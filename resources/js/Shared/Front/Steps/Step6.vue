<style scoped>
#iframe-container {
  width: 1000px;
  height: 1000px;
}

#iframe-container :deep(iframe) {
  width: 1000px !important;
  height: 1000px !important;
}
</style>

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
import axios from "axios";

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
  mounted() {
    let yousign = document.createElement('script')
    yousign.setAttribute('src', 'https://cdn.yousign.tech/iframe-sdk-1.6.0.min.js');
    yousign.setAttribute('integrity', 'sha384-/7MD1voOOzWVz7FmgeMwmmd1DO85Mo0PkkxdYd9j2wDGzGDGRG/phgnL0c9Xyy52');
    yousign.setAttribute('crossorigin', 'anonymous');
    document.head.appendChild(yousign);

    yousign.onload = () => {
      
    const yousignInstance = new Yousign({
    signatureLink: this.product.yousign_member_signer_id,
    iframeContainerId: 'iframe-container',
    isSandbox: true, //To set if you use the sandbox environment
    });

    yousignInstance.onSignatureDone(async (data)=> {
    //You can download the document with all signatures in it
    console.log("Signature Done - The signature request is completed");
    
    const path = window.location.pathname;
    const segments = path.split('/');
    // L'ultimo segmento (il codice lungo)
    const cart_ref_extracted = segments.pop() || segments.pop();
    const response = await axios.post('/yousign/procedure_finished', {
            signature_request_id: data.signature_request_id,
            cart_ref: cart_ref_extracted
        },
      {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
    });
  }
  }
};
</script>