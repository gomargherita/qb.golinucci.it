# 05 — Job, email e sicurezza

## Scheduler (`app/Console/Kernel.php`)

Il cron è solo l’orologio (`* * * * * php artisan schedule:run`). Il lavoro va in **coda** (Redis staging / `database` prod finché Vittorio non installa Redis).

| Tick scheduler | Job Redis | Quando |
|----------------|-----------|--------|
| `SendRenewalsJob` | `php artisan mail:renew` | ogni giorno 08:00 |
| `ReconcileDoprintStuckJob` | `doprint:reconcile-stuck --apply` | ogni ora |
| `mail:first_send` | — | **commentato** |

Serve crontab `www-data` (chiesto a Vittorio, KAN-10). Comandi CLI restano per dry-run manuale.

Implementazione: `app/Console/Commands/SendRenewals.php`, `app/Jobs/SendRenewalsJob.php`, `app/Jobs/ReconcileDoprintStuckJob.php`.

Logica (sintesi):

- Seleziona certificati con `end_validity_date` tra oggi−60g e oggi+30g
- Esclude chi ha `client_details.with_accident = 1`
- Se `cart_ref` non è già in `renewals_email`, invia notifiche di rinnovo/reminder e traccia l’invio
- Crea/aggiorna `reminders` e gestisce password temporanee (vedi sotto)

Altri comandi (manuali / import): `MedImporter`, `BankersImporter`, `UserUpdate`, `SendFirstEmail`.

## Email: perché Golinucci non può modificarle da soli

Le Notification Laravel contengono **testo, scadenze e regole** inline (PHP). Non c’è modello “email template” in DB né UI admin.

Per renderle gestibili serve una feature dedicata (template + variabili + audit), non un tweak isolato.

## Criticità sicurezza confermate nel codice

### 1. Password in chiaro su `reminders.pw` (P0) — **risolto (KAN-4)**

**Prima:** le Notification di rinnovo generavano password, le scrivevano in chiaro su `reminders.pw`, le mettevano in email e sovrascrivevano `users.password`.

**Ora:**
- `RenewalAccessService` emette un token opaco (solo hash SHA-256 su `reminders.access_token_hash` + scadenza)
- CTA email → `GET /renewal/access/{cart_ref}?token=…` → login sessione → redirect al rinnovo
- nessuna password in email; `users.password` non viene più toccato dalle mail di rinnovo
- migration `2026_10_05_120000_secure_reminder_access_tokens` azzera gli storici `pw`
- OTP 1h sul link (KAN-8). Smoke 23/10: OTP ok; qualche template ha ancora From «Laravel» (KAN-12).

### 2. CSRF sui webhook Yousign — **risolto (KAN-5)**

`yousign/*` escluso da CSRF; completion idempotente + `yousign:reconcile-stuck` per pratiche ferme.  
Vedi [03-integrazioni.md](03-integrazioni.md).

**CSRF PayPal createOrder (KAN-39):** dopo OTP, `POST .../store_step4/{cart}/paypal` 419. Except staging attivo; JS `Step4.vue` locale non ancora mixato. `errors/419.blade.php` usa sempre il testo «30 giorni» (fuorviante).

**Coda post-pagamento (KAN-10):** `CompletePaidCartJob` (`tries=5`, timeout 180, unique `complete-paid-cart-{cartId}`). Staging Redis 23/10 OK. Produzione ancora `database`.

**KAN-37:** prodotti Bancari 3 e 4 mandano una sola mail «Polizza e documenti». Il prodotto 2 (ER) resta PEC più una copia ordinaria con oggetto e testo diversi («copia della PEC»). Non era un doppio job Redis.

### 3. Logging

Capture PayPal e altri oggetti vengono loggati (`Log::info`). In produzione il log può crescere e contenere dati sensibili (come già notato da FMP).

### 4. SQL grezzo — **parziale (KAN-12, 28/10)**

`mail:renew` e area utente (`SiteController` rinnovo già fatto) usano query builder su `renewals` / `renewals_email`. Restano altri `DB::raw` (select elenco colonne, DATE_FORMAT) senza concatenazione di input.

### 5. Refuso backoffice `contact_ref` / `version_ref` — **risolto (KAN-13)**

Campi presenti in Edit/Create BO; payload e validazione usano `version_ref`; il percorso owner salva entrambi i ref.

## File di lavoro sospetti in root repo

Nello snapshot compaiono file “one-shot” stile tinker (query cart, delete renewals_email, ecc.). Non sono parte dell’app: da non deployare e da ripulire quando si mette ordine al repository aziendale.

## Checklist intervento sicurezza (ordine suggerito)

1. ~~Smettere di scrivere `reminders.pw`; token hash + wipe storici~~ (KAN-4)  
2. ~~OTP email 1h sul link di rinnovo~~ (KAN-8)
3. ~~Escludere webhook Yousign da CSRF + secret di verifica~~ (KAN-5) 
4. Ridurre logging PayPal/Yousign; rotazione log  
5. Hardening permessi `.env` sul server (infra Vittorio + app)
