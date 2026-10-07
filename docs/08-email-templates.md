# 08 — Email templates (KAN-12)

## Obiettivo

Testi email modificabili da BO, con audit (Spatie activitylog), senza deploy per ogni ritocco copy.

## Modello

Tabella `email_templates`: `key`, `name`, `subject`, `body`, `bcc`, `is_active`, `updated_by`.

Variabili nel testo: `{{nome}}` sostituite da `EmailTemplateService`.

## BO

- Menu admin → **Email templates**
- Edit subject/body/bcc/active
- Chiave tecnica non editabile (evita rotture codice)

## Pilot

`renewal_otp` usato da `RenewalOtpNotification` (fallback hardcoded se template assente).

## Prossimi template da migrare

`renew_email`, `reminder_email`, `reminder_email_passed`, finali Yousign, bonifico, ecc. — stessa `key` + wiring Notification.

## Timing / scadenze

I giorni del cron `mail:renew` restano in codice/config per ora; i template coprono subject/body. Scheduling configurabile = fase 2.
