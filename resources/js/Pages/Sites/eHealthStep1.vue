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

  <div style="
  margin: auto;
  width: 100%;
  padding: 10px;
  text-align:center">
 
  <img src="/img/visa.png" style="padding: 10px; display: inline-block;"/>
  <img src="/img/mastercard.png" style="padding: 10px; display: inline-block;"/>
  <img src="/img/amex.png" style="padding: 10px; display: inline-block;"/>
  <img src="/img/applepay.png" style="padding: 10px; display: inline-block;"/>
  <img src="/img/paypal.png" style="padding: 10px; display: inline-block;"/>



  </div>

  <section id="main-content" class="container mx-auto p-2" >
    
  <h1>Importo totale: 90€</h1><br>
  
  <p>Per procedere al pagamento, compilare i campi. <br> Inserire una email valida per ricevere correttamente il codice di accesso.</p>

  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
      <div>

    <label for="name">Nome:</label><br>
    <input type="text" id="name" name="name" v-model="formData.name" style="width:100%" required><br><br>
  </div>
<div>
    <label for="surname">Cognome:</label><br>
    <input type="text" id="surname" name="surname" v-model="formData.surname" style="width:100%" required><br><br>
  </div><div>
    <label for="codice_fiscale">Codice fiscale:</label><br>
    <input type="text" id="codice_fiscale" name="codice_fiscale" v-model="formData.codice_fiscale" style="width:100%" required><br><br>
  </div><div>
    <label for="citta_residenza">Città di residenza:</label><br>
    <input type="text" id="citta_residenza" name="citta_residenza" v-model="formData.citta_residenza" style="width:100%" required><br><br>
</div><div>
    <label for="indirizzo_residenza">Indirizzo di residenza:</label><br>
    <input type="text" id="indirizzo_residenza" name="indirizzo_residenza" v-model="formData.indirizzo_residenza" style="width:100%" required><br><br>
    </div><div>
    <label for="email">Email per la conferma:</label><br>
    <input type="email" id="email" name="email" v-model="formData.email" style="width:100%" required><br><br>
</div></div>
    <div>
      <buy-loading-button

        class="gol-btn my-8 bg-primary"
        type="submit"
        >{{ __("Next") }}</buy-loading-button
      >
    </div>
  </form>

  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import BuyProduct from "@/Shared/Front/BuyProduct";
import Layout from "@/Shared/Layouts/EmptyLayout.vue"
import BuyLoadingButton from "@/Shared/BuyLoadingButton";


export default {
  components: {
    HOne,
    BuyProduct,
    BuyLoadingButton,
  },
  layout: Layout,
  data() {
    return {
      product: 'eHealth', // Initialize `product` with a default value

      formData: this.$inertia.form({
        email: "",
        name: "",
        surname: "",
        codice_fiscale: "",
        indirizzo_residenza: "",
        citta_residenza: "",
      }),
    };
  },
    methods: {

  submit(){
    this.formData.post(
        this.route("front.ehealthStep2", {
          name: this.formData.name,
          surname: this.formData.surname,
          email: this.formData.email,
          codice_fiscale: this.formData.codice_fiscale,
          indirizzo_residenza: this.formData.indirizzo_residenza,
          citta_residenza: this.formData.citta_residenza,
        }),
        {
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
  }
    /*paymentGatewaySelected: function (evt) {
      this.form.payment_gateway_id_selected = evt.target.dataset.payment_id;
    },*/
  },
};
</script>
