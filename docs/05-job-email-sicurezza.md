# 05 — Job, email e sicurezza

## Scheduler (`app/Console/Kernel.php`)

| Comando | Quando | Log |
|---------|--------|-----|
| `mail:renew` | ogni giorno 08:00 | `renew_email.log` + email on failure |
| `mail:first_send` | **commentato** | era previsto daily 08:00 |

Implementazione: `app/Console/Commands/SendRenewals.php`.

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
- prossima evoluzione: OTP 1h sul link (KAN-8)

### 2. CSRF sui webhook Yousign — **risolto (KAN-5)**

`yousign/*` escluso da CSRF; completion idempotente + `yousign:reconcile-stuck` per pratiche ferme.  
Vedi [03-integrazioni.md](03-integrazioni.md).

### 3. Logging

Capture PayPal e altri oggetti vengono loggati (`Log::info`). In produzione il log può crescere e contenere dati sensibili (come già notato da FMP).

### 4. SQL grezzo

Vari punti usano `DB::raw` / `DB::statement` con concatenazione di `cart_ref` (rinnovi, renewals_email). Rischio injection se i ref non fossero sempre generati internamente; comunque fragile e da sostituire con query builder/Eloquent.

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
