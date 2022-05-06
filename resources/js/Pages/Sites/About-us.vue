<template>
  <Head>
    <title>{{ __("About us") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B about us meta description"
    />
  </Head>
  <HOne :title="`About us`" />

  <section id="main-content" class="container mx-auto p-2">
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
