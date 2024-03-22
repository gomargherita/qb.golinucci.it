<template>
  <Head>
    <title>eHealth - {{ __("Products") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  <HOne :title="product" />

  <section id="main-content" class="container mx-auto p-2" >
    <div style="
  margin: auto;
  width: 50%;
  padding: 10px;">
  <h1>Importo totale: 90€</h1><br>
    <div id="paypal-button-container" class="paypal-button-container"></div>
  
    <hr>
    <p style="text-align: center;">oppure</p>
    <!-- PayPal Logo --><table border="0" cellpadding="0" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark"></a></td></tr></table><!-- PayPal Logo -->
  </div>
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
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import BuyProduct from "@/Shared/Front/BuyProduct";
import Layout from "@/Shared/Layouts/EmptyLayout.vue"

export default {
  components: {
    HOne,
    BuyProduct,
  },
  layout: Layout,
  props: {

    paypal_client_id: String,
  },
  data() {
    return {
      product: 'eHealth' // Initialize `product` with a default value
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
                const response = await fetch("/ehealth/pay", {
                  method: "POST",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                const rawOrderData = await response.json();
                const orderData = rawOrderData.order;

                payment_id = rawOrderData.payment_id;



                if (orderData.id) {
                      return orderData.id;
                    } else {
                      const errorDetail = orderData?.details?.[0];
                      const errorMessage = errorDetail
                        ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                        : JSON.stringify(orderData);
                
                      throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },

              // Finalize the transaction after payer approval
              onApprove: async (data) => {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ payment_id +"/r", {
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
                //console.log(orderData);
                location.reload();
              },
              onError: function (error) {
                // Do something with the error from the SDK
                resultMessage("Ci scusiamo, la transazione non può essere completata.<br><br>${error}",);
              },
            })
            .render("#paypal-button-container");

            
            // Create the Card Fields Component and define callbacks
            const cardField = paypal.CardFields({
              createOrder: async (data) => {
                document.getElementById("card-field-submit-button").style.display = "none";
                document.getElementById("pagamento-in-corso").innerHTML = "Tentativo di pagamento in corso... attendere prego";
                const response = await fetch("/ehealth/pay", {
                  method: "POST",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                const rawOrderData = await response.json();
                const orderData = rawOrderData.order;

                payment_id = rawOrderData.payment_id;



                if (orderData.id) {
                      return orderData.id;
                    } else {
                      const errorDetail = orderData?.details?.[0];
                      const errorMessage = errorDetail
                        ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                        : JSON.stringify(orderData);
                
                      throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },
              onApprove: async (data) => {
                document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";

                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/products/"+product.id+"/store_step4_paypal/"+cart.cart_ref+"/"+ payment_id +"/r", {
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
                //console.log(orderData);
                location.reload();
              },
              onError: function (error) {
                document.getElementById("card-field-submit-button").style.display = "inline";

                // Do something with the error from the SDK
                document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";

                resultMessage("Ci scusiamo, la transazione non può essere completata.<br><br>${error}",);
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
  },
};
</script>
