<template>
  <Head :title="__('Scelta Polizza e Tempi')" />
  <HTwo :title="__('Scelta Polizza e Tempi')" />
  <Offers :product="product" :conditions="conditions" :cart="cart"> </Offers>
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
    cart: Object,
    product: Object,
    conditions: Array,
  },
};
</script>
