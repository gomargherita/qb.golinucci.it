<template>
  <Head>
    <title>{{ __("User account") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B about us meta description"
    />
  </Head>
  <HOne :title="`User account`" />

  <section class="container mx-auto p-2">
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
};
</script>
