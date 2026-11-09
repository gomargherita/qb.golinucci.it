# 08 — Email templates (KAN-12)

## Obiettivo

Testi email modificabili da BO, con audit (Spatie activitylog), senza deploy per ogni ritocco copy.

## Modello

Tabella `email_templates`: `key`, `name`, `subject`, `body` (`MEDIUMTEXT`), `bcc`, `from_email`, `from_name`, `is_active`, `updated_by`.

Variabili nel testo: `{{nome}}` sostituite da `EmailTemplateService`.

`body` può essere testo semplice **oppure** HTML Unlayer. Il JSON del design è salvato in un commento `<!--qb-unlayer-design:…-->` in testa a `body` (serve solo al BO; in invio viene strippato).

## BO

- Menu admin → **Email templates**
- Edit subject / body / bcc / **From** (email + nome) / active
- Body: **Unlayer** (embed.js, `displayMode: email`) — blocchi, immagini, pulsanti, anteprima desktop/mobile, Merge Tags
- Chiave tecnica non editabile (evita rotture codice)
- Script: `public/js/email-template-editor.js` (niente mix)

## Invio

`EmailTemplateService::applyToMail()`:

- HTML Unlayer → view `emails.bo-html` + text `emails.bo-text`
- Testo semplice (template non ancora salvati con l’editor) → `MailMessage::line()` come prima

From: se `from_email` è valorizzato nel template, `applyToMail()` lo usa; se è vuoto resta il fallback nella Notification. **`bank_transfer_confirmed` resta vuoto di proposito**: From sanitari vs bancari è scelto in PHP dal prodotto. `paypal_payment_cancel` → `posta@golinucci.it`. La PEC bancari (`mailer('pec')`, `bancari@pec.golinucci.it`) non viene sovrascritta. CC e allegati restano nel codice.

Giovane medico (prodotto 5) usa i template medici (`renewal_invite_medici`, From sanitari), non quelli bancari.

## Template attivi

| Key | Notification |
|-----|----------------|
| `renewal_otp` | `RenewalOtpNotification` |
| `final_documents_medici` | `FinalEmailWithAttachments` (type 1) |
| `final_documents_bancari` | `FinalEmailWithAttachments` (type 2) |
| `renewal_invite_medici` | `FirstEmailWithCredentials`, `RenewEmail`, `ReminderEmail`, `ReminderEmailPassed` (product_id 1) |
| `renewal_invite` | stessi, per ER/RI Bancari e altri prodotti |
| `bank_transfer_medici` | `BankTransferGatewayPayment` (product 1 e 5) |
| `bank_transfer_er` | `BankTransferGatewayPayment` (product 2) |
| `bank_transfer_bancari` | `BankTransferGatewayPayment` (product 3 e 4) |
| `bank_transfer_confirmed` | `ConfirmedOfflinePayment` |
| `paypal_payment_cancel` | `PaypalGatewayPaymentCancel` |
| `importo_libero` | `ImportoLiberoEmail` |
| `importo_libero_conditions` | `ImportoLiberoConditionsEmail` |
| `benefit` | `BenefitEmail` |
| `ehealth` | `EhealthEmail` |
| `salute_sport` | `SaluteSportEmail` |

Copy reminder oggi identica all’invito: riusano gli stessi template Unlayer (niente tasti BO, niente nuove key). Se in seguito serve un sollecito diverso, si splitta la key.

Fallback hardcoded se template assente / inattivo.

Se il body è ancora testo semplice, il pulsante «Rinnova la tua polizza» lo aggiunge il codice. Dopo il salvataggio Unlayer va messo un pulsante con Merge Tag `{{url}}`.

`FinalEmailWithAttachmentsNotPec` riusa `final_documents_medici` / `final_documents_bancari`. Allegati e canale PEC restano nel codice.

## Prossimi

Tempistiche cron `mail:renew`: `config/renewals.php` (`RENEWAL_INVITE_DAYS_BEFORE`, lookback, reminder ±N). I template coprono subject/body/From. BO per i giorni = fase 3.
