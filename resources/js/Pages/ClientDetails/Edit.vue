<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-primary hover:text-secondary-400"
        :href="route('clientDetails')"
        >{{ __("Client details") }}</Link
      >
      <span class="text-gray font-medium">/</span>
      <span>{{ form.last_name }} {{ form.first_name }}</span>
    </h1>
    <trashed-message v-if="client_detail.deleted_at" class="mb-6" @restore="restore"
      >{{ __("This user detail has been deleted") }}.</trashed-message
    >
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
            pattern="\d{4}-\d{2}-\d{2}"
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
          <text-input
            v-model="form.contact_ref"
            :error="form.errors.contact_ref"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Contact ref"
          />
          <text-input
            v-model="form.version_ref"
            :error="form.errors.version_ref"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Version ref"
          />
          <textarea-input
            v-model="form.note"
            :error="form.errors.note"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Note"
          />
          <h1 class="mb-8 text-3xl font-bold w-full">Medici</h1>
          <select-input
            v-model="form.field_value[0]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Qualifica dell'Assicurato"
          >
            <option :value="null" />
            <option value="1">Dirigente Medico</option>
            <option value="2">Medico Convenzionato</option>
            <option value="3">Medico Specialista in Formazione</option>
            <option value="4">Personale sanitario</option>
            <option value="5">Dirigente Sanitario non Medico</option>
            <option value="6">Dirigente Infermieristico</option>
          </select-input>
          <select-input
            v-model="form.field_value[1]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Tipologia ente"
          >
            <option :value="null" />
            <option value="1">Pubblico</option>
            <option value="2">Privato-Pubblico</option>
          </select-input>
          <text-input
            v-model="form.field_value[2]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Ente di appartenenza"
          />
          <text-input
            v-model="form.field_value[3]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Specializzazione"
          />
          <h1 class="mb-8 text-3xl font-bold w-full">Bancari</h1>
          <text-input
            v-model="form.field_value[4]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Telefono Ufficio"
          />
          <text-input
            v-model="form.field_value[5]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First/Cisl di appartenenza"
          />
          <text-input
            v-model="form.field_value[6]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Banca"
          />
          <text-input
            v-model="form.field_value[7]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Codice ABI"
          />
          <select-input
            v-model="form.field_value[8]"
            :error="form.errors.field_value"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Regione di appartenenza"
          >
            <option :value="null" />
            <option value="1">Abruzzo</option>
            <option value="2">Basilicata</option>
            <option value="3">Calabria</option>
            <option value="4">Campania</option>
            <option value="5">Friuli Venezia Giulia</option>
            <option value="6">Lazio</option>
            <option value="7">Liguria</option>
            <option value="8">Lombardia</option>
            <option value="9">Marche</option>
            <option value="10">Molise</option>
            <option value="11">Piemonte</option>
            <option value="12">Puglia</option>
            <option value="13">Sardegna</option>
            <option value="14">Sicilia</option>
            <option value="15">Toscana</option>
            <option value="16">Trentino Alto Adige</option>
            <option value="17">Umbria</option>
            <option value="18">Val d'Aosta</option>
            <option value="19">Veneto</option>
          </select-input>
          <select-input
            v-model="form.motivo_blocco_invio"
            :error="form.errors.with_accident"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Blocca avviso"
          >
          <option value="Avvisi attivi">Avvisi attivi</option>
          <option value="Avvisi bloccati, nessun motivo specificato">Avvisi bloccati, nessun motivo specificato</option>
          <option value="Attivata postuma">Attivata postuma</option>
          <option value="Emissione fuori sistema">Emissione fuori sistema</option>
          <option value="Referral">Referral</option>
          <option value="Errato/Doppio caricamento">Errato/Doppio caricamento</option>
          <option value="Disdetta">Disdetta</option>
        </select-input>



          <checkbox-input
            v-model="form.flag_prev_2022"
            :error="form.errors.flag_prev_2022"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Flag Prev 2022"
            :checked="form.flag_prev_2022"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!client_detail.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Client detail") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Update") }} {{ __("Client detail") }}</loading-button
          >
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Related User") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Last Name</th>
          <th class="pb-4 pt-6 px-6">First Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
        </tr>
        <tr v-if="user" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('users.edit', user.id)"
            >
              {{ user.last_name }}
              <icon
                v-if="user.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.first_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="user.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
        </tr>
      </table>
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
    client_detail: Object,
    user: Object,
    can: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.client_detail.first_name,
        last_name: this.client_detail.last_name,
        fiscal_code: this.client_detail.fiscal_code,
        gender: this.client_detail.gender,
        dob: this.client_detail.dob,
        phone: this.client_detail.phone,
        address: this.client_detail.address,
        city: this.client_detail.city,
        province: this.client_detail.province,
        country: this.client_detail.country,
        postal_code: this.client_detail.postal_code,
        contact_ref: this.client_detail.contact_ref,
        version_ref: this.client_detail.version_ref,
        note: this.client_detail.note,
        with_accident: this.client_detail.with_accident,
        motivo_blocco_invio: this.client_detail.motivo_blocco_invio,
        flag_prev_2022: this.client_detail.flag_prev_2022,
        client_detail_id: this.client_detail.client_detail_id,
        conditional_field_id: this.client_detail.conditional_field_id,
        selected_field_value: this.client_detail.selected_field_value,
        field_value: this.client_detail.field_value,
        /*selected_field_value_0: this.client_detail.selected_field_value[0],
        selected_field_value_1: this.client_detail.selected_field_value[1],
        selected_field_value_2: this.client_detail.selected_field_value[2],
        selected_field_value_3: this.client_detail.selected_field_value[3],
        selected_field_value_4: this.client_detail.selected_field_value[4],
        selected_field_value_5: this.client_detail.selected_field_value[5],
        selected_field_value_6: this.client_detail.selected_field_value[6],
        selected_field_value_7: this.client_detail.selected_field_value[7],
        selected_field_value_8: this.client_detail.selected_field_value[8],*/

      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/clientDetails/${this.client_detail.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this client_detail?")) {
        this.$inertia.delete(`/admin/clientDetails/${this.client_detail.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this client_detail?")) {
        this.$inertia.put(`/admin/clientDetails/${this.client_detail.id}/restore`);
      }
    },
  },
};
</script>