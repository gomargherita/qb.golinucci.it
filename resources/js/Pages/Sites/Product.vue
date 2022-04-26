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
    <ProductDetail :product="product" :conditions="conditions"> </ProductDetail>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import ProductDetail from "@/Shared/Front/ProductDetail";

export default {
  components: {
    HOne,
    ProductDetail,
  },
  props: {
    product: Object,
    conditions: Array
  },
};
</script>
