<?php
/**
 * Read-only volume audit for SMS/OTP provider quotes.
 * Run on prod: cd /var/www/qb.golinucci.it && php scripts/audit-otp-sms-volumes.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function section(string $title): void
{
    echo "\n=== {$title} ===\n";
}

function printRows(array $rows): void
{
    if ($rows === []) {
        echo "(no rows)\n";
        return;
    }
    foreach ($rows as $row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
}

echo 'APP_URL=' . config('app.url') . ' DB=' . config('database.connections.mysql.database') . ' @ ' . date('c') . "\n";

section('Note for quote');
echo "QB renewal OTP today = EMAIL (renewal_otps). Planned SMS OTP ≈ same volume as OTP issued + resends.\n";
echo "Yousign firma uses otp_sms today (billed by Yousign, not this SMS gateway) ≈ signatures count.\n";

section('Paid payments by year (proxy: completed checkout transactions)');
printRows(DB::select(
    'SELECT YEAR(paid_at) AS y, COUNT(*) AS cnt FROM payments WHERE paid = 1 AND paid_at IS NOT NULL GROUP BY y ORDER BY y DESC'
));

section('Paid payments last 12 months by month');
printRows(DB::select(
    'SELECT DATE_FORMAT(paid_at, "%Y-%m") AS ym, COUNT(*) AS cnt
     FROM payments WHERE paid = 1 AND paid_at IS NOT NULL AND paid_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
     GROUP BY ym ORDER BY ym DESC'
));

section('Certificates issued by year (polizze emesse)');
printRows(DB::select(
    'SELECT YEAR(created_at) AS y, COUNT(*) AS cnt FROM certificates GROUP BY y ORDER BY y DESC'
));

section('Yousign signature rows by year (each ≈ 1 Yousign OTP SMS)');
printRows(DB::select(
    'SELECT YEAR(created_at) AS y, COUNT(*) AS cnt FROM signatures GROUP BY y ORDER BY y DESC'
));

section('Renewal carts linked (renewals table)');
printRows(DB::select(
    'SELECT YEAR(c.created_at) AS y, COUNT(*) AS cnt
     FROM renewals r
     INNER JOIN carts c ON c.cart_ref = r.new_cart_ref
     GROUP BY y ORDER BY y DESC'
));

section('Renewal reminder emails sent (renewals_email)');
printRows(DB::select(
    'SELECT COUNT(*) AS total FROM renewals_email'
));

section('Reminders rows by year (renewal comms pipeline)');
printRows(DB::select(
    'SELECT YEAR(created_at) AS y, COUNT(*) AS cnt FROM reminders WHERE deleted_at IS NULL GROUP BY y ORDER BY y DESC'
));

section('Renewal OTP issued (email today; SMS if migrated)');
printRows(DB::select(
    'SELECT COUNT(*) AS total FROM renewal_otps'
));
printRows(DB::select(
    'SELECT DATE_FORMAT(created_at, "%Y-%m") AS ym, COUNT(*) AS cnt
     FROM renewal_otps WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
     GROUP BY ym ORDER BY ym DESC'
));

section('Suggested SMS OTP estimate (renewal access only)');
$renewalOtpsLast12 = (int) (DB::selectOne(
    'SELECT COUNT(*) AS c FROM renewal_otps WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'
)->c ?? 0);
$renewalsLast12 = (int) (DB::selectOne(
    'SELECT COUNT(*) AS c FROM renewals r INNER JOIN carts c ON c.cart_ref = r.new_cart_ref
     WHERE c.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'
)->c ?? 0);
$paidLast12 = (int) (DB::selectOne(
    'SELECT COUNT(*) AS c FROM payments WHERE paid = 1 AND paid_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'
)->c ?? 0);

// Before KAN-8 prod rollout, renewal_otps may be near zero; use renewals or paid renewals as upper bound.
$annualRenewalSmsLow = max($renewalOtpsLast12, (int) floor($renewalsLast12 * 1.0));
$annualRenewalSmsHigh = max($renewalOtpsLast12, (int) ceil($renewalsLast12 * 1.5)); // issue + occasional resend

echo "Last 12 months renewal_otps rows: {$renewalOtpsLast12}\n";
echo "Last 12 months renewal carts (new_cart): {$renewalsLast12}\n";
echo "Last 12 months paid payments (all products): {$paidLast12}\n";
echo "Quote range renewal SMS OTP / year (after go-live): ~{$annualRenewalSmsLow} – {$annualRenewalSmsHigh}\n";
echo "Quote range renewal SMS OTP / month (avg): ~" . max(1, (int) round($annualRenewalSmsLow / 12)) . ' – ' . max(1, (int) ceil($annualRenewalSmsHigh / 12)) . "\n";
