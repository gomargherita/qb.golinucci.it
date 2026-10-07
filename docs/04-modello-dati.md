# 04 — Modello dati (QB2)

Fonte: `docs/qb2_schema.sql` (MySQL 8). AUTO_INCREMENT elevati (es. carts ~18k) confermano storico lungo.

## Entità del percorso Medici

```text
users 1──1 client_details
  │
  └──* carts *──1 products
        │           └── product_type, offers, declarations, steps, counters…
        ├──1 payments ── payment_gateways
        ├──1 certificates ── contracts, doprint_*
        │         └──* signatures (di fatto 1 procedura attiva)
        └── reminders (flag email + pw in chiaro)
```

Tabella ponte rinnovi:

```text
renewals (old_cart_ref PK → new_cart_ref)
renewals_email (traccia “già inviata email rinnovo” per old_cart_ref)
```

## Tabelle chiave

### `carts`
Identità processo commerciale. Campi importanti: `cart_ref` (unique, 13 char), `product_step_id`, `renewal_date`, `retroactivity`, importi, `locked` (Y/N), `expired_at`, soft delete.

### `payments`
`cart_id`, `payment_gateway_id`, `payment_no` (unique, assegnato a pagamento avvenuto), `paid`, `transaction_ref` (ordine PayPal), `paid_at`.  
Nessuna tabella “payment_attempts”.

### `certificates`
Date polizza + stato DoPrint: `doprint_token`, `doprint_current_step`, `doprint_certificate_no` (unique), `active`.

### `signatures`
`procedure_id`, `file_id`, `member_signer_id`, `signature_link`, `status`. Unique su procedure/file/member id.

### `client_details`
Anagrafica: CF, telefono, indirizzo, `contact_ref`, `version_ref`, flag (`confirmation`, `with_accident`, …).
Editabili da BO (KAN-13).

### `reminders`
Flag temporali email (`one_week_before`, `two_weeks_after`, …) + colonna **`pw` varchar(255)** usata per password temporanee in chiaro.

### `products` / `product_offers` / `product_steps` / `product_declarations` / …
Configurazione commerciale parziale a DB; molte regole restano nel codice.

### `product_counters` / `doprint_counters`
Progressivi annuali per numerazione pagamenti e certificati.

### Altro
- `importo_libero_buyers`, `free_amounts`: pagamenti liberi  
- `ehealth_*`: prodotto eHealth  
- `bordereaux_*`: export  
- `activity_log`: Spatie  
- `jobs` / `failed_jobs`: code DB  
- `accounts`, `roles`, `permissions`: multi-account / Spatie  

## Vincoli deboli (rilevanti per i bug)

| Area | Osservazione |
|------|----------------|
| Rinnovo | Solo `renewals`; nessun unique (user, product, year) |
| Bozza | Nessun vincolo “una sola bozza aperta per prodotto” |
| Pagamento | 1:1 di fatto con cart; tentativi non versionati |
| Firma | Stati stringa liberi; niente machine-state enforcement a DB |

## Conteggio circa (schema dump)

Indicativo dagli AUTO_INCREMENT: users ~6.8k, carts ~18k, payments ~15k, certificates ~15k, signatures ~4.5k, reminders ~9.5k.
