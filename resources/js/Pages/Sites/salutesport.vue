<template>
  <Head>
    <title>Pagamento online - {{ __("Products") }}</title>
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
    
  
  <!-- <p>Per procedere al pagamento, compilare i campi. <br></p><br> -->

  <p>Per aderire al “programma assicurativo + Sport&Salute Golinucci” compilare i seguenti campi e indicare nella pagina successiva i dati per il pagamento online.</p>

  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
      <div style="display:none">
    <input type="number" id="total_payable" name="total_payable" step="0.01" v-model="formData.total_payable" style="width:100%; " required ><br><br>
  </div>
  <div>
    <label for="name">Nome:</label><br>
    <input type="text" id="name" name="name" v-model="formData.name" style="width:100%" required><br><br>
    </div><div>
    <label for="surname">Cognome:</label><br>
    <input type="text" id="surname" name="surname" v-model="formData.surname" style="width:100%" required><br><br>
</div><div>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" v-model="formData.email" style="width:100%" required><br><br>
</div>
<div>
<label for="profession">Professione:</label><br>
    <input type="text" id="profession" name="profession" v-model="formData.profession" style="width:100%" required><br><br>
</div><div>
  <label for="dob">Data di nascita:</label><br>
    <input type="date" id="dob" name="dob" v-model="formData.dob" style="width:100%" required><br><br>
</div>
<div>
<label for="fiscalcode">Codice fiscale:</label><br>
    <input type="text" id="fiscalcode" name="fiscalcode" v-model="formData.fiscalcode" style="width:100%" required><br><br>
</div>
<div>
<label for="phone">Numero di telefono:</label><br>
    <input type="text" id="phone" name="phone" v-model="formData.phone" style="width:100%" required><br><br>
</div>

<div>
<label for="via">Indirizzo (Via):</label><br>
    <input type="text" id="via" name="via" v-model="formData.via" style="width:100%" required><br><br>
</div>
<div>
<label for="citta">Indirizzo (Città):</label><br>
    <input type="text" id="citta" name="citta" v-model="formData.citta" style="width:100%" required><br><br>
</div>
<div>
<label for="cap">Indirizzo (CAP):</label><br>
    <input type="text" id="cap" name="cap" v-model="formData.cap" style="width:100%" required><br><br>
</div>
<div>
<label for="provincia">Indirizzo (Provincia):</label><br>
    <input type="text" id="provincia" name="provincia" v-model="formData.provincia" style="width:100%" required><br><br>
</div>





<div style="display:none">
    <label for="causale">Causale:</label><br>
    <input type="text" id="causale" name="causale" v-model="formData.causale" style="width:100%" required><br><br>
</div>
<br>
<p>La decorrenza della copertura è dalle ore 24:00 della data di emissione polizza il primo giorno lavorativo disponibile dopo la conferma di pagamento.</p>
<br>
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
  props: {
    total_payable: Number,
    causal: String,
  },
  data() {
    return {
      product: 'Importo: ' + this.total_payable + "€", // Initialize `product` with a default value

      formData: this.$inertia.form({
        email: "",
        name: "",
        surname: "",
        profession: "",
        fiscalcode: "",
        phone: "",
        dob: "",
        via: "",
        citta: "",
        cap: "",
        provincia: "",
        total_payable: this.total_payable,
        causale: this.causal,
      }),
    };
  },
    methods: {

  submit(){
    this.formData.post(
        this.route("front.salutesportStep2", {
          name: this.formData.name,
          surname: this.formData.surname,
          email: this.formData.email,
          total_payable: this.formData.total_payable,
          causale: this.formData.causale,
          profession: this.formData.profession,
          fiscalcode: this.formData.fiscalcode,
          phone: this.formData.phone,
          dob: this.formData.dob,
          via: this.formData.via,
          citta: this.formData.citta,
          cap: this.formData.cap,
          provincia: this.formData.provincia,
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
