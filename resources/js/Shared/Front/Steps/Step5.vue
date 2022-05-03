<template>
  <Head :title="__('Autenticazione Cliente')" />
  <HTwo :title="__('Autenticazione Cliente')" />
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";

export default {
  components: {
    HTwo,
  },
  props: {
    product: Object,
    conditions: Array,
  },
};
</script>
