<template>
  <Head :title="__('Metodo di pagamento')" />
  <HTwo :title="__('Metodo di pagamento')" />
  <div class="mt-8">
    <p class="text-center text-lg mb-6">
      Importo da pagare:
      <strong>{{ $filters.formatPrice(payableAmount) }}</strong>
    </p>
    <p v-if="product.electronic_capped" class="text-center font-bold mb-6">
      Per importi superiori a {{ Number(product.electronic_max_eur).toLocaleString('it-IT') }} €
      il pagamento elettronico non è disponibile. Procedi con bonifico bancario.
    </p>
    <form>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="(payment_gateway, index) in product.payment_gateways"
          class="odd:bg-white even:bg-slate-100"
        >
          <div class="p-4">
            <!--<icon
              v-if="payment_gateway.name == 'Paypal'"
              name="paypal"
              class="block mx-auto w-full max-w-xs fill-white"
              height="50"
              />-->
              <div class="text-center" v-if="payment_gateway.name == 'Stripe' && stripe_enabled">
                <p>Paga online (carta, PayPal, Apple Pay)</p>
                <button @click="stripePay" type="button" class="gol-btn my-8 bg-primary">
                  Paga ora
                </button>
                <p id="pagamento-stripe" style="color:red; font-weight:bold; margin:0; padding:0"></p>
              </div>
              <div class="text-center" v-if="payment_gateway.name == 'Paypal' && !stripe_enabled">
                <!-- <p>Paga con PayPal</p> -->
                <div id="paypal-button-container" class="paypal-button-container"></div>

                <div id="paypal-card-section" style="display:none">
                <hr>
                <p>oppure paga con carta</p>
                <div id="checkout-form">
                  <div id="card-name-field-container"></div>
                  <div id="card-number-field-container"></div>
                  <div id="card-expiry-field-container"></div>
                  <div id="card-cvv-field-container"></div>
                  <button id="card-field-submit-button" type="button" class="gol-btn my-8 bg-primary">
                    Paga ora
                  </button>
                </div>
                </div>
                <p id="pagamento-in-corso" style="color:red; font-weight:bold; margin:0; padding:0"></p>












              </div>
            <div class="text-center" v-if="payment_gateway.name == 'BBM'">
              Oppure procedi con Bonifico Bancario
              <button @click="bbmPayment" id="bonifico" type="button" class="gol-btn my-8 bg-primary">
                    Paga con bonifico bancario
                  </button>
            </div>
          </div>
          <div class="p-4 text-center">
            <input
              name="payment_gateway_id_selected"
              :data-payment_id="payment_gateway.id"
              class="mr-1"
              type="hidden"
            />
          </div>
        </div>
      </div>
      <div v-if="!product.payment">
        <div class="my-4" v-html="product.payments_description"></div>
        <BreezeValidationErrors class="mb-4" />
        <!--<buy-loading-button
          :loading="form.processing"
          :disabled="form.processing"
          :class="{ 'opacity-25': form.processing }"
          class="gol-btn my-8 bg-primary"
          type="submit"
          >{{ __("Next") }}</buy-loading-button
        >-->
      </div>
      <div v-if="product.payment">
        <div class="mt-8 mb-24 text-center text-lg">
          <p class="bold">
            Hai selezionato il metodo di pagamento con Bonifico Bancario.
          </p>

          <p>Ecco i dati per effettuare il bonifico:</p>

          <ul class="list-none list-inside font-bold text-primary my-8">
            <li>
              Importo:
              <span
                v-text="$filters.formatPrice(parseFloat(product.payment.total_payable))"
              ></span>
            </li>
            <li v-if="product.id == 1 ">Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT69 D030 6923 9031 0000 0007 600 presso Banca Intesa San Paolo</li>
            <li v-if="product.id == 2 ">Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT31 Z062 3023 9000 0003 0575 079 presso Crédit agricole Cariparma</li>

            <li v-if="product.id == 3 ">Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT63 J032 7323 9000 0020 0102 910 presso BCC Credito Cooperativo romagnolo</li>
            <li v-if="product.id == 4 ">Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT63 J032 7323 9000 0020 0102 910 presso BCC Credito Cooperativo romagnolo</li>

          </ul>
          <p>
            Riceverai una email al tuo indirizzo email con un riassunto di queste stesse
            informazioni.
          </p>
          <p>
            <p >
              <b>Nota importante:</b> la <b>generazione della polizza</b> verrà presa in carico nel <b>giorno lavorativo successivo</b> a quello di data valuta del pagamento effettuato con bonifico bancario.<br />
              Riceverai successivamente una email da parte nostra con un <b>link</b> per effettuare la <b>sottoscrizione</b> della polizza da remoto (invitiamo a controllare anche nella Posta Indesiderata).</p
            >
          </p>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

export default {
  components: {
    BuyLoadingButton,
    HTwo,
    BreezeValidationErrors,
  },
  props: {
    cart: Object,
    product: Object,
    paypal_client_id: String,
    stripe_enabled: Boolean,
  },
  data() {
    return {
      form: this.$inertia.form({
        payment_gateway_id_selected: null,
      }),
    };
  },
  computed: {
    payableAmount() {
      const raw =
        (this.product && this.product.payment && this.product.payment.total_payable) ||
        (this.cart && this.cart.total_payable) ||
        0;
      return parseFloat(raw) || 0;
    },
  },

  mounted(){
    if (this.stripe_enabled || this.product.electronic_capped) {
      return;
    }
    if (!this.paypal_client_id) {
      const el = document.getElementById("pagamento-in-corso");
      if (el) {
        el.innerHTML =
          "Pagamento PayPal non disponibile: configurazione mancante sul server (client ID). Usa bonifico o contatta l'assistenza.";
      }
      return;
    }

    let paypalSDK = document.createElement('script')
      paypalSDK.setAttribute('src', 'https://www.paypal.com/sdk/js?components=buttons,card-fields&currency=EUR&locale=it_IT&client-id='+this.paypal_client_id)
      let product = this.product;
      let cart = this.cart;
      
      document.head.appendChild(paypalSDK)
      //console.log(this.paypal_client_id);
      // KAN-39: after OTP the Inertia CSRF meta can be stale; send cookie + header.
      const csrfHeaders = function () {
        const headers = {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        };
        const meta = document.head.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute('content')) {
          headers['X-CSRF-TOKEN'] = meta.getAttribute('content');
        }
        const xsrf = document.cookie.split('; ').find(function (row) {
          return row.indexOf('XSRF-TOKEN=') === 0;
        });
        if (xsrf) {
          headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrf.split('=').slice(1).join('='));
        }
        return headers;
      };
      const createPaypalOrder = async function () {
        const response = await fetch("/products/"+product.id+"/store_step4/"+cart.cart_ref+"/paypal", {
          method: "POST",
          redirect: "error",
          credentials: "same-origin",
          headers: csrfHeaders(),
        });
        let rawOrderData = {};
        try {
          rawOrderData = await response.json();
        } catch (e) {
          const el = document.getElementById("pagamento-in-corso");
          if (el) {
            el.innerHTML = "Pagamento non avviato. Ricarica la pagina e riprova.";
          }
          throw new Error("create_order_not_json_" + response.status);
        }
        if (!response.ok) {
          const el = document.getElementById("pagamento-in-corso");
          if (el) {
            el.innerHTML = rawOrderData.message || "Sessione scaduta. Ricarica la pagina e riprova.";
          }
          throw new Error("create_order_http_" + response.status);
        }
        if (rawOrderData.reload == "reload") {
          location.reload();
          return;
        }
        payment_id = rawOrderData.payment_id;
        if (rawOrderData.order && rawOrderData.order.id) {
          return rawOrderData.order.id;
        }
        const el = document.getElementById("pagamento-in-corso");
        if (el) {
          el.innerHTML = "Pagamento NON riuscito. Riprovare.";
        }
        throw new Error("create_order_missing_id");
      };
      let payment_id;

        paypalSDK.onload = function() {
          //console.log(product);
          // Render the button component
          window.paypal
            .Buttons({
              style: { 
            label: 'pay', 
            tagline: 'true',
            layout: 'horizontal'
              } ,
              // Sets up the transaction when a payment button is clicked
              createOrder: async () => {
                return createPaypalOrder();
              },

              // Finalize the transaction after payer approval
              onApprove: async (data) => {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const payId = payment_id ? String(payment_id) : "0";
                const orderQuery = data.orderID ? ("?order_id=" + encodeURIComponent(data.orderID)) : "";
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ payId + orderQuery, {
                  method: "GET",
                  headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': csrfToken,
                },
                })

                let orderData = {};
                try {
                  orderData = await response.json();
                } catch (e) {
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento inviato. Aggiornamento in corso…";
                  location.reload();
                  return;
                }
                if(orderData.info_paypal == "SUCCESS"){
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";
                  location.reload();
                }
                else{
                  document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                  location.reload();
                }
              },
              onError: function (error) {
                // Do something with the error from the SDK
                //resultMessage("Ci scusiamo, la transazione non può essere completata.<br><br>${error}",);
              },
            })
            .render("#paypal-button-container");

            
            // Create the Card Fields Component and define callbacks
            const cardField = paypal.CardFields({
              createOrder: async (data) => {
                document.getElementById("card-field-submit-button").style.display = "none";
                document.getElementById("paypal-button-container").style.display = "none";
                document.getElementById("bonifico").style.display = "none";

                document.getElementById("pagamento-in-corso").innerHTML = "Tentativo di pagamento in corso... attendere prego";
                return createPaypalOrder();
              },
              onApprove: async (data) => {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const payId = payment_id ? String(payment_id) : "0";
                const orderQuery = data.orderID ? ("?order_id=" + encodeURIComponent(data.orderID)) : "";
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ payId + orderQuery, {
                  method: "GET",
                  headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': csrfToken,
                },
                })

                let orderData = {};
                try {
                  orderData = await response.json();
                } catch (e) {
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento inviato. Aggiornamento in corso…";
                  location.reload();
                  return;
                }
                if(orderData.info_paypal == "SUCCESS"){
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";
                  location.reload();
                }
                else{
                  document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                  location.reload();
                }
              },
              onError: function (error) {
                document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";
                document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                location.reload();
              },
            });
            
            // Render each field after checking for eligibility
            if (cardField.isEligible()) {
              document.getElementById("paypal-card-section").style.display = "block";
              const nameField = cardField.NameField();
              nameField.render("#card-name-field-container");

              const numberField = cardField.NumberField();
              numberField.render("#card-number-field-container");

              const cvvField = cardField.CVVField();
              cvvField.render("#card-cvv-field-container");

              const expiryField = cardField.ExpiryField();
              expiryField.render("#card-expiry-field-container");

              document
                .getElementById("card-field-submit-button")
                .addEventListener("click", () => {
                  cardField.submit().then(() => {
                    // submit successful
                  }).catch(() => {
                    document.getElementById("pagamento-in-corso").innerHTML =
                      "Controlla i dati della carta e riprova, oppure usa il pulsante PayPal sopra.";
                  });
                });
            }
          }


        //}




    },
  methods: {
    async stripePay() {
      const el = document.getElementById("pagamento-stripe");
      if (el) {
        el.innerHTML = "Reindirizzamento a Stripe…";
      }
      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
      const response = await fetch(
        "/products/" + this.product.id + "/checkout-stripe/" + this.cart.cart_ref,
        {
          method: "POST",
          credentials: "same-origin",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken,
          },
        }
      );
      let data = {};
      try {
        data = await response.json();
      } catch (e) {
        if (el) {
          el.innerHTML = "Pagamento non disponibile (risposta non valida). Riprovare.";
        }
        return;
      }
      if (data.reload == "reload") {
        location.reload();
        return;
      }
      if (data.url) {
        window.location = data.url;
        return;
      }
      if (el) {
        el.innerHTML =
          "Pagamento non disponibile. " + (data.message || "Riprovare o usa il bonifico.");
      }
    },
    resultMessage(message) {
    const container = document.querySelector("#result-message");
    container.innerHTML = message;
  },
    /*paymentGatewaySelected: function (evt) {
      this.form.payment_gateway_id_selected = evt.target.dataset.payment_id;
    },*/
    bbmPayment(gateway = null) {
      this.form.post(
        this.route("front.product.store_step4", {
          product: this.product.id,
          cart: this.cart.cart_ref,
          gateway: 'bbm',
        }),
        {
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
    },
  },
};
</script>
