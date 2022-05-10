<template>
  <Head>
    <title>{{ product.name }} - {{ __("Products") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B products meta description"
    />
  </Head>
  <HOne :title="product.name" />

  <section id="main-content" class="container mx-auto p-2">
    <div class="p-12">
      Attenzione: c'è stato un
      <b class="font-bold">errore nella gestione del pagamento per l'ordine</b>.<br />
      Ti abbiamo inviato una email di notifica, ci attiveremo il prima possibile per
      risolvere il problema.
    </div>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";

export default {
  components: {
    HOne,
  },
  props: {
    product: Object,
    cart: Object,
    payment: Object,
  },
};
</script>
