<template>
  <Head :title="__('Ordine Confermato')" />
  <HTwo :title="__('Ordine Confermato')" />
  <div class="mt-8">
    <p class="text-center">
      Grazie per aver acquistato la polizza.<br />
      <span class="font-bold text-lg">
        Abbiamo inviato al tuo indirizzo email tutti i tuoi documenti.
      </span>
    </p>
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
};
</script>
