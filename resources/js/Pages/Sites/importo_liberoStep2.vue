<template>
  <Head>
    <title>Importo Libero - {{ __("Products") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  
  <HOne :title="product" />

  <section id="main-content" class="container mx-auto p-2" >
    
  <h1>Importo totale: {{ this.total_payable }}€</h1><br>
  
  
  <div style="
  margin: auto;
  width: 50%;
  padding: 10px;">
  <div id="paypal-button-container" class="paypal-button-container"></div><br>
  <div  id="applepay-container"></div>
  </div>
  
    <hr>
    <p style="text-align: center;">oppure</p>
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
    id: String,
    total_payable : Number,
    paypal_client_id: String,
  },
  data() {
    return {
      product: 'Importo libero' // Initialize `product` with a default value
    };
  },
  mounted(){
    let applePaySDK = document.createElement('script');
    applePaySDK.setAttribute('src', 'https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js');

    let paypalSDK = document.createElement('script')
      paypalSDK.setAttribute('src', 'https://www.paypal.com/sdk/js?components=buttons,card-fields,applepay&currency=EUR&locale=it_IT&client-id='+this.paypal_client_id)
      let id = this.id;
      let total_payable = this.total_payable;
      
      document.head.appendChild(paypalSDK);
      document.head.appendChild(applePaySDK);
      
      let paypalLoaded = false;
      let applepayLoaded = false;

      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
      let payment_id;
      //window.onload =  () => {



        function checkIfReady(){
          if (applepayLoaded && paypalLoaded){
            setup_paypal();
            setup_applepay();
          }
        }
        paypalSDK.onload = function() {
          paypalLoaded = true;
          checkIfReady()
        }
        applePaySDK.onload = function() {
          applepayLoaded = true;
          checkIfReady()
        }

        function setup_paypal (){
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
                const response = await fetch("/importo_libero/pay/"+id, {
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
                      console.log("1");
                      document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                      //throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },

              // Finalize the transaction after payer approval
              onApprove: async (data) => {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/importo_libero/check_payment/"+id, {
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
                console.log("2");

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

                document.getElementById("pagamento-in-corso").innerHTML = "Tentativo di pagamento in corso... attendere prego";
                const response = await fetch("/importo_libero/pay/"+id, {
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
                      console.log("3");

                      document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";

                      //throw new Error(errorMessage);
                    }
                        
                //console.log(response.json());
                  //.then((order) => console.log(order));
              },
              onApprove: async (data) => {


                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("/importo_libero/check_payment/"+id, {
                  method: "GET",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                
                const orderData = await response.json();
                console.log(orderData);
                //console.log("prova");
                if(orderData.info_paypal == "SUCCESS"){
                  
                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento riuscito.";
                  location.reload();
                }
                else{
                  document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";
                console.log("4");

                  document.getElementById("pagamento-in-corso").innerHTML = "Pagamento NON riuscito. Riprovare.";
                }
              },
              onError: function (error) {
                document.getElementById("card-field-submit-button").style.display = "inline";
                document.getElementById("paypal-button-container").style.display = "inline";

                // Do something with the error from the SDK
                console.log("5");

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



          function setup_applepay() {
          //APPLE PAY -------------------------------------------------------------

          if (!window.ApplePaySession) {
            console.error('This device does not support Apple Pay');
          }
          else if (!window.ApplePaySession.canMakePayments()) {
            console.error('This device is not capable of making Apple Pay payments');
          }
          const applepay = paypal.Applepay();
          applepay.config()
          .then(applepayConfig => {
            if (applepayConfig.isEligible) {
              document.getElementById("applepay-container").innerHTML = '<apple-pay-button style="width: 100%; --apple-pay-button-height: 45px;" id="btn-appl" buttonstyle="black" type="buy" locale="it">';
              document.getElementById("btn-appl").addEventListener("click", () => {

                const paymentRequest = {
                countryCode: applepayConfig.countryCode,
                merchantCapabilities: applepayConfig.merchantCapabilities,
                supportedNetworks: applepayConfig.supportedNetworks,
                currencyCode: "EUR",
                requiredShippingContactFields: [],
                requiredBillingContactFields: ["postalAddress"],
                total: {
                  label: "Golinucci SRL",
                  type: "final",
                  amount: total_payable,
                }
                }

                const session = new ApplePaySession(4, paymentRequest);
                //console.log(session);
                //console.log("sessione printata");

                session.onvalidatemerchant = (event) => {
                applepay.validateMerchant({
                  validationUrl: event.validationURL,
                  displayName: "Golinucci SRL"
                })
                .then(validateResult => {
                  session.completeMerchantValidation(validateResult.merchantSession);
                })
                .catch(validateError => {
                  console.error(validateError);
                  session.abort();
                });
                };

                session.onpaymentauthorized = (event) => {
                //console.log('Your billing address is:', event.payment.billingContact);
                //console.log('Your shipping address is:', event.payment.shippingContact);
                fetch("/importo_libero/pay/"+id, {
                  method: "POST",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                .then(res => res.json())
                .then((createOrderData) => {
                  if(createOrderData.reload == "reload"){
                  location.reload();
                  }
                  console.log(createOrderData);
                  var orderId = createOrderData.order.id;
                  applepay.confirmOrder({
                    orderId: orderId,
                    token: event.payment.token,
                    billingContact: event.payment.billingContact
                  })
                  .then(confirmResult => {
                    session.completePayment(ApplePaySession.STATUS_SUCCESS);
                    fetch("/importo_libero/check_payment/"+id, {
                  method: "GET",
                  // Use the "body" parameter to optionally pass additional order information
                  // such as product ID or amount
                  headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                })
                    .then(res => res.json())
                    .then(captureResult => {
                      console.log(captureResult);
                      location.reload();
                    })
                    .catch(captureError => console.error(captureError));
                  })
                  .catch(confirmError => {
                    if (confirmError) {
                      console.error('Error confirming order with applepay token');
                      console.error(confirmError);
                      session.completePayment(ApplePaySession.STATUS_FAILURE);
                    }
                  });
                });
              };


              session.begin();
              });
              }
          })
          .catch(applepayConfigError => {
            console.log(applepayConfigError);
            console.error('Error while fetching Apple Pay configuration.');
          });
          console.log("preparato il listener");
          //handleApplePayClick();

        //APPLE PAY -------------------------------------------------------------
        }



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
