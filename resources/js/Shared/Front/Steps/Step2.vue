<template>
  <Head :title="__('Scelta Polizza e Tempi')" />
  <HTwo :title="__('Scelta Polizza e Tempi')" />
  <!-- <Offers :product="product" :conditions="conditions"> </Offers> -->
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Offers from "@/Shared/Front/Offers";

export default {
  components: {
    HTwo,
    Offers,
  },
  props: {
    product: Object,
    conditions: Array,
  },
};
</script>
