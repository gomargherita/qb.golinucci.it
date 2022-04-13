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
    Product {{ product.name }}
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import ProductBox from "@/Shared/Front/ProductBox";

export default {
  components: {
    HOne,
  },
  props: {
    product: Object,
  },
};
</script>
