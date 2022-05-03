<template>
  <Head :title="__('Metodo di pagamento')" />
  <HTwo :title="__('Metodo di pagamento')" />
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
