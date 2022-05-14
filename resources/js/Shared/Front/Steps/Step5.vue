<template>
  <Head :title="__('Generazione Documento e Firma')" />
  <HTwo :title="__('Generazione Documento e Firma')" />
  <div class="mt-8">
    <p>
      Siamo pronti a generare i tuoi documenti. Ci vorranno alcuni secondi, ti preghiamo
      di attendere e di non abbandonare la pagina, per favore.
    </p>

    <p v-if="product.documentGeneration" v-text="product.documentGeneration"></p>

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
    // documentGeneration: {
    //   type: String,
    //   default: "",
    // },
  },
  data() {
    return {
      form: this.$inertia.form({
        
      }),
    };
  },
  methods: {
    doprintRequest: async (product, cart, method_name) => {
      let myResponse = "";
      await axios
        .post(
          route("front.product.store_step5", {
            product: product,
            cart: cart,
          }),
          { documentGeneration: method_name }
        )
        .then((response) => {
          if (response.data.error) {
            myResponse = { error: true, message: response.data.message };
          } else {
            myResponse = { error: false, message: response.data.message };
          }
        });
      return myResponse;
    },
    async submit() {
      let product = this.product.id;
      let cart = this.cart.cart_ref;
      let handshakeResponse = await this.doprintRequest(
        this.product,
        this.cart,
        "handshake"
      ).then((response) => {
        debugger;
        this.$props.product.documentGeneration = response.message;
        if (response.error) {
          return false;
        }
        return true;
      });

      if (handshakeResponse) {
        let validationResponse = await this.doprintRequest(
          this.product,
          this.cart,
          "validation"
        ).then((response) => {
          this.$props.product.documentGeneration = response.message;
          if (response.error) {
            return false;
          }
          return true;
        });
      }

      // this.product.documentGeneration = response.data.message;
      //   if (handshakeResponse) {
      //     //   const secondRequest = await axios
      //     //     .post(
      //     //       route("front.product.store_step5", {
      //     //         product: product,
      //     //         cart: cart,
      //     //       }),
      //     //       { documentGeneration: "validation" }
      //     //     )
      //     //     .then((response) => {
      //     //       if (reponse.error) {
      //     //         this.product.documentGeneration = response.data.message;
      //     //         return false;
      //     //       } else {
      //     //         this.product.documentGeneration = response.data.message;
      //     //         return true;
      //     //       }
      //     //     });
      //   }

      //   Inertia.post(
      //     route("front.product.store_step5", {
      //       product: product,
      //       cart: cart,
      //     }),
      //     { documentGeneration: "testtest" },
      //     {
      //       only: ["product"],
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
