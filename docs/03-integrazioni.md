# 03 — Integrazioni esterne

Config in `config/paypal.php`, `config/doprint.php`, `config/yousign.php`. Segreti solo via `.env` (non presente nello snapshot).

---

## 1. PayPal (`srmklive/paypal`)

**Dove:** `SiteBuyProductController` (carrello), metodi analoghi su importo libero / eHealth in `SiteController`.

**Flusso (KAN-6):**

1. `PaypalCaptureService::createOrReuseOrder` → `payments.transaction_ref` (+ riuso ordine CREATED/APPROVED)
2. UI PayPal (Vue) autorizza; `onApprove` chiama return con `payment_id` **e** `order_id`
3. `PaypalCaptureService::captureAndComplete` (lock riga, idempotente, gestisce `ORDER_ALREADY_CAPTURED`)
4. `CompletePaidCartService` → paid / payment_no / PDF / step 5
5. **Webhook** `POST /paypal/webhook` per chiusura anche se il browser non torna
6. Recovery: `php artisan paypal:reconcile-unpaid` / `--apply`
7. Audit append-only: tabella `payment_events` + `payments.paypal_status`

**Gateway DB:** tabella `payment_gateways` (paypal=1, bonifico=2).

---

## 2. Lloyd’s DoPrint (SOAP)

**Service:** `app/Services/LloydsDoprintService.php`

**Config:** sandbox vs live in base a `App::environment(['local','staging'])`.

**Pipeline** (persistita su `certificates`):

| `doprint_current_step` | Azione |
|------------------------|--------|
| (null) → handshake | Ottiene `SessionToken` → `doprint_token` |
| validation | XML da template privati (`lloyds_new_base.xml`, `lloyds_gmedici.xml`, …) |
| upload | Allegati |
| transaction | Transazione |
| download | Scarica PDF polizza |
| completed | Fine |

Orchestrazione principalmente in `CartController` (metodi generazione Lloyds / step documento) e flusso front step 5.

**Note:**

- Template XML su disk `private` (non nel DB)
- Scelta template legata a `product_id` (1 vs 5); ramo “renew XML” presente ma di fatto bypassato (`if (true)`)
- Numerazione certificato: `doprint_certificate_no` + contatori `doprint_counters`
- Errori SOAP → eccezioni; recovery parziale (si riparte dallo step salvato), ma pratiche possono restare intermedie

---

## 3. Yousign (API v3)

**Service:** `YousignApiService` + client HTTP `YousignApiLaravel`  
**Config:** `YOUSIGN_KEY`, `YOUSIGN_API_URL` (default `https://api.yousign.app/v3/`), URL app, email/telefono staging.

**Avvio procedura (`startYousignClientAndLaunchProcedure`):**

1. Crea riga `signatures` con `status = init`
2. `createProcedure` + `addMember` (in staging forza email/telefono di test)
3. Carica `certificate.pdf` come file firma
4. Server stamp, genera link firmatario → `signature_link`
5. Stati successivi: `server_stamp_ok` / errori / `member_finished` / `procedure_finished` / refused / expired

**Callback HTTP (`routes/web.php`):**

| Route | Metodo | Handler |
|-------|--------|---------|
| `/yousign/member_finished` | GET | (logica storica commentata nel controller) |
| `/yousign/procedure_finished` | POST | scarica PDF firmato, step 7, email finale |
| `/yousign/procedure_refused` | GET | status refused |
| `/yousign/procedure_error` | GET | status error |
| `/yousign/procedure_expired` | GET | status expired |
| `/yousign/server_stamps_*` | GET | stamp ok/error |

**Webhook hardening (KAN-5):**
- CSRF: `yousign/*` escluso in `VerifyCsrfToken`
- Auth opzionale: middleware `VerifyYousignWebhook` + `YOUSIGN_WEBHOOK_SECRET`
- Completion centralizzata in `YousignProcedureCompletionService` (idempotente)
- Lookup cart per `cart_ref` **o** `procedure_id` / payload v3 `data.signature_request.id`
- Recovery: `php artisan yousign:reconcile-stuck` (dry-run) / `--apply`

---

## 4. Postmark / email

Pacchetto SwiftMailer Postmark. Testi e timing delle notifiche sono **nel codice PHP** (`app/Notifications/*`), non editabili da backoffice.

Principali notifiche:

- `RenewEmail`, `ReminderEmail`, `ReminderEmailPassed` (cron rinnovi)
- `FinalEmailWithAttachments` / `…NotPec`
- `FirstEmailWithCredentials`
- `BankTransferGatewayPayment`, `PaypalGatewayPaymentCancel`
- Importo libero, eHealth, Benefit, SaluteSport

Job schedulato attivo: `mail:renew` ogni giorno alle 08:00 (`app/Console/Kernel.php`).

---

## 5. Generazione PDF locali

Servizi DomPDF / custom:

- `PaymentCertificateToPdfService` (proforma / certificato pagamento)
- `DeclarationsToPdfService`, `DatasheetToPdfService`, `NewApplicationToPdfService`
- `PaymentBankersToPdfService`

File su disk `private`, tipicamente sotto `certificates/user_{id}/cart_{id}/`.
