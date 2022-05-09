<template>
  <Head :title="__('Scelta Polizza e Tempi')" />
  <HTwo :title="__('Scelta Polizza e Tempi')" />
  <div class="mt-8">
    <div>
      <div v-if="conditions.length">
        <div class="text-center">{{ __("Filtra tra le nostre offerte di polizza") }}</div>
        <div
          :class="`offer-filters grid grid-cols-1 md:grid-cols-${conditions.length} mx-auto text-center gap-4`"
        >
          <div v-for="condition in conditions" class="inline-block">
            <label v-text="condition.field_name"></label>
            <div v-if="condition.field_type == 'select'">
              <select v-model="filter_conditions[condition.id]" @change="change">
                <option
                  v-for="(option_value, option_key) in JSON.parse(condition.field_value)"
                  :value="option_key"
                  v-text="option_value"
                  :selected="option_key == 1"
                ></option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <form @submit.prevent="submit">
        <div class="overflow-x-auto p-1 w-full">
          <table
            class="offer-table text-primary table-auto w-full mx-auto border-collapse border border-primary shadow rounded-lg mt-8 overflow-hidden"
          >
            <thead class="rounded-lg">
              <tr class="uppercase bg-primary text-white">
                <th class="p-4 font-light"></th>
                <th class="p-4 font-light">
                  Massimale per Danni Materiali e Immateriali per la Professione Sanitaria
                </th>
                <th class="p-4 font-light">
                  Massimale R.C Patrimoniale da funzioni di carattere amminstrativo e/o
                  organizzativo
                </th>
                <th class="p-4 font-light">Franchigia</th>
                <th class="p-4 font-light">Costo Annuo</th>
                <th class="p-4 font-light">Wording</th>
                <th class="p-4 font-light">Choose</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!filteredRows.length">
                <td colspan="6" class="p-4">
                  {{ __("Select a combination using filters above") }}
                </td>
              </tr>
              <tr
                v-for="(offer, index) in filteredRows"
                class="odd:bg-white even:bg-slate-100 uppercase"
              >
                <td class="p-4 border border-primary">{{ index + 1 }}</td>
                <td
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(offer.indemnity_limit)"
                ></td>
                <td
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(offer.indemnity_limit_patrimonial)"
                ></td>
                <!-- TODO Massimale RC Patrimoniale solo per i rinnovi 1.000.000, i nuovi è 500.000 -->
                <td class="p-4 border border-primary">Zero</td>
                <td
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(parseFloat(offer.total_payable))"
                ></td>
                <td class="p-4 border border-primary">
                  <Link
                    :href="offer.contract.contract_filepath"
                    target="_blank"
                    class="btn-gol"
                  >
                    {{ __("Download") }}
                  </Link>
                </td>
                <td class="p-4 border border-primary">
                  <label
                    class="flex items-center mt-6 select-none"
                    for="product_offer_id_selected"
                  >
                    <input
                      name="product_offer_id_selected"
                      :data-offer_id="offer.id"
                      class="mr-1"
                      type="radio"
                      @click="offerSelected"
                    />
                  </label>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="my-8" v-html="product.conditions"></div>
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
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Offers from "@/Shared/Front/Offers";
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import isEqual from "lodash/isEqual";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

export default {
  components: {
    BuyLoadingButton,
    HTwo,
    Offers,
    BreezeValidationErrors
  },
  props: {
    cart: Object,
    product: Object,
    conditions: Array,
  },
  data() {
    return {
      filter_conditions: {},
      objectToCompare: {},
      form: this.$inertia.form({
        product_offer_id_selected: null,
      }),
    };
  },
  computed: {
    filteredRows() {
      return this.filter();
    },
    objectToCompareEdit: {
      get() {
        return this.objectToCompare;
      },
      set(v) {
        this.objectToCompare = v;
      },
    },
  },
  methods: {
    filter() {
      return this.product.offers.filter((offer) => {
        let conditional_fields_flag = false;
        this.objectToCompareEdit = {};
        offer.conditional_fields.forEach((conditional_field) => {
          this.objectToCompareEdit[conditional_field.id] =
            conditional_field.pivot.selected_field_value;
        });
        // console.log(this.filter_conditions, this.objectToCompare);
        conditional_fields_flag = isEqual(this.objectToCompare, this.filter_conditions);
        // console.log("equal ", conditional_fields_flag);
        return conditional_fields_flag;
      });
    },
    change: function (evt) {
      //this.$emit("change", evt);
    },
    offerSelected: function (evt) {
      this.form.product_offer_id_selected = evt.target.dataset.offer_id;
    },
    submit() {
      this.form.post(
        this.route("front.product.store_step2", {
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
