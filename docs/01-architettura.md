# 01 — Architettura e stack

## Applicazione

| Elemento | Valore osservato nel codice |
|----------|-----------------------------|
| Nome | QB (`qb.golinucci.it`) |
| Framework | Laravel **8.83** (`composer.json`: `laravel/framework ^8.75`) |
| PHP | ^7.3 / >=8.0 dichiarato; produzione su **8.1** (infra Vittorio) |
| Frontend | **Vue 3** + **Inertia.js** + Tailwind + Laravel Mix |
| Auth / ruoli | Laravel Breeze + Spatie Permission |
| Altri pacchetti | Sanctum, Media Library, Activitylog, Maatwebsite Excel, League Glide, DomPDF, Propaganistas Phone, Ziggy |
| Pagamenti | `srmklive/paypal` ~3.0 |
| Email | `wildbit/swiftmailer-postmark` (Postmark) |

## Infrastruttura (da Vittorio, allineata al codice)

- Droplet DigitalOcean: Ubuntu 22.04, Apache 2.4, 2 vCPU / ~1,9 GB RAM
- DB: MySQL 8 Managed (separato dal droplet)
- Code Laravel: `QUEUE_CONNECTION=database` + Supervisor (niente Redis/RabbitMQ)
- Cache e sessioni: driver **file**
- Sul server: Node.js v24 (nvm) + Playwright/Chromium (uso da approfondire; non emerge un job chiaro nel Kernel)

## Layout repository (radice progetto)

```
app/
  Console/Commands/     # mail:renew, importatori, ecc.
  Http/Controllers/     # Site*, Cart, Payment, Yousign, admin CRUD
  Models/               # Cart, Payment, Certificate, Signature, …
  Notifications/        # email (testi hardcoded)
  Services/             # DoPrint SOAP, Yousign, generazione PDF
config/                 # paypal.php, yousign.php, doprint.php, …
database/migrations/
resources/js/           # Pages Inertia (front + admin)
routes/web.php          # quasi tutte le route (poca/nulla API dedicata)
storage/                # documenti privati (certificate PDF) via disk `private`
```

## Due superfici utente

1. **Front (cliente medico)**  
   Route sotto `SiteController` / `SiteBuyProductController`  
   Acquisto: `/products/{product}/buy/{cart?}`  
   Rinnovo: `/products/{product}/renew/{cart}`  
   Pagamento libero: `/pagaonline`, `/paga-online/...`

2. **Admin (`/admin/...`)**  
   Middleware `auth` + `check_role`  
   CRUD prodotti, utenti, anagrafiche, carrelli per `product_type`, bordereaux, contatori DoPrint/prodotto, importo libero admin  
   Azioni operative su carrello: genera Lloyds, regen certificato, invio email, update decorrenza, step 6, ecc.

## Tipi prodotto (logica hardcoded)

Nel codice ricorrono ID fissi (non solo configurazione DB):

| Uso nel codice | Significato tipico |
|----------------|--------------------|
| `product_type_id == 1` | Flusso Medici “lungo” (step fino a 7, DoPrint + Yousign) |
| `product_id == 1` | Variante Medici (XML DoPrint `lloyds_new_base.xml`) |
| `product_id == 5` | G Medici (`lloyds_gmedici.xml`); contatore pagamento riusa prodotto 1 |
| `product_id` 2/3/4 | Flusso più corto (step fino a 5), spesso “bancari” / altri |
| `product_type_id == 2` | Ramo bancari (date fine anno hardcoded nel capture PayPal) |

**Implicazione:** aggiungere un prodotto non è solo insert in `products`: tocca controller, XML DoPrint, PDF, email, contatori.

## Ambienti correlati (fuori da questo repo)

- **QB1**: gestionale storico PHP procedurale (non questo codice)
- **firma / GolSign**: piattaforma firma separata; il flusso Medici di QB2 usa **Yousign diretto**, non GolSign

## Deployment e qualità

- Nessuna pipeline CI evidente nello snapshot
- Test automatici sostanzialmente assenti sui flussi critici
- Remote Git storico su account personale (da spostare in azienda)
- Staging segnalato come dati vecchi / differenze non versionate (relazione FMP)
