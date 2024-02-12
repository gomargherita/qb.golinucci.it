<style>
@import '/resources/css/app.css';
</style>
<template>

  <div class="mt-8">
    <p class="text-center">
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
    <buy-loading-button
      style="color:white;"
      type="button"
      id="startGeneration"
      v-if="!isStartGenerationHidden"
      @click.once="startGeneration"
      class="gol-btn my-8 bg-primary"
    >
      {{ __("Start") }}
    </buy-loading-button>
    <form @submit.prevent="submit" v-if="!isNextHidden">
      <buy-loading-button
      style="color:white;"

        :loading="form.processing"
        :disabled="form.processing"
        :class="{ 'opacity-25': form.processing }"
        class="gol-btn my-8 bg-primary"
        type="submit"
        >{{ __("Next") }}</buy-loading-button
      >
      <p>Cliccando "Prosegui" verrà inviato il certificato di polizza a yousign e verrà notificato il cliente via email di procedere con la firma.
    </p>
    </form>

  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Layout from "@/Shared/Layouts/Layout";
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import LoadingButton from "@/Shared/LoadingButton";

import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    HTwo,
    BuyLoadingButton,
  },
  layout: Layout,
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
      let url = route("carts.storeLloyds", params);

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
      console.log(response);
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
      this.form.get(
        this.route("carts.updatetostep6", {
          cart: this.cart.id,
        }),
        {
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
    },
  },
};
</script>
