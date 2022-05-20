<template>
  <Head :title="__('Firma Documento')" />
  <HTwo :title="__('Firma Documento')" />
  <div class="mt-8">
    <p class="text-center">
      Manca solo un ultimo step: firmare i tuoi documenti.<br />
      <span class="font-bold text-xl text-red-500">ATTENZIONE</span><br />
      <span class="font-bold text-lg">Per firmare i documenti visiona tutte le pagine e clicca sul buttone FIRMA in basso a destra.</span>
    </p>
    <iframe
      v-if="product.yousign"
      :src="procedureId"
      class="my-8 min-h-screen w-full"
    ></iframe>
  </div>
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
      return (
        this.product.yousign_url +
        "procedure/sign?members=" +
        this.product.yousign.member.id
      );
    },
  },
};
</script>
