<template>
  <Head :title="__('Dichiarazioni dell\'Assicurato')" />
  <HTwo :title="__('Dichiarazioni dell\'Assicurato')" />
  <div class="mt-8">
    <div v-html="product.pre_declarations"></div>
    <form @submit.prevent="submit">
      <div class="overflow-x-auto p-1 w-full">
        <div class="grid justify-items-end">
          <label class="bg-primary px-3 py-2 my-2 rounded-full">
            <span class="text-white text-lg mr-2">Conferma tutte</span>
            <input type="checkbox" v-model="selectAll" />
          </label>
        </div>
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
                    v-model="form.selected_declarations"
                    :value="declaration.id"
                    class="mr-1"
                    name="selected_declarations[]"
                    type="checkbox"
                    @click="declarationSelected"
                    number
                  />
                </label>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="my-4" v-html="product.post_declarations"></div>
      <div class="my-8 grid justify-items-end">
        <TextInput
            v-model="form.signature"
            :error="form.errors.signature"
            class="pb-8 w-full lg:w-1/3"
            label="Firmatario"
          />
      </div>
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
import TextInput from "@/Shared/TextInput";

export default {
  components: {
    BuyLoadingButton,
    HTwo,
    TextInput,
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
        selected_declarations: [],
        signature: null
      }),
    };
  },
  computed: {
    selectAll: {
      get: function () {
        return this.product.declarations
          ? this.form.selected_declarations.length == this.product.declarations.length
          : false;
      },
      set: function (value) {
        var selected = [];

        if (value) {
          this.product.declarations.forEach(function (declaration) {
            selected.push(declaration.id);
          });
        }

        this.form.selected_declarations = selected;
      },
    },
  },
  methods: {
    submit() {
      this.form.post(
        this.route("front.product.store_step3", {
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
