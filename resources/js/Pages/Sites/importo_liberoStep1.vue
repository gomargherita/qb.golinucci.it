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
    
  
  <p>Per procedere al pagamento, compilare i campi. <br></p>

  <form @submit.prevent="submit">
    <label for="total_payable">Importo da pagare (EURO):</label><br>
    <input type="number" id="total_payable" name="total_payable" step="0.01" v-model="formData.total_payable" style="width:100%" required><br><br>

    <label for="name">Nome:</label><br>
    <input type="text" id="name" name="name" v-model="formData.name" style="width:100%" required><br><br>
    
    <label for="surname">Cognome:</label><br>
    <input type="text" id="surname" name="surname" v-model="formData.surname" style="width:100%" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" v-model="formData.email" style="width:100%" required><br><br>

    <label for="causale">Causale:</label><br>
    <input type="text" id="causale" name="causale" v-model="formData.causale" style="width:100%" required><br><br>

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
      product: 'Pagamento importo libero', // Initialize `product` with a default value

      formData: this.$inertia.form({
        email: "",
        name: "",
        surname: "",
        total_payable: "",
        causale: "",
      }),
    };
  },
    methods: {

  submit(){
    this.formData.post(
        this.route("front.importoLiberoStep2", {
          name: this.formData.name,
          surname: this.formData.surname,
          email: this.formData.email,
          total_payable: this.formData.total_payable,
          causale: this.formData.causale,
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
