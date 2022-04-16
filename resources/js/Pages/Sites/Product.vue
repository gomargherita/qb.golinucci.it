<template>
  <Head>
    <title>{{ __("Product") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  <HOne :title="product.name" />

  <section class="container mx-auto p-2">
    <ProductDetailBox :product="product"> </ProductDetailBox>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import ProductDetailBox from "@/Shared/Front/ProductDetailBox";

export default {
  components: {
    HOne,
    ProductDetailBox
  },
  props: {
    product: Object,
  },
};
</script>
