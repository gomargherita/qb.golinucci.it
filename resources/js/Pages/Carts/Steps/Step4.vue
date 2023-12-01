<template>
  <div>
    <Head :title="form.cart_ref" />
    <!-- <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('carts', cart.product.id)">{{
        __("Carts")
      }}</Link>
      <span class="text-gray font-medium">/</span>
      <span>{{ __("Cart") }} {{ form.cart_ref }} </span>
    </h1> -->
    <trashed-message v-if="cart.deleted_at" class="mb-6" @restore="restore"
      >{{ __("This cart detail has been deleted") }}.</trashed-message
    >
    
    <h2 class="mt-12 text-2xl font-bold">{{ __("Payment") }}</h2>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <div v-if="!cart.payment" class="flex flex-wrap p-8">No payment yet.</div>
        <form v-if="cart.payment" @submit.prevent="update_payment">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form_payment.payment_gateway_id"
              :error="form_payment.errors.payment_gateway_id"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Payment Gateway"
            >
              <option
                v-for="payment_gateway in payment_gateways"
                :value="payment_gateway.id"
                :selected="payment_gateway.id == form_payment.payment_gateway_id"
              >
                {{ payment_gateway.name }}
              </option></select-input
            >

            <text-input
              v-model="form_payment.transaction_ref"
              :error="form_payment.errors.transaction_ref"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Transaction ref"
            />
            <checkbox-input
              v-model="form_payment.paid"
              :error="form_payment.errors.paid"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Paid"
              :checked="form_payment.paid"
            />
            <text-input
              v-model="form_payment.paid_at"
              :error="form_payment.errors.paid_at"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Paid at"
              type="date"
              pattern="\d{4}-\d{2}-\d{2}"
            />

            <div class="pb-8 pr-6 w-full lg:w-full">
              <p class="mb-2">
                <b>Importo pagamento</b>: {{ this.cart.payment?.total_payable }}<br />
                <b
                  >Numero certificato di pagamento assegnato:
                  {{ this.cart.payment?.payment_no }}
                </b>
              </p>
              <p class="mb-2">
                <b class="text-red-500">Attenzione!</b>: prima di segnare un pagamento
                come pagato controllare che il
                <b>metodo di pagamento sia corretto</b> perché non sarà più possibile
                correggere a mano il numero di certificato di pagamento.<br />
                Ricordare che con il metodo di pagamento con bonifico bancario, una volta
                segnato il pagamento come pagato, la procedura invia al cliente una mail
                che conferma il pagamento e fa procedere il cliente dallo step 5.<br />
                In caso di problemi e di prenotazione di un numero di certificato
                sbagliato è possibile ricorrere alla cancellazione del record di pagamento.
              </p>
            </div>
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <button
              class="text-red-600 hover:underline"
              tabindex="-1"
              type="button"
              @click="destroy_payment"
            >
              {{ __("Delete") }} {{ __("Payment") }}
            </button>
            <loading-button
              :loading="form_payment.processing"
              class="btn-gol-primary ml-auto"
              type="submit"
              >{{ __("Update") }} {{ __("Payment") }}</loading-button
            >
          </div>
        </form>
      </div>
    </div>

    
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    LoadingButton,
    SelectInput,
    CheckboxInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    cart: Object,
    user: Object,
    payment_gateways: Array,
    can: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        cart_ref: this.cart.cart_ref,
        product_step_id: this.cart.product_step_id,
      }),
      form_payment: this.$inertia.form({
        cart_id: this.cart.id,
        payment_gateway_id: this.cart.payment?.payment_gateway_id,
        paid: this.cart.payment?.paid,
        transaction_ref: this.cart.payment?.transaction_ref,
        paid_at: this.cart.payment?.paid_at,
        total_payable: this.cart.payment?.total_payable,
      }),
    };
  },
  computed: {
    cartUrl() {
      return "/products/" + this.cart.product.id + "/buy/" + this.cart.cart_ref;
    },
  },
  methods: {
    update() {
      this.form.put(`/admin/carts/${this.cart.id}`);
    },
    update_payment() {
      this.form_payment.put(`/admin/payments/${this.cart.payment?.id}`);
      this.route("carts.store_step4", {
          product: this.cart.product.id,
          cart: this.cart.cart_ref,
        });
    },
    destroy_payment() {
      if (confirm("Are you sure you want to delete this payment?")) {
        this.$inertia.delete(`/admin/payments/${this.cart.payment?.id}`);
      }
    },
    destroy() {
      if (confirm("Are you sure you want to delete this cart?")) {
        this.$inertia.delete(`/admin/carts/${this.cart.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this cart?")) {
        this.$inertia.put(`/admin/carts/${this.cart.id}/restore`);
      }
    },
  },
};
</script>
