# Documentazione tecnica QB Golinucci

Documentazione ricostruita dal codice sorgente (snapshot 28/09/2026) e dallo schema DB `qb2_schema.sql`.

| Documento | Contenuto |
|-----------|-----------|
| [01-architettura.md](01-architettura.md) | Stack, ambienti, struttura repo |
| [02-flusso-medici.md](02-flusso-medici.md) | Percorso acquisto/rinnovo step by step |
| [03-integrazioni.md](03-integrazioni.md) | PayPal, DoPrint, Yousign, Postmark |
| [04-modello-dati.md](04-modello-dati.md) | Tabelle e relazioni principali |
| [05-job-email-sicurezza.md](05-job-email-sicurezza.md) | Cron, email, criticità sicurezza |
| [06-mappa-codice.md](06-mappa-codice.md) | Dove mettere le mani (file chiave) |
| [07-deploy.md](07-deploy.md) | Flusso main→staging→prod, releases/symlink, rollback, checklist |
| [08-email-templates.md](08-email-templates.md) | Template email editabili da BO (KAN-12) |
| [PROJECT_BRIEF.md](PROJECT_BRIEF.md) | Contesto commerciale / infra / priorità |
| [PROPOSTE_QB_Golinucci.md](PROPOSTE_QB_Golinucci.md) | Tre proposte per il cliente |
| [qb2_schema.sql](qb2_schema.sql) | Schema MySQL completo |

## Sintesi operativa (per chi interviene)

1. **Non rifare la piattaforma**: i flussi sono in `SiteBuyProductController` + `CartController` + servizi DoPrint/Yousign.
2. **Pagamenti**: cattura PayPal guidata dal browser (`store_step4_paypal_return`); un solo record `payments` per carrello; niente storico tentativi.
3. **Rinnovi**: tabella `renewals` (old→new `cart_ref`); se si apre un buy senza `cart_ref` si crea un **nuovo** carrello.
4. **DoPrint**: macchina a stati su `certificates.doprint_current_step` (handshake → validation → upload → transaction → download → completed).
5. **Yousign**: API v3; webhook hardening KAN-5 (CSRF except + completion service + `yousign:reconcile-stuck`).
6. **Sicurezza / pagamenti P0**: KAN-4 token rinnovi; KAN-5 Yousign; KAN-6 PayPal capture idempotente + webhook + reconcile.

## Cosa non è in questo snapshot

- File `.env` (segreti esclusi da Vittorio)
- `vendor/`, `node_modules/`
- Conferma runtime di staging vs produzione (solo codice)

## Knowledge base WeblyRAG

Dossier MCP agent_id: **`golinucci`** (titolo: Golinucci QB — Fractional Tech Lead). Contiene brief, proposte, docs tecnici 01–06, sintesi FMP, stakeholder/budget, infra Vittorio.
