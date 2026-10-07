# 02 — Flusso Medici (acquisto e rinnovo)

Il carrello (`carts`) è l’oggetto centrale. Lo step corrente è `product_step_id` (FK su `product_steps`).

## Step tipici (product_type Medici = 1)

| Step | Cosa fa l’utente | Dove nel codice |
|------|------------------|-----------------|
| 1 | Anagrafica / dati cliente | `store_step1` |
| 2 | Offerta / condizioni | `store_step2` |
| 3 | Dichiarazioni | `store_step3` |
| 4 | Pagamento | `store_step4` + return PayPal |
| 5 | Generazione documento (DoPrint) | `store_step5` / admin `generateLloyds` |
| 6 | Firma Yousign | step6 + `YousignApiService` |
| 7 | Completato | avanzamento dopo webhook/firma |

Prodotti 2/3/4 usano uno switch più corto (fino allo step 5).

## Avvio acquisto (KAN-7)

File: `SiteBuyProductController::buy` + `RenewalCartService`

- Con `cart_ref`: riprende quel carrello; se è un rinnovo, **ripristina** `renewal_date` / `retroactivity` se assenti.
- Senza `cart_ref`:
  1. riprende bozza aperta (stesso user+product, step &lt; 7)
  2. altrimenti, se c’è certificato in finestra rinnovo → redirect a `renew`
  3. altrimenti crea primo acquisto

Non si creano più carrelli orfani a ogni click su “compra”.

## Rinnovo (KAN-7)

Route front: `GET /products/{product}/renew/{cart:cart_ref}` → `SiteBuyProductController::renew`  
Admin: `CartController::renew`  
Servizio: `App\Services\Renewals\RenewalCartService`

1. Verifica ownership e finestra (−30g rispetto a `end_validity_date`)
2. Se esiste già `renewals.old_cart_ref` → riprende il `new_cart_ref`
3. Se esiste bozza rinnovo aperta per user+product → riprende quella (no fork)
4. Altrimenti crea carrello con date copiate dal certificato precedente e link `renewals`

```text
old_cart_ref → new_cart_ref
renewal_date ← end_validity_date precedente
retroactivity ← retroactivity precedente
```

Campi data rilevanti:

- `carts.renewal_date` / `carts.retroactivity`
- `certificates.start_validity_date` / `end_validity_date` / `retroactivity`

## Pagamento (step 4)

1. `store_step4` crea o riusa `payments` con `firstOrCreate(['cart_id' => ...])`  
   → **un solo payment per carrello**; i tentativi successivi sovrascrivono gateway/ref, non creano storico.
2. Gateway `paypal` (id 1) o bonifico/BBM (id 2).
3. PayPal: `createOrder` intent `CAPTURE`, salva `transaction_ref` = order id.
4. Il frontend (JS PayPal) completa e poi chiama in GET:

   `/products/{product}/store_step4_paypal/{cart}/{payment_id}`

5. `store_step4_paypal_return` fa `capturePaymentOrder` e, se `COMPLETED`:
   - `paid = 1`, assegna `payment_no` da `product_counters`
   - genera PDF certificato di pagamento (Medici)
   - porta il carrello allo **step 5**

**Fragilità:** se il browser non chiama il return (chiusura pagina, errore rete, sessione), QB resta con ordine PayPal creato e `paid = 0`. Non c’è webhook PayPal evidente nel codice per riconciliare.

Cancel: `store_step4_paypal_cancel` fa solo redirect allo step pagamento (email di cancel commentata).

## Dopo il pagamento: documento e firma

- Step 5 / admin: pipeline DoPrint a stati (vedi [03-integrazioni.md](03-integrazioni.md))
- Step 6: avvio procedura Yousign sul PDF `certificate.pdf`
- Completamento: webhook / callback aggiornano `signatures.status` e portano allo step 7; email finali con allegati

Admin può forzare passaggi (`generatelloyds`, `updatestep6`, `regen_certificate`, ecc.).

## Altri flussi collegati (non “carrello Medici classico”)

| Percorso | Controller | Note |
|----------|------------|------|
| Importo libero `/pagaonline` | `SiteController` + `ImportoLiberoController` | Pagamento con causale; stessa dipendenza PayPal/browser; validazione causale vs DB già causa di 500 in casi reali |
| eHealth / Benefit / SaluteSport | `SiteController` | Prodotti/campagne parallele |
| Bordereaux | `BordereauxController` | Export admin |

## Diagramma logico

```text
[Login] → buy(cart?) ──no cart──► nuovo Cart step1
              │
              └──cart──► step 1..3 (dati)
                            ↓
                         step 4 PayPal createOrder
                            ↓
                    (browser) capture + return URL
                            ↓
                         step 5 DoPrint (stati)
                            ↓
                         step 6 Yousign
                            ↓
                         step 7 + email
```

Rinnovo corretto:

```text
Cart anno N (completo) → renew → nuovo Cart + riga renewals → stessi step
```
