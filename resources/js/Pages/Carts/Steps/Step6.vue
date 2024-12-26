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
    <div id="iframe-container"></div>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Layout from "@/Shared/Layouts/Layout";

export default {
  components: {
    HTwo,
  },
  layout: Layout,
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
  mounted() {
    let yousign = document.createElement('script')
    yousign.setAttribute('src', 'https://cdn.yousign.tech/iframe-sdk-1.6.0.min.js');
    yousign.setAttribute('integrity', 'sha384-/7MD1voOOzWVz7FmgeMwmmd1DO85Mo0PkkxdYd9j2wDGzGDGRG/phgnL0c9Xyy52');
    yousign.setAttribute('crossorigin', 'anonymous');
    document.head.appendChild(yousign);

    yousign.onload = function() {new Yousign({
    signatureLink: product.yousign_member_signer_id,
    iframeContainerId: 'iframe-container',
    isSandbox: true, //To set if you use the sandbox environment
    });
  }
  }
};
</script>

<style scoped>
.yousign-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
#yousign-iframe {
  width: 100%;
  height: 600px;
  border: none;
}
</style>