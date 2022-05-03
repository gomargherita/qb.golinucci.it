<template>
  <Head :title="__('Dichiarazioni dell\'Assicurato')" />
  <HTwo :title="__('Dichiarazioni dell\'Assicurato')" />
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
