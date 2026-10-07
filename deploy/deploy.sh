#!/usr/bin/env bash
# QB deploy — releases/ + atomic symlink (see docs/07-deploy.md)
# Usage:
#   ./deploy/deploy.sh staging
#   ./deploy/deploy.sh prod
#   ./deploy/deploy.sh staging --dry-run
#   ./deploy/deploy.sh prod --rollback
#
# Requires on the server: PHP 8.1, composer, rsync/git as chosen.
# Path/user SSH: set in deploy/env/<env>.env (not committed with secrets).

set -euo pipefail

ENV_NAME="${1:-}"
DRY_RUN=0
DO_ROLLBACK=0

shift || true
while [[ $# -gt 0 ]]; do
  case "$1" in
    --dry-run) DRY_RUN=1 ;;
    --rollback) DO_ROLLBACK=1 ;;
    *) echo "Unknown option: $1" >&2; exit 1 ;;
  esac
  shift
done

if [[ -z "$ENV_NAME" || ( "$ENV_NAME" != "staging" && "$ENV_NAME" != "prod" ) ]]; then
  echo "Usage: $0 staging|prod [--dry-run] [--rollback]" >&2
  exit 1
fi

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${ROOT_DIR}/deploy/env/${ENV_NAME}.env"

if [[ ! -f "$ENV_FILE" ]]; then
  echo "Missing ${ENV_FILE}. Copy from deploy/env/${ENV_NAME}.env.example" >&2
  exit 1
fi

# shellcheck disable=SC1090
source "$ENV_FILE"

: "${DEPLOY_HOST:?}"
: "${DEPLOY_USER:?}"
: "${DEPLOY_PATH:?}"
: "${PHP_BIN:=php}"
: "${COMPOSER_BIN:=composer}"
: "${KEEP_RELEASES:=5}"
: "${RUN_MIGRATE:=1}"
: "${RUN_NPM_ON_SERVER:=0}"

SSH_TARGET="${DEPLOY_USER}@${DEPLOY_HOST}"
SSH_OPTS=(-o BatchMode=yes)
if [[ -n "${DEPLOY_SSH_KEY:-}" ]]; then
  SSH_OPTS+=(-i "$DEPLOY_SSH_KEY")
fi

remote() {
  if [[ "$DRY_RUN" -eq 1 ]]; then
    echo "[dry-run] ssh ${SSH_TARGET} $*"
    return 0
  fi
  ssh "${SSH_OPTS[@]}" "$SSH_TARGET" "$@"
}

if [[ "$DO_ROLLBACK" -eq 1 ]]; then
  remote "bash -s" <<EOF
set -euo pipefail
cd "${DEPLOY_PATH}"
prev=\$(ls -1dt releases/* | sed -n '2p' || true)
if [[ -z "\${prev}" ]]; then
  echo "No previous release to rollback to" >&2
  exit 1
fi
ln -sfn "\${prev}" current
cd current
${PHP_BIN} artisan config:cache
${PHP_BIN} artisan route:cache
${PHP_BIN} artisan view:cache
${PHP_BIN} artisan queue:restart || true
echo "Rolled back to \${prev}"
EOF
  exit 0
fi

SHA="$(git -C "$ROOT_DIR" rev-parse --short HEAD 2>/dev/null || echo local)"
RELEASE_ID="$(date +%Y%m%d_%H%M%S)_${SHA}"
RELEASE_DIR="${DEPLOY_PATH}/releases/${RELEASE_ID}"

echo "Deploy ${ENV_NAME} → ${SSH_TARGET}:${RELEASE_DIR}"

# Sync code (exclude secrets/deps/junk)
RSYNC_EXCLUDES=(
  --exclude '.git'
  --exclude '.env'
  --exclude '.env.*'
  --exclude 'node_modules'
  --exclude 'vendor'
  --exclude 'storage/logs'
  --exclude 'storage/framework/cache'
  --exclude 'storage/framework/sessions'
  --exclude 'storage/framework/views'
  --exclude 'bootstrap/cache/*.php'
  --exclude 'deploy/env/*.env'
  --exclude '.cursor'
  --exclude 'composer.phar'
)

if [[ "$DRY_RUN" -eq 1 ]]; then
  echo "[dry-run] would rsync to ${RELEASE_DIR}"
else
  remote "mkdir -p '${DEPLOY_PATH}/releases' '${DEPLOY_PATH}/shared/storage' '${RELEASE_DIR}'"
  remote "test -f '${DEPLOY_PATH}/shared/.env' || (echo 'Missing shared/.env on server' >&2; exit 1)"
  rsync -az --delete "${RSYNC_EXCLUDES[@]}" -e "ssh ${SSH_OPTS[*]}" \
    "${ROOT_DIR}/" "${SSH_TARGET}:${RELEASE_DIR}/"
fi

remote "bash -s" <<EOF
set -euo pipefail
cd "${RELEASE_DIR}"

ln -sfn "${DEPLOY_PATH}/shared/.env" .env
rm -rf storage
ln -sfn "${DEPLOY_PATH}/shared/storage" storage
mkdir -p bootstrap/cache

${COMPOSER_BIN} install --no-dev --optimize-autoloader --no-interaction

if [[ "${RUN_NPM_ON_SERVER}" == "1" ]]; then
  npm ci
  npm run production
fi

if [[ "${RUN_MIGRATE}" == "1" ]]; then
  ${PHP_BIN} artisan migrate --force
fi

${PHP_BIN} artisan config:cache
${PHP_BIN} artisan route:cache
${PHP_BIN} artisan view:cache

ln -sfn "${RELEASE_DIR}" "${DEPLOY_PATH}/current"
cd "${DEPLOY_PATH}/current"
${PHP_BIN} artisan queue:restart || true

cd "${DEPLOY_PATH}/releases"
ls -1dt */ 2>/dev/null | tail -n +$((KEEP_RELEASES + 1)) | xargs -r rm -rf

echo "OK current → ${RELEASE_DIR}"
EOF
