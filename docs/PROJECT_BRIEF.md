# Golinucci QB — Brief di progetto

Sistema gestionale assicurativo **QB** (`https://qb.golinucci.it/`), focus iniziale area **MEDICI**. Cliente: Golinucci. Infrastruttura server: Vittorio Carraro (Bitmark) — non gestisce l’applicativo.

## Stack e ambienti

| Ambiente   | Host / IP           | Note |
|-----------|---------------------|------|
| Produzione | qb.golinucci.it / `46.101.103.49` | Laravel su droplet DigitalOcean; PHP 8.1 |
| Staging    | `157.230.99.252`    | PHP 8.1; DB staging da verificare (può essere vecchio) |
| qb1        | qb1.golinucci.it / `46.101.202.19` | Ubuntu 20.04, Apache, PHP 7.3, DB `qb-golinucci` |
| firma      | firma.golinucci.it, scalapay.golinucci.it / `161.35.214.129` | PHP 7.4; anche app GolSign (non in vhost); cron meteo |

- **DB**: DigitalOcean Managed Database MySQL 8.0.45 (cluster condiviso) + MySQL locale su qb1/firma (localhost, ruolo da chiarire). Schema QB2: `qb2_schema.sql`.
- **SSH**: senza VPN, whitelist IP; utente dedicato (es. `federico` RO su staging/qb1/firma con sudo diagnostico limitato).
- **Credenziali riservate**: Tresorit Send (link mail + password WhatsApp).
- **Repo / codice**: snapshot inviato da Vittorio (2026-09-28) via Tresorit (`qb_code_20260928.tar.gz`, senza vendor/node_modules/.env). Repo Git storico su account personale ex-dev → da portare sotto controllo aziendale. Deploy/rollback non formalizzati.
- **PHP**: nessuna versione supportata a lungo termine (7.3 / 7.4 / 8.1); upgrade possibile senza cambio OS, da pianificare dopo lettura codice.

### Dettaglio QB2 produzione (email Vittorio, 28 set 2026)
- Droplet: Ubuntu 22.04, Apache 2.4, **2 vCPU / 1,9 GB RAM**
- Laravel 8.83 + PHP 8.1 + Inertia.js
- Code: `QUEUE_CONNECTION=database` (Supervisor); **niente Redis / RabbitMQ**
- Cache e sessioni: driver **file** locale
- Node.js v24 (nvm) + Playwright/Chromium sul server
- Pacchetti: Spatie Permission, Activitylog, Maatwebsite Excel, League Glide
- Integrazioni: Yousign; Lloyd’s DoPrint via **SOAP**
- Postmark (email) già noto da relazione FMP


## Integrazioni critiche (titolarità Golinucci)

1. **Pagamenti** (es. carta / PayPal) — fallimenti ripetuti lato cliente.
2. **Lloyd’s DoPrint** (`doprint.lloyds.com`) — generazione certificato PDF.
3. **YouSign** — firma digitale, avviata/gestita da QB.
4. Flusso tipico: QB → DoPrint (PDF) → YouSign (firma via QB). Errori intermittenti richiedono recovery manuale.

Documentazione integrazioni: da ricostruire dal codice. Contatti business/ops: Paolo / Margherita (ruolo qb1, firma, GolSign).

## Priorità prodotto — area MEDICI

### P0 — Stabilizzazione (incaricabile subito)

1. **Pagamenti online**: ridurre tentativi falliti / rendere il flusso idempotente e diagnostico.
2. **Pipeline DoPrint → YouSign**: affidabilità, retry, logging, recovery senza intervento manuale.
3. **Continuità rinnovi**: un solo carrello/anno per cliente; collegamento al carrello precedente; preservare decorrenza, scadenza, retroattività.

### P1 — Controllo operativo

- Parametri/template **email automatiche** modificabili da Golinucci (oggi bloccati).
- Aggiornamento **contact_ref** e **version_ref** da backoffice per cliente.
- Autocompletamento luoghi anagrafica (Google Maps o OpenStreetMap).
- Generazione polizze da QB **senza** certificato di pagamento.
- Nuovi prodotti: **Tutela legale – medici**, **Liberi professionisti medici**.
- Widget **WhatsApp** su QB e sito (392.3626336; valutare anche fisso).

### P2 — Evoluzione

- QB come hub **cross-sell**: salvare decorrenze di polizze gestite fuori QB e proporre rinnovo insieme alla polizza già in piattaforma.

## Cosa fare insieme (fase onboarding)

1. Chiave SSH pubblica + IP (statico o alternativa) → Vittorio whitelist / utente.
2. Accesso staging RO (e prod se necessario); verificare freschezza dati staging.
3. Recuperare o clonare codice; migrare repo sotto org Golinucci; mappare deploy.
4. Audit codice: pagamenti, DoPrint, YouSign, modello carrelli/rinnovi MEDICI.
5. Allineare con Paolo/Margherita su qb1, firma, GolSign, Scalapay.
6. Piano interventi P0 con observability (log, job, retry) e procedure di recovery.

## Contatti

- Infrastruttura: Vittorio Carraro — vittorio.carraro@bitmark.it
- Account integrazioni (PayPal, DoPrint, YouSign): Golinucci
- Dominio funzionale qb1/firma: Paolo / Margherita

## Relazione tecnica Full Marketing Power (27 set 2026)

Fonte: `Relazione-tecnica-QB-Golinucci.pdf` (inoltrata da Margherita). Analisi RO ago–set 2026. **Non serve rifare da zero l’audit**: usarla come baseline ed eseguire.

### Stack confermato
Laravel 8.83 + PHP 8.1, Vue/Inertia, Apache, MySQL managed, code Laravel, PayPal, DoPrint, Yousign API v3, Postmark. QB1 = PHP procedurale storico (ancora raggiunto). Firma/GolSign = piattaforma separata, **non** usata dal flusso Medici QB2 (solo asset grafici).

### Evidenze aggregate (stati in QB, non = perdite certe)
- 108 pagamenti locali non conclusi con ref esterno già presente
- 344 firme Medici 2026 ancora in stato init; 318 ferme >30 giorni
- 5/20 completamenti Yousign recenti → HTTP 419 (sessione/CSRF)
- 30 pratiche Medici storiche ferme intermedie DoPrint
- 25 carrelli Medici 2026 senza collegamento rinnovo pur avendo posizione precedente
- 9.197 record promemoria con credenziali temporanee in chiaro → **P0 sicurezza**

### Cause radice confermate
1. Nuovo carrello senza ripresa bozza → duplicati / perdita date
2. Pagamento dipendente dal callback browser (no riconciliazione server)
3. DoPrint/Yousign: recovery parziale; Yousign complete via sessione utente (419)
4. Repo su account personale; prod con modifiche non committate; staging vecchio; no deploy/rollback/test
5. Email hardcodate; contact_ref/version_ref incompleti in BO

### Priorità FMP (allineare offerta)
- **P0**: sicurezza (credenziali/log/permessi) + repo aziendale + staging + deploy/test
- **P1**: pagamenti + carrelli/rinnovi + DoPrint/Yousign + piano upgrade Laravel/PHP
- **P2**: email BO, anagrafica, autocomplete, messa in sicurezza/dismissione QB1 e firma
- **P3**: nuovi prodotti e cross-sell (dopo stabilizzazione)

Conclusione FMP: **non rifare la piattaforma**; stabilizzare prima di estendere.
