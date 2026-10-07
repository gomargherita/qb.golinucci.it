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
| `app/Http/Controllers/ClientDetailsController.php` | Anagrafica BO (`contact_ref` / typo `varsion_ref`) |
| `app/Http/Controllers/CertificateController.php` | Certificati |
| `app/Http/Controllers/BordereauxController.php` | Export bordereaux + vista importo libero admin |

## Services

| File | Ruolo |
|------|--------|
| `app/Services/LloydsDoprintService.php` | SOAP handshake → download |
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
5. Log Laravel + `renew_email.log` + `failed_jobs`
6. Solo dopo: pannelli PayPal / DoPrint / Yousign (titolarità Golinucci)
