<template>
  <div class="mt-8">
    <div v-if="product.conditional_fields.length">
      <div v-if="product.id == 1 || product.id == 5" class="text-center">{{ __("Filtra tra le nostre offerte di polizza") }}</div>
      <div class="offer-filters grid grid-cols-1 mx-auto text-center gap-4 mt-8">
        <div v-for="conditional_field in product.conditional_fields">
          <template v-if="!conditional_field.only_client_details">
            <label v-text="conditional_field.field_label"></label>
            <div v-if="conditional_field.field_type == 'select'">
              <select v-model="filter_conditions[conditional_field.id]" @change="change">
                <option
                  v-for="(option_value, option_key) in JSON.parse(
                    conditional_field.field_value
                  )"
                  :value="option_key"
                  v-text="option_value"
                  :selected="option_key == 1"
                ></option>
              </select>
            </div>
          </template>
        </div>
      </div>
    </div>
    <div class="overflow-x-auto p-1 w-full">
      <table
        class="offer-table text-primary table-auto w-full mx-auto border-collapse border border-primary shadow rounded-lg mt-8 overflow-hidden"
      >
        <thead class="rounded-lg">
          <tr class="uppercase bg-primary text-white">
            <th class="p-4 font-light"></th>
            <th v-for="table_element in JSON.parse(product.table_elements.table_elements)" class="p-4 font-light">
                {{table_element}}            
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!filteredRows.length">
            <td colspan="6" class="p-4">
              {{ __("Select a combination using filters above") }}
            </td>
          </tr>

          <!-- Prodotto MEDICI -->
          <tr
          v-if="product.id == 1 || product.id == 5"
          
            v-for="(offer, index) in filteredRows"
            
            class="odd:bg-white even:bg-slate-100 uppercase"
          >
          <template v-if="offer.hide == 0">
            <td class="p-4 border border-primary">{{ index + 1 }}</td>
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
            <td class="p-4 border border-primary" style="text-align: center; text-transform: none;">
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
                {{ __("DIP") }}
              </a>
              <p style="margin:0"></p>
              <a
                :href="offer.contract.dip_aggiuntivo"
                target="_blank"
                class="btn-gol"
              >
                {{ __("DIP_AGGIUNTIVO") }}
              </a>
            </td>
          </template>
          </tr>

          <!-- Prodotto BANCARI -->
          <template
            v-if="product.id == 2 || product.id == 3 || product.id == 4"
            v-for="(offer, index) in filteredRows"
          >
          <tr v-if="offer.hide == 0" class="odd:bg-white even:bg-slate-100 uppercase">
            <td class="p-4 border border-primary">{{ offer.combination }}</td>
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
              class="p-4 border border-primary"
              v-text="$filters.formatPrice(parseFloat(price))"
            ></td>
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
  </div>
  <div class="my-8" v-html="product.conditions"></div>
</template>

<script>
import isEqual from "lodash/isEqual";

export default {
  props: {
    product: Object,
  },
  data() {
    return {
      offers: [],
      offers_flags: {},
      filter_conditions: {},
      objectToCompare: {},
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
    bankersOffers(){
      for(let i=0; i<this.product.offers.length; i++){
        if(this.offers_flags[this.product.offers[i].combination] == null){
          this.offers.push([]);
          this.offers_flags[this.product.offers[i].combination] = "true";
        }
        this.offers[this.product.offers[i].combination-1].push(this.product.offers[i].total_payable);
      }    
      return(this.offers);
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

        //console.log(this.emptyFilter);
        
        // console.log(this.filter_conditions, this.objectToCompare);
        conditional_fields_flag = isEqual(this.objectToCompare, this.filter_conditions);
        // console.log("equal ", conditional_fields_flag);
        
        return conditional_fields_flag;
      });
    },
    change: function (evt) {
      this.$emit("change", evt);
    },
  },
};
</script>
