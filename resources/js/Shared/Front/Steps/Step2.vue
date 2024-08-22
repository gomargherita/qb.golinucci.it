<template>
  <Head :title="__('Scelta Polizza')" />
  <HTwo :title="__('Scelta Polizza')" />
  <div class="mt-8">
    <div>
      <div class="text-center">
        <p>Seleziona una delle offerte disponibili nella tabella sottostante.</p>
      </div>
      <form @submit.prevent="submit">
        <div class="overflow-x-auto p-1 w-full">
          <table
            class="offer-table text-primary table-auto w-full mx-auto border-collapse border border-primary shadow rounded-lg mt-8 overflow-hidden"
          >
            <thead class="rounded-lg">
              <tr class="uppercase bg-primary text-white">
            <th v-for="table_element in JSON.parse(product.table_elements.table_elements)" class="p-4 font-light">
                {{table_element}}            
            </th>
            <th v-if="product.id == 1 || product.id == 5" class="p-4 font-light">Scegli</th>
          </tr>
            </thead>
            <tbody>
              <tr v-if="product.offers.length == 0">
                <td colspan="6" class="p-4">
                  {{ __("No offer available.") }}
                </td>
              </tr>
              
              
              <!-- Prodotto MEDICI -->
              <tr
              v-if="product.id == 1 || product.id == 5"
                v-for="(offer, index) in product.offers"
                class="odd:bg-white even:bg-slate-100 uppercase"
              >
                <td v-if="product.id != 5"
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(offer.indemnity_limit)"
                ></td>
                <td v-if="product.id == 5"
                  class="p-4 border border-primary"
                  v-html="'<strong>'+$filters.formatPrice(offer.indemnity_limit) + '</strong>' + ' per sinistro <br /> <strong>3.000.000,00 €</strong> per anno'"
                  ></td>
                <td v-if="product.id == 1"
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(offer.indemnity_limit_patrimonial)"
                ></td>
                <td v-if="product.id == 5"
                  class="p-4 border border-primary"
                >{{ offer.indemnity_limit_patrimonial }} Anni</td>
                <td class="p-4 border border-primary">Zero</td>
                <td
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(parseFloat(offer.total_payable))"
                ></td>
                <td class="p-4 border border-primary" style="text-align: center;  text-transform: none;">
                  <a

                :href="offer.contract.contract_filepath"
                target="_blank"
                class="btn-gol"
              >
                {{ __("Condizioni") }}
              </a>
              <p style="margin:0"></p>
              <a
                :href="offer.contract.dip"
                target="_blank"
                class="btn-gol"
              >
                {{ __("DIP_doc_informativo") }}
              </a>
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
                      :checked="offer.id == this.form.product_offer_id_selected"
                    />
                  </label>
                </td>
              </tr>



              <!-- Prodotto BANCARI -->
              <template
                v-if="product.id == 2 || product.id == 3 || product.id == 4"
                v-for="(offer, index) in product.offers"
              >
              <tr v-if="offer.hide == 0" class="odd:bg-white even:bg-slate-100 uppercase">
                <td v-if="offer.indemnity_limit == 0"
              class="p-4 border border-primary"
              v-text="'------'"
            ></td>
            <td v-if="offer.indemnity_limit != 0"
              class="p-4 border border-primary"
              v-text="$filters.formatPrice(offer.indemnity_limit)"
            ></td>
            <td v-if="offer.indemnity_limit_patrimonial == 0"
              class="p-4 border border-primary"
              v-text="'------'"
            ></td>
                <td v-if="offer.indemnity_limit_patrimonial != 0"
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(offer.indemnity_limit_patrimonial)"
                ></td>
                <td v-if="offer.legal_fees == 0"
              class="p-4 border border-primary"
              v-text="'------'"
            ></td>
                <td v-if="offer.legal_fees != 0"
                  class="p-4 border border-primary"
                  v-text="$filters.formatPrice(parseFloat(offer.legal_fees))"
                ></td>
                <td v-for="(price, index) in bankersOffers[offer.combination-1]"
                  class="p-4 border border-primary">
                  {{ $filters.formatPrice(parseFloat(price)) }}

                  <label
                    class="flex items-center mt-6 select-none"
                    for="product_offer_id_selected"
                    style="display: inline;"

                  >
                    <input
                      name="product_offer_id_selected"
                      :data-offer_id="offers_id[offer.combination-1][index]"
                      class="mr-1"
                      type="radio"
                      @click="offerSelected"
                      :checked="offers_id[offer.combination-1][index] == this.form.product_offer_id_selected"
                    />
                  </label>
                </td>
                <td v-if="bankersOffers[offer.combination-1].length < 3"
            class="p-4 border border-primary"
            v-text="'-'">
            </td>
                <td class="p-4 border border-primary">
                  <a
                    :href="offer.contract.contract_filepath"
                    target="_blank"
                    class="btn-gol"
                  >
                    {{ __("Download") }}
                  </a>
                </td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
        <div style="line-height: 1.5" class="my-8" v-html="product.conditions"></div>
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
    BreezeValidationErrors,
  },
  props: {
    cart: Object,
    product: Object,
  },
  data() {
    return {
      offers: [],
      offers_flags: {},
      offers_id: [],
      filter_conditions: {},
      objectToCompare: {},
      form: this.$inertia.form({
        product_offer_id_selected: (this.cart.product_offer_id) ? this.cart.product_offer_id : null,
      }),
    };
  },
  computed:{
    bankersOffers(){
      for(let i=0; i<this.product.offers.length; i++){
        if(this.offers_flags[this.product.offers[i].combination] == null){
          this.offers.push([]);
          this.offers_id.push([]);
          this.offers_flags[this.product.offers[i].combination] = "true";
        }

        this.offers_id[this.product.offers[i].combination-1].push(this.product.offers[i].id);
        this.offers[this.product.offers[i].combination-1].push(this.product.offers[i].total_payable);
      }
      return(this.offers);
    },
  },
  methods: {
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
