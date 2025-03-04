<template>
  <Head>
    <title>{{ product.name }} - {{ __("Products") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  <HOne :title="product.name" />

  <section id="main-content" class="container mx-auto p-2">
    <BuyProduct :product="product" :cart="cart" :paypal_client_id="paypal_client_id" :clientDetails="clientDetails"> </BuyProduct>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import BuyProduct from "@/Shared/Front/BuyProduct";

export default {
  components: {
    HOne,
    BuyProduct,
  },
  props: {
    cart: Object,
    product: Object,
    paypal_client_id: String,
    clientDetails: Object
  },
};
</script>
