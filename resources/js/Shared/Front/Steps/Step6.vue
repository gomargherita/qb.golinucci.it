<template>
  <Head :title="__('Firma Documento')" />
  <HTwo :title="__('Firma Documento')" />

  <iframe v-if="product.yousign" :src="procedureId" class="min-h-screen w-full"></iframe>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";

export default {
  components: {
    HTwo,
  },
  props: {
    cart: Object,
    product: Object,
  },
  computed: {
    procedureId() {
      return this.product.yousign_url + 'procedure/sign?members=' + this.product.yousign.member.id;
    },
  }
};
</script>
