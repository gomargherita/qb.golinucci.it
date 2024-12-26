<template>
  <Head :title="__('Firma Documento')" />
  <HTwo :title="__('Firma Documento')" />
  <div class="mt-8">
    <p class="text-center">
      Manca solo un ultimo step: firmare i tuoi documenti.<br />
      <span class="font-bold text-xl text-red-500">ATTENZIONE</span><br />
      <span class="font-bold text-lg"
        >Per firmare il documento visiona tutte le pagine sottostanti e infine clicca sul bottone FIRMA verde in
        basso a destra.</span
      >
    </p>
    <div v-if="product.signature_error">
      {{ product.signature_error }}
    </div>
    <iframe
      v-if="product.yousign_member_signer_id"
      :src="procedureId"
      class="my-8 min-h-screen w-full"
    ></iframe>
  </div>
</template>
<script src="https://cdn.yousign.tech/iframe-sdk-1.6.0.min.js" integrity="sha384-/7MD1voOOzWVz7FmgeMwmmd1DO85Mo0PkkxdYd9j2wDGzGDGRG/phgnL0c9Xyy52" crossorigin="anonymous"></script>


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
        this.product.yousign_member_signer_id
      );
    },
  },
};
</script>
