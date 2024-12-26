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
    <div v-if="product.yousign_member_signer_id" class="my-8 min-h-screen w-full">
      <div id="yousign-iframe"></div>
    </div>
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
    this.loadYousignIframe();
  },
  methods: {
    loadYousignIframe() {
      if (!window.YousignIframe) {
        const script = document.createElement('script');
        script.src = 'https://cdn.yousign.tech/iframe-sdk-1.6.0.min.js';
        script.integrity = 'sha384-/7MD1voOOzWVz7FmgeMwmmd1DO85Mo0PkkxdYd9j2wDGzGDGRG/phgnL0c9Xyy52';
        script.crossOrigin = 'anonymous';
        script.onload = () => this.initIframe();
        document.body.appendChild(script);
      } else {
        this.initIframe();
      }
    },
    initIframe() {
      this.yousignIframe = new window.YousignIframe({
        selector: '#yousign-iframe',
        signatureRequestId: this.product.yousign_member_signer_id,
        language: 'en',
        height: '600px',
        width: '100%'
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