<template>
  <Head>
    <title>{{ __("Products") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  <HOne :title="`Products`" />

  <section class="container mx-auto p-2">
    <ProductBox :products="products"> </ProductBox>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import ProductBox from "@/Shared/Front/ProductBox";

export default {
  components: {
    HOne,
    ProductBox,
  },
  props: {
    products: Array,
  },
};
</script>
