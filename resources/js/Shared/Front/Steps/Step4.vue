<template>
  <Head :title="__('Metodo di pagamento')" />
  <HTwo :title="__('Metodo di pagamento')" />
  <div class="mt-8">
    <form @submit.prevent="submit">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="(payment_gateway, index) in product.payment_gateways"
          class="odd:bg-white even:bg-slate-100"
        >
          <div class="p-4">
            <icon
              v-if="payment_gateway.name == 'Paypal'"
              name="paypal"
              class="block mx-auto w-full max-w-xs fill-white"
              height="50"
            />
            <div class="text-center" v-if="payment_gateway.name == 'BBM'">
              Bonifico Bancario
            </div>
          </div>
          <div class="p-4 text-center">
            <input
              name="payment_gateway_id_selected"
              :data-payment_id="payment_gateway.id"
              class="mr-1"
              type="radio"
              @click="paymentGatewaySelected"
            />
          </div>
        </div>
      </div>
      <div>
        <div class="my-4" v-html="product.payments_description"></div>
        <BreezeValidationErrors class="mb-4" />
        <buy-loading-button
          :loading="form.processing"
          :disabled="form.processing"
          :class="{ 'opacity-25': form.processing }"
          class="gol-btn my-8 bg-primary"
          type="submit"
          >{{ __("Next") }}</buy-loading-button
        >
      </div>
    </form>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

export default {
  components: {
    BuyLoadingButton,
    HTwo,
    BreezeValidationErrors,
  },
  props: {
    cart: Object,
    product: Object,
    conditions: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        payment_gateway_id_selected: null,
      }),
    };
  },
  methods: {
    paymentGatewaySelected: function (evt) {
      console.log(evt.target.dataset.payment_id);
      this.form.payment_gateway_id_selected = evt.target.dataset.payment_id;
    },
    submit() {
      this.form.post(
        this.route("front.product.store_step4", {
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
