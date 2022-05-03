<template>
  <div class="mt-8">
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
            <td class="p-4 border border-primary" v-text="$filters.formatPrice(offer.indemnity_limit)"></td>
            <td class="p-4 border border-primary" v-text="$filters.formatPrice(1000000)"></td>
            <!-- TODO Massimale RC Patrimoniale solo per i rinnovi 1.000.000, i nuovi è 500.000 -->
            <td class="p-4 border border-primary">Zero</td>
            <td class="p-4 border border-primary" v-text="$filters.formatPrice(parseFloat(offer.total_payable))"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="my-8">
    <p>
      La polizza è aggiornata alle disposizioni contenute nella Legge 24/17 (Legge Gelli),
      prevede:
    </p>
    <ol class="list-decimal list-inside">
      <li>
        <b>Apertura del sinistro fin dalla ricezione di comunicazione</b> da parte
        dell’Azienda di appartenenza, riguardante l’inizio delle trattative stragiudiziali
        con il paziente ai sensi dell’<b>art. 13 della Legge Gelli</b>, con maggiore
        tutela dell’assicurato in quanto non deve aspettare anni per dare comunicazione
        dell’eventuale ricezione di un “invito a dedurre” da parte della Corte dei Conti o
        altro tribunale competente.
      </li>
      <li>
        <b>Copertura per i danni di natura patrimoniale</b> (non lesioni a persona ma di
        natura “immateriale”) <b>inclusa la violazione privacy</b>.
      </li>
      <li>
        <b>Continuità assicurativa</b>: l’effettiva retroattività della copertura è di 10
        anni + i precedenti anni di copertura con Golinucci Srl.
      </li>
      <li>
        Previsione di una <b>copertura “postuma” di ultra attività di 10 anni</b>, da
        attivare alla cessazione del rapporto di lavoro dipendente.
      </li>
    </ol>
  </div>
</template>

<script>
import isEqual from "lodash/isEqual";

export default {
  props: {
    product: Object,
    conditions: Array,
  },
  data() {
    return {
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
      this.$emit("change", evt);
    },
  },
};
</script>
