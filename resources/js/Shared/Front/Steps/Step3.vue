<template>
  <Head :title="__('Dichiarazioni dell\'Assicurato')" />
  <HTwo :title="__('Dichiarazioni dell\'Assicurato')" />
  <div class="mt-8">
    <div v-html="product.pre_declarations"></div>
    <form @submit.prevent="submit">
      <div class="overflow-x-auto p-1 w-full">
        <table
          class="declaration-table table-auto w-full mx-auto border-collapse border-2 border-primary shadow rounded-lg mt-8 overflow-hidden"
        >
          <tbody>
            <tr v-if="!product.declarations.length">
              <td colspan="6" class="p-4">
                {{ __("Non sono disponibili dichiarazioni per questo prodotto.") }}
              </td>
            </tr>
            <tr
              v-for="(declaration, index) in product.declarations"
              class="odd:bg-white even:bg-slate-100"
            >
              <td class="p-4" v-html="declaration.declaration"></td>
              <td class="p-4">
                <label
                  class="flex items-center mt-6 select-none"
                  for="product_declaration_id_selected"
                >
                  <input
                    id="product_offer_id_selected"
                    name="product_offer_id_selected"
                    :data-declaration_id="declaration.id"
                    class="mr-1"
                    type="checkbox"
                    @click="declarationSelected"
                  />
                </label>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-html="product.post_declarations"></div>
      <div class="my-8 float-right">FIRMA?</div>
      <div>
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
        // user_id: this.$page.props.auth.user.id,
        // first_name: this.$page.props.clientDetails.first_name,
        // last_name: this.$page.props.clientDetails.last_name,
        // fiscal_code: this.$page.props.clientDetails.fiscal_code,
        // address: this.$page.props.clientDetails.address,
        // city: this.$page.props.clientDetails.city,
        // region: this.$page.props.clientDetails.region,
        // postal_code: this.$page.props.clientDetails.postal_code,
        // country: "IT",
        // phone: this.$page.props.clientDetails.phone,
        // confirmation: false,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(
        this.route("front.product.store_step3", {
          product: this.$page.props.product.id,
          cart: this.$page.props.cart.cart_ref,
        }),
        {
          // onSuccess: () => this.form.reset('password', 'password_confirmation'),
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
    },
  },
};
</script>
