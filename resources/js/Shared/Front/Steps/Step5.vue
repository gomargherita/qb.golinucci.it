<template>
  <Head :title="__('Generazione Documento e Firma')" />
  <HTwo :title="__('Generazione Documento e Firma')" />
  <div class="mt-8">
    <p>
      Siamo pronti a generare i tuoi documenti. Ci vorranno alcuni secondi, ti preghiamo
      di attendere e di non abbandonare la pagina, per favore.
    </p>
    <p v-if="product.test" v-text="product.test"></p>
    <p
      v-if="product.generationError"
      v-text="product.generationError"
      class="font-bold text-red-500"
    ></p>

    <form @submit.prevent="submit">
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
      form: this.$inertia.form({
        handshake_result: null,
        validation_result: null,
      }),
    };
  },
  methods: {
    submit() {
      let product = this.product.id;
      let cart = this.cart.cart_ref;
      Inertia.post(
        route("front.product.store_step5", {
          product: product,
          cart: cart,
        }),
        { test: "testtest" },
        {
          only: ["product"],
        }
      );
      //   Inertia.post(
      //     route("front.product.store_step5"),
      //     {
      //       product: product,
      //       cart: cart,
      //     },
      //     {
      //       onSuccess: () => {

      //         Inertia.reload({
      //           data: {
      //             //   product: this.product.id,
      //             //   cart: this.cart.cart_ref,
      //             test: "fase 2",
      //           },
      //         });
      //       },
      //     }
      //   );

      //   axios
      //     .post(route("front.product.store_step5"), {
      //       product: product,
      //       cart: cart,
      //     })
      //     .then((res) => {
      //       console.log(res);
      //     });
      //   this.form.post(
      //     this.route("front.product.store_step5", {
      //       product: this.product.id,
      //       cart: this.cart.cart_ref,
      //     }),
      //     {
      //       // preserveScroll: (page) => Object.keys(page.props.errors).length,
      //       onSuccess: (page) => {
      //         this.form.handshake_result = page;
      //       },
      //       onError: (errors) => {
      //         console.log(errors);
      //       },
      //       //   onFinish: (visit) => {
      //       //       // console.log(visit);
      //       //       Inertia.reload({ only: ['users'] })
      //       //   },
      //     }
      //   );
    },
  },
};
</script>
