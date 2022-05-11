<template>
  <Head :title="__('Ordine Confermato')" />
  <HTwo :title="__('Ordine Confermato')" />
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";

export default {
  components: {
    HTwo,
  },
  props: {
    product: Object,
  },
};
</script>
