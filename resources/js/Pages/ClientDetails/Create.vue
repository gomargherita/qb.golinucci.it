<template>
  <div>
    <Head title="Create ClientDetail" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-primary hover:text-secondary-400"
        :href="route('clientDetails')"
        >{{ __("Client detail") }}</Link
      >
      <span class="text-gray font-medium">/</span>{{ __("Create") }}
    </h1>
    <p class="ml-2">per l'utente {{ user.email }}</p>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First Name"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last Name"
          />
          <text-input
            v-model="form.fiscal_code"
            :error="form.errors.fiscal_code"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Fiscal Code"
          />
          <select-input
            v-model="form.gender"
            :error="form.errors.gender"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Gender"
          >
            <option :value="null" />
            <option value="M">M</option>
            <option value="F">F</option>
          </select-input>
          <text-input
            v-model="form.dob"
            :error="form.errors.dob"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Date of Birth"
            type="date"
          />
          <text-input
            v-model="form.phone"
            :error="form.errors.phone"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Phone"
            type="tel"
          />
          <text-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Address"
          />
          <text-input
            v-model="form.city"
            :error="form.errors.city"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="City"
          />
          <select-input
            v-model="form.province"
            :error="form.errors.province"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Province"
          >
            <option :value="null" />
            <option value="AG">AGRIGENTO</option>
            <option value="AL">ALESSANDRIA</option>
            <option value="AN">ANCONA</option>
            <option value="AO">AOSTA</option>
            <option value="AR">AREZZO</option>
            <option value="AP">ASCOLI PICENO</option>
            <option value="AT">ASTI</option>
            <option value="AV">AVELLINO</option>
            <option value="BA">BARI</option>
            <option value="BT">BARLETTA-ANDRIA-TRANI</option>
            <option value="BL">BELLUNO</option>
            <option value="BN">BENEVENTO</option>
            <option value="BG">BERGAMO</option>
            <option value="BI">BIELLA</option>
            <option value="BO">BOLOGNA</option>
            <option value="BZ">BOLZANO</option>
            <option value="BS">BRESCIA</option>
            <option value="BR">BRINDISI</option>
            <option value="CA">CAGLIARI</option>
            <option value="CL">CALTANISSETTA</option>
            <option value="CB">CAMPOBASSO</option>
            <option value="CI">CARBONIA-IGLESIAS</option>
            <option value="CE">CASERTA</option>
            <option value="CT">CATANIA</option>
            <option value="CZ">CATANZARO</option>
            <option value="CH">CHIETI</option>
            <option value="CO">COMO</option>
            <option value="CS">COSENZA</option>
            <option value="CR">CREMONA</option>
            <option value="KR">CROTONE</option>
            <option value="CN">CUNEO</option>
            <option value="EN">ENNA</option>
            <option value="FM">FERMO</option>
            <option value="FE">FERRARA</option>
            <option value="FI">FIRENZE</option>
            <option value="FG">FOGGIA</option>
            <option value="FC">FORLI'-CESENA</option>
            <option value="FR">FROSINONE</option>
            <option value="GE">GENOVA</option>
            <option value="GO">GORIZIA</option>
            <option value="GR">GROSSETO</option>
            <option value="IM">IMPERIA</option>
            <option value="IS">ISERNIA</option>
            <option value="SP">LA SPEZIA</option>
            <option value="AQ">L'AQUILA</option>
            <option value="LT">LATINA</option>
            <option value="LE">LECCE</option>
            <option value="LC">LECCO</option>
            <option value="LI">LIVORNO</option>
            <option value="LO">LODI</option>
            <option value="LU">LUCCA</option>
            <option value="MC">MACERATA</option>
            <option value="MN">MANTOVA</option>
            <option value="MS">MASSA-CARRARA</option>
            <option value="MT">MATERA</option>
            <option value="VS">MEDIO CAMPIDANO</option>
            <option value="ME">MESSINA</option>
            <option value="MI">MILANO</option>
            <option value="MO">MODENA</option>
            <option value="MB">MONZA E BRIANZA</option>
            <option value="NA">NAPOLI</option>
            <option value="NO">NOVARA</option>
            <option value="NU">NUORO</option>
            <option value="OG">OGLIASTRA</option>
            <option value="OT">OLBIA-TEMPIO</option>
            <option value="OR">ORISTANO</option>
            <option value="PD">PADOVA</option>
            <option value="PA">PALERMO</option>
            <option value="PR">PARMA</option>
            <option value="PV">PAVIA</option>
            <option value="PG">PERUGIA</option>
            <option value="PU">PESARO E URBINO</option>
            <option value="PE">PESCARA</option>
            <option value="PC">PIACENZA</option>
            <option value="PI">PISA</option>
            <option value="PT">PISTOIA</option>
            <option value="PN">PORDENONE</option>
            <option value="PZ">POTENZA</option>
            <option value="PO">PRATO</option>
            <option value="RG">RAGUSA</option>
            <option value="RA">RAVENNA</option>
            <option value="RC">REGGIO CALABRIA</option>
            <option value="RE">REGGIO EMILIA</option>
            <option value="RI">RIETI</option>
            <option value="RN">RIMINI</option>
            <option value="RM">ROMA</option>
            <option value="RO">ROVIGO</option>
            <option value="SA">SALERNO</option>
            <option value="SS">SASSARI</option>
            <option value="SV">SAVONA</option>
            <option value="SI">SIENA</option>
            <option value="SR">SIRACUSA</option>
            <option value="SO">SONDRIO</option>
            <option value="TA">TARANTO</option>
            <option value="TE">TERAMO</option>
            <option value="TR">TERNI</option>
            <option value="TO">TORINO</option>
            <option value="TP">TRAPANI</option>
            <option value="TN">TRENTO</option>
            <option value="TV">TREVISO</option>
            <option value="TS">TRIESTE</option>
            <option value="UD">UDINE</option>
            <option value="VA">VARESE</option>
            <option value="VE">VENEZIA</option>
            <option value="VB">VERBANO-CUSIO-OSSOLA</option>
            <option value="VC">VERCELLI</option>
            <option value="VR">VERONA</option>
            <option value="VV">VIBO VALENTIA</option>
            <option value="VI">VICENZA</option>
            <option value="VT">VITERBO</option>
          </select-input>
          <select-input
            v-model="form.country"
            :error="form.errors.country"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Country"
          >
            <option :value="null" />
            <option value="IT">Italia</option>
          </select-input>
          <text-input
            v-model="form.postal_code"
            :error="form.errors.postal_code"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Postal code"
          />
          <textarea-input
            v-model="form.note"
            :error="form.errors.note"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Note"
          />
          <checkbox-input
            v-model="form.with_accident"
            :error="form.errors.with_accident"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="With accident"
            :checked="form.with_accident"
          />
          <checkbox-input
            v-model="form.flag_prev_2022"
            :error="form.errors.flag_prev_2022"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Flag Prev 2022"
            :checked="form.flag_prev_2022"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit"
            >{{ __("Create") }} {{ __("Client detail") }}</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    Head,
    LoadingButton,
    SelectInput,
    CheckboxInput,
    TextareaInput,
    TextInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    user: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        user_id: this.user.id,
        first_name: null,
        last_name: null,
        fiscal_code: null,
        gender: null,
        dob: null,
        phone: null,
        address: null,
        city: null,
        province: null,
        country: null,
        postal_code: null,
        note: null,
        with_accident: false,
        flag_prev_2022: false,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/admin/clientDetails");
    },
  },
};
</script>
