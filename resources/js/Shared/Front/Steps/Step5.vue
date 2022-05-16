<template>
  <Head :title="__('Generazione Documento e Firma')" />
  <HTwo :title="__('Generazione Documento e Firma')" />
  <div class="mt-8">
    <p>
      Siamo pronti a generare i tuoi documenti. Ci vorranno alcuni secondi, ti preghiamo
      di attendere e di non abbandonare la pagina, per favore.
    </p>
    <ul v-if="product.documentGenerator.length">
      <li
        v-for="documentGenerator in product.documentGenerator"
        v-text="documentGenerator.message"
      ></li>
    </ul>
    <button
      type="button"
      id="startGeneration"
      v-if="!isHidden"
      @click.once="startGeneration"
      class="gol-btn my-8 bg-primary"
    >
      Start
    </button>
    <form @submit.prevent="submit" class="hidden">
      <buy-loading-button
        :loading="form.processing"
        :disabled="form.processing"
        :class="{ 'opacity-25': form.processing }"
        class="gol-btn my-8 bg-primary"
        type="submit"
        >{{ __("Next") }}</buy-loading-button
      >
    </form>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    HTwo,
    BuyLoadingButton,
  },
  props: {
    cart: Object,
    product: Object,
  },
  data() {
    return {
      form: this.$inertia.form({}),
      isHidden: false,
    };
  },
  methods: {
    doprintRequest: async (params) => {
      let response = {};
      let url = route("front.product.store_step5", params);

      await axios
        .post(url)
        .then((res) => {
          if (res.data.error) {
            throw res.data;
          }
          response = res.data;
        })
        .catch((err) => {
          throw err;
        });

      return response;
    },
    async startGeneration() {
      this.isHidden = true;
      let self = this;

      let params = { product: self.product, cart: self.cart };
      // Prima chiamata

      try {
        // Imposta il metodo da utilizzare nel BE
        params.method = "certificate_initialization";

        // Esegue la chiamata al metodo e con la response POSITIVA aggiorna la prop del componente
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        // Con la response NEGATIVA aggiorna la prop del componente
        self.product.documentGenerator.push(err);
        // Non esegue la chiamata successiva
        return false;
      }

      // Seconda chiamata
      try {
        // Imposta il metodo da utilizzare nel BE
        params.method = "handshake";

        // Esegue la chiamata al metodo e con la response POSITIVA aggiorna la prop del componente
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        // Con la response NEGATIVA aggiorna la prop del componente
        self.product.documentGenerator.push(err);
        // Non esegue la chiamata successiva
        return false;
      }

      // Terza chiamata
      try {
        params.method = "validation";
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        self.product.documentGenerator.push(err);
        return false;
      }
    },
  },
};
</script>
