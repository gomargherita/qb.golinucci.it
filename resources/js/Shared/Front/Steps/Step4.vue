<template>
  <Head :title="__('Metodo di pagamento')" />
  <HTwo :title="__('Metodo di pagamento')" />
  <div class="mt-8">
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
              <div class="text-center" v-if="payment_gateway.name == 'Paypal'">
                <!-- <p>Paga con PayPal</p> -->
                <div id="paypal-button-container" class="paypal-button-container"></div>

                <hr>
                oppure

                <!-- PayPal Logo --><table border="0" cellpadding="0" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark"></a></td></tr></table><!-- PayPal Logo -->

                <div id="checkout-form">
                  <div id="card-name-field-container"></div>
                  <div id="card-number-field-container"></div>
                  <div id="card-expiry-field-container"></div>
                  <div id="card-cvv-field-container"></div>
                  <button id="card-field-submit-button" type="button" class="gol-btn my-8 bg-primary">
                    Paga ora
                  </button>
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
  },
  data() {
    return {
      form: this.$inertia.form({
        payment_gateway_id_selected: null,
      }),
    };
  },

  mounted(){
    let paypalSDK = document.createElement('script')
      paypalSDK.setAttribute('src', 'https://www.paypal.com/sdk/js?components=buttons,card-fields&currency=EUR&locale=it_IT&client-id='+this.paypal_client_id)
      let product = this.product;
      let cart = this.cart;
      
      document.head.appendChild(paypalSDK)
      //console.log(this.paypal_client_id);
      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
      let payment_id;
      //window.onload =  () => {

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
              createOrder: async (data) => {
                const response = await fetch("/products/"+product.id+"/store_step4/"+cart.cart_ref+"/paypal", {
                  method: "POST",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                const rawOrderData = await response.json();

                if(rawOrderData.reload == "reload"){
                  location.reload();
                }
                const orderData = rawOrderData.order;

                payment_id = rawOrderData.payment_id;



                if (orderData.id) {
                      return orderData.id;
                    } else {
                      //const errorDetail = orderData?.details?.[0];
                      //const errorMessage = errorDetail
                      //  ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                      //  : JSON.stringify(orderData);
                      document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                      //throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },

              // Finalize the transaction after payer approval
              onApprove: async (data) => {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ String(payment_id), {
                  method: "GET",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                
                //console.log("prova");
                //console.log(orderData);
                const orderData = await response.json();
                //console.log("prova");
                if(orderData.info_paypal == "SUCCESS"){
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";
                  location.reload();
                }
                else{
                  document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
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
                const response = await fetch("/products/"+product.id+"/store_step4/"+cart.cart_ref+"/paypal", {
                  method: "POST",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                const rawOrderData = await response.json();
                if(rawOrderData.reload == "reload"){
                  location.reload();
                }
                const orderData = rawOrderData.order;

                payment_id = rawOrderData.payment_id;



                if (orderData.id) {
                      return orderData.id;
                    } else {
                      //const errorDetail = orderData?.details?.[0];
                      //const errorMessage = errorDetail
                      //  ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                      //  : JSON.stringify(orderData);
                      document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";

                      //throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },
              onApprove: async (data) => {
                

                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ String(payment_id), {
                  method: "GET",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                
                const orderData = await response.json();
                //console.log("prova");
                if(orderData.info_paypal == "SUCCESS"){
                  
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";
                  location.reload();
                }
                else{
                  document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                }
              },
              onError: function (error) {
                document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                document.getElementById("bonifico").style.display = "inline";

                // Do something with the error from the SDK
                document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";

                //resultMessage("Ci scusiamo, la transazione non può essere completata.<br><br>${error}",);
              },
            });
            
            // Render each field after checking for eligibility
            if (cardField.isEligible()) {
              const nameField = cardField.NameField();
              nameField.render("#card-name-field-container");

              const numberField = cardField.NumberField();
              numberField.render("#card-number-field-container");

              const cvvField = cardField.CVVField();
              cvvField.render("#card-cvv-field-container");

              const expiryField = cardField.ExpiryField();
              expiryField.render("#card-expiry-field-container");

              // Add click listener to submit button and call the submit function on the CardField component
              document
                .getElementById("card-field-submit-button")
                .addEventListener("click", () => {
                  cardField.submit().then(() => {
                    // submit successful
                  });
                });
            }
          }


        //}




    },
  methods: {
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
