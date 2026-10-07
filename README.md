# QB (Golinucci)

Gestionale assicurativo **QB** — Laravel 8 + Vue 3 / Inertia.  
Produzione: `https://qb.golinucci.it`

Documentazione tecnica più ampia: cartella [`docs/`](docs/README.md).

## Stack

| Layer | Tecnologia |
|-------|------------|
| Backend | PHP 8.1 (prod) / Laravel 8.83 |
| Frontend | Vue 3, Inertia.js, Tailwind, Laravel Mix |
| Database | MySQL 8 (DigitalOcean Managed in prod) |
| Code | Laravel `database` + Supervisor (prod) |
| Cache / session | `file` (prod attuale) |
| Pagamenti | PayPal (`srmklive/paypal`); Stripe in programma |
| Documenti | Lloyd’s DoPrint (SOAP) |
| Firma | Yousign API v3 |
| Email | Postmark |

## Prerequisiti locali

- PHP **8.1** (come in produzione). Con PHP 8.2/8.3 il `composer.lock` attuale può rifiutare pacchetti (es. `inertia-laravel` limita a `~8.0|~8.1`)
- Estensioni PHP: `curl`, `dom`, `exif`, `gd`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip`, `soap` (DoPrint)
- Composer 2.x (se non è in PATH: `php composer.phar …` — lo script non va committato)
- Node.js **18+** e npm
- MySQL 8 (locale o staging in sola lettura)

## Avvio rapido

```bash
# 1. Dipendenze PHP
composer install

# 2. Ambiente
cp .env.example .env
php artisan key:generate

# 3. Configura DB e integrazioni in .env (vedi sotto)

# 4. Migrazioni (solo se hai un DB dedicato di sviluppo — NON su prod)
php artisan migrate

# 5. Frontend
npm install
npm run dev
# oppure build una tantum:
npm run production

# 6. Server applicativo
php artisan serve
```

Apri `APP_URL` (default `http://localhost:8000`).

Per le code in locale (come in prod usa `database`):

```bash
php artisan queue:work
```

## Cosa manca / da richiedere (non è nel repo)

| Cosa | Perché |
|------|--------|
| File `.env` reale | Escluso dal Git (segreto). Parti da `.env.example` |
| Credenziali DB staging/prod | Vittorio / Bitmark |
| PayPal, DoPrint, Yousign, Postmark | Account Golinucci — accessi tecnici da richiedere |
| Disk `private` con XML/template Lloyd’s e PDF | Su server in `storage/app/private` — non nel tarball completo |
| `APP_KEY` | Generata in locale con `key:generate`; in staging/prod non sovrascrivere |

## Note importanti

- **Non** eseguire `db:wipe` / seed di prod o staging condiviso.
- In root possono esserci file “one-shot” tipo tinker lasciati dallo storico: sono in `.gitignore`, non committarli.
- Deploy e pipeline: disegno in [`docs/07-deploy.md`](docs/07-deploy.md) — Jira **KAN-28** (relates KAN-16 / KAN-26).
- Ops legacy (cron, supervisor, cache in prod): vedi note in fondo o `docs/`.

## Comandi utili (produzione / staging)

Dopo modifiche a `.env` sul server:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan optimize
```

Scheduler (crontab `www-data`):

```text
* * * * * cd /var/www/qb.golinucci.it && php artisan schedule:run >> /dev/null 2>&1
```

Code: Supervisor → `laravel-worker` (vedi documentazione Laravel 8 queues).
