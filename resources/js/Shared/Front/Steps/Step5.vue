<template>
  <Head :title="__('Generazione Documento')" />
  <HTwo :title="__('Generazione Documento')" />
  <div class="mt-8">
    <p class="text-center">
      <span style="font-size:27px">Pagamento riuscito.</span><br>
      Siamo pronti a generare i tuoi documenti.<br>
      <span class="font-bold text-xl text-red-500">ATTENZIONE</span><br>
      <span class="font-bold text-lg"
        >Ci vorranno alcuni secondi, ti preghiamo di attendere e di non abbandonare la
        pagina fino alla conclusione della procedura.</span>
    </p>
    <div v-if="product.documentGenerator.length">
      <ul class="my-4 font-bold text-primary text-center">
        <li
          v-for="documentGenerator in product.documentGenerator"
          v-text="documentGenerator.message"
        ></li>
      </ul>
    </div>
    <button
      type="button"
      id="startGeneration"
      v-if="!isStartGenerationHidden"
      @click.once="startGeneration"
      class="gol-btn my-8 bg-primary"
    >
      {{ __("Start") }}
    </button>
    <form @submit.prevent="submit" v-if="!isNextHidden">
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
      isStartGenerationHidden: false,
      isNextHidden: true,
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
      this.isStartGenerationHidden = true;
      let self = this;

      let params = { product: self.product, cart: self.cart };

      // Prima chiamata
      try {
        params.method = "initialization";
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
        params.method = "handshake";
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        self.product.documentGenerator.push(err);
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

      // Quarta chiamata
      try {
        params.method = "upload";
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        self.product.documentGenerator.push(err);
        return false;
      }

      // Quinta chiamata
      try {
        params.method = "transaction";
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        self.product.documentGenerator.push(err);
        return false;
      }

      // Sesta chiamata
      try {
        params.method = "download";
        const res = await this.doprintRequest(params);
        self.product.documentGenerator.push(res);
      } catch (err) {
        self.product.documentGenerator.push(err);
        return false;
      }

      this.isNextHidden = false;
    },
    submit() {
      this.form.post(
        this.route("front.product.store_step5", {
          product: this.product.id,
          cart: this.cart.cart_ref,
        }),
        {
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
    },
  },
};
</script>
