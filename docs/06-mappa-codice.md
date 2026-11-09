# 06 — Mappa del codice (dove intervenire)

## Controllers

| File | Ruolo |
|------|--------|
| `app/Http/Controllers/SiteBuyProductController.php` | **Cuore front Medici**: buy, renew, step 1–7, PayPal create/capture |
| `app/Http/Controllers/CartController.php` | Admin carrelli, renew, DoPrint orchestration, regen certificato, email, decorrenza |
| `app/Http/Controllers/SiteController.php` | Home, prodotti, importo libero, eHealth, benefit |
| `app/Http/Controllers/ImportoLiberoController.php` | Admin / gestione importo libero |
| `app/Http/Controllers/YousignController.php` | Callback firma |
| `app/Http/Controllers/PaymentController.php` | Update/delete payment (admin) |
| `app/Http/Controllers/ClientDetailsController.php` | Anagrafica BO (`contact_ref` / `version_ref`, KAN-13) |
| `app/Http/Controllers/PaypalWebhookController.php` | Webhook PayPal (KAN-6) |
| `app/Http/Controllers/StripeWebhookController.php` | Webhook Stripe (codice presente, gateway **spento**) |
| `app/Http/Controllers/CertificateController.php` | Certificati |
| `app/Http/Controllers/BordereauxController.php` | Export bordereaux + vista importo libero admin |

## Services

| File | Ruolo |
|------|--------|
| `app/Services/LloydsDoprintService.php` | SOAP handshake → download |
| `app/Services/LloydsDoprintPipelineService.php` | Pipeline stati DoPrint post-pagamento |
| `app/Console/Commands/ReconcileDoprintStuck.php` | Retry carrelli Medici pagati fermi in DoPrint |
| `app/Services/Payments/PaypalCaptureService.php` | createOrder / capture idempotente |
| `app/Services/Payments/CompletePaidCartService.php` | paid → PDF / email / DoPrint via job |
| `app/Jobs/CompletePaidCartJob.php` | Job unique post-pagamento (Redis staging) |
| `app/Jobs/SendRenewalsJob.php` | Tick 08:00 → `mail:renew` in coda |
| `app/Jobs/ReconcileDoprintStuckJob.php` | Tick orario → retry DoPrint in coda |
| `app/Services/Renewals/RenewalCartService.php` | Bozze / date rinnovo (KAN-7, KAN-40) |
| `app/Services/Yousign/YousignApiService.php` | Orchestrazione firma su cart |
| `app/Services/Yousign/YousignApiLaravel.php` | Client HTTP API v3 |
| `app/Services/*ToPdfService.php` | PDF locali (pagamento, dichiarazioni, datasheet, bancari) |

## Models da conoscere

`Cart`, `Payment`, `Certificate`, `Signature`, `ClientDetail`, `Product`, `ProductOffer`, `ProductStep`, `Reminder`, `User`

Relazioni utili: `Cart::payment`, `certificate`, `signature` (HasOneThrough).

## Frontend (Inertia / Vue)

| Path | Ruolo |
|------|--------|
| `resources/js/Shared/Front/Steps/Step*.vue` | Step acquisto front (Step4 = PayPal JS) |
| `resources/js/Pages/Carts/Steps/` | Step lato admin |
| `resources/js/Pages/Sites/` | Importo libero, eHealth, ecc. |

PayPal return: fetch GET da `Step4.vue` verso `store_step4_paypal/...`.

## Route

Quasi tutto in `routes/web.php` (prefisso `admin` + front). Nessun `routes/api.php` ricco: le integrazioni espongono endpoint web.

## Config da listare in onboarding

- `config/paypal.php`
- `config/stripe.php` / `config/payments.php` (Stripe spento, KAN-36)
- `config/queue.php` (redis + database, `retry_after` 240)
- `config/doprint.php`
- `config/yousign.php`
- `config/mail.php` + eventuali `mail-extra` / `renew_email` (se presenti)

## Interventi P0/P1 → file tipici

| Obiettivo | Parti da toccare per prime |
|-----------|----------------------------|
| Chiusura firma (KAN-5) | `VerifyCsrfToken` (`yousign/*`), `YousignProcedureCompletionService`, `yousign:reconcile-stuck` |
| Accesso rinnovo (KAN-4/8) | `RenewalAccessService` + `RenewalOtpService` + `RenewalChallengeController` + `Auth/RenewalChallenge.vue` |
| Continuità rinnovi (KAN-7) | `RenewalCartService`, `SiteBuyProductController::buy/renew`, `CartController::renew`, model `Renewal` |
| Pagamenti PayPal (KAN-6) | `PaypalCaptureService`, `CompletePaidCartService`, `PaypalWebhookController`, `paypal:reconcile-unpaid`, `Step4.vue` |
| CSRF PayPal dopo OTP (KAN-39) | `VerifyCsrfToken` except `products/*/store_step4/*/paypal`, `Step4.vue` |
| Coda Redis (KAN-10) | `config/queue.php`, `.env` `QUEUE_CONNECTION`, Supervisor `queue:work redis`, `CompletePaidCartJob` |
| Bozza rinnovo già pagata (KAN-40) | `RenewalCartService::findOpenRenewalDraft` |
| Anti-duplicato carrello | `SiteBuyProductController::buy`, regole su bozze aperte |
| Continuità rinnovi | `renew` methods, tabella `renewals`, vincoli/backfill |
| DoPrint recovery | `CartController` + `LloydsDoprintService`, UI stati admin |
| Email editabili | nuova feature; oggi solo `app/Notifications/*` |
| contact/version_ref | `ClientDetailsController` + form Vue collegato |

## Come orientarsi in debug

1. Individua `cart_ref` / `carts.id`
2. Controlla `product_step_id`, `payments.paid` + `transaction_ref`
3. Controlla `certificates.doprint_current_step`
4. Controlla `signatures.status` + `procedure_id`
5. Log Laravel + `renew_email.log` + tabelle `jobs` / `failed_jobs` (prod) o Redis `QUEUE_CONNECTION` (staging)
6. Solo dopo: pannelli PayPal / DoPrint / Yousign (titolarità Golinucci)
