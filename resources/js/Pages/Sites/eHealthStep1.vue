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
    
  <h1>Importo totale: 90€</h1><br>
  
  <p>Per procedere al pagamento, compilare i campi. <br> Inserire una email valida per ricevere correttamente il codice di accesso.</p>

  <form @submit.prevent="submit">
    <label for="name">Nome:</label><br>
    <input type="text" id="name" name="name" v-model="formData.name" style="width:100%" required><br>
    
    <label for="surname">Cognome:</label><br>
    <input type="text" id="surname" name="surname" v-model="formData.surname" style="width:100%" required><br>
    
    <label for="email">Email per la conferma:</label><br>
    <input type="email" id="email" name="email" v-model="formData.email" style="width:100%" required><br><br>

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
