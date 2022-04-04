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
    <ProductBox> </ProductBox>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";
import ProductBox from "@/Shared/Front/ProductBox";
// import headingBgImg from "@/../img/sign.jpg";

export default {
  components: {
    HOne,
    ProductBox,
  },
  //   data() {
  //     return {
  //       headingBgImg2: {
  //         backgroundImage: `url(${headingBgImg})`,
  //         textAlign: "center",
  //       },
  //     };
  //   },
};
</script>
