<template>
  <Head :title="__('Generazione Documento')" />
  <HTwo :title="__('Generazione Documento')" />
  <div class="mt-8">
    <p class="text-center">
      <span style="font-size:27px">Pagamento riuscito.</span><br>
      Stiamo generando i tuoi documenti, non serve cliccare nulla.
    </p>
    <ul class="my-4 font-bold text-primary text-center">
      <li v-for="(line, index) in lines" :key="index" v-text="line"></li>
    </ul>
    <p v-if="error" class="text-center font-bold text-red-500">{{ error }}</p>
    <button
      type="button"
      v-if="error"
      @click="retry"
      class="gol-btn my-8 bg-primary"
    >
      Riprova
    </button>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    HTwo,
  },
  props: {
    cart: Object,
    product: Object,
  },
  data() {
    return {
      form: this.$inertia.form({}),
      lines: ["Preparazione documenti in corso..."],
      error: null,
      advanced: false,
      polls: 0,
      timer: null,
    };
  },
  mounted() {
    if (this.product.completedDocumentGenerator || this.cart.product_step_id >= 6) {
      this.goNext();
      return;
    }
    this.startPipeline();
  },
  beforeUnmount() {
    if (this.timer) {
      clearTimeout(this.timer);
    }
  },
  methods: {
    async postMethod(method) {
      const url = route("front.product.store_step5", {
        product: this.product,
        cart: this.cart,
        method,
      });
      const res = await axios.post(url);
      return res.data;
    },
    async watchProgress() {
      try {
        const status = await this.postMethod("status");
        if (status.error) {
          this.error = status.message;
          return;
        }
        if (status.message) {
          this.lines = [status.message];
        }
        if (status.completed || status.cart_step >= 6) {
          this.goNext();
          return;
        }
        this.polls += 1;
        if (this.polls > 40) {
          this.error =
            "La generazione sta impiegando più del previsto. Puoi riprovare, oppure contattarci al 0547 22351.";
          return;
        }
        this.timer = setTimeout(() => this.watchProgress(), 2500);
      } catch (err) {
        this.error =
          (err && err.message) ||
          "Generazione documenti non riuscita. Riprova oppure contattaci al 0547 22351.";
      }
    },
    async startPipeline() {
      this.error = null;
      this.polls = 0;
      this.lines = ["Preparazione documenti in corso..."];
      try {
        const res = await this.postMethod("run");
        if (res && res.error) {
          this.error = res.message;
          return;
        }
        if (res && (res.completed || res.cart_step >= 6)) {
          this.goNext();
          return;
        }
      } catch (e) {
        this.error =
          "Generazione documenti non riuscita. Riprova oppure contattaci al 0547 22351.";
        return;
      }
      this.watchProgress();
    },
    async retry() {
      this.lines = ["Nuovo tentativo in corso..."];
      await this.startPipeline();
    },
    goNext() {
      if (this.advanced) {
        return;
      }
      this.advanced = true;
      if (this.cart.product_step_id >= 6) {
        Inertia.visit(
          this.route("front.product.buy", {
            product: this.product.id,
            cart: this.cart.cart_ref,
          })
        );
        return;
      }
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
