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
    
  <h1>Siamo spiacenti, in questo momento non è possibile procedere all'acquisto.</h1><br>
  
  
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
