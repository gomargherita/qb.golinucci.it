<template>
  <Head :title="__('Client detail')" />
  <HTwo :title="__('Client detail')" />

  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
      <div>
        <TextInput
          id="first_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.first_name"
          :error="form.errors.first_name"
          label="First Name"
          autofocus
        />
      </div>

      <div>
        <TextInput
          id="last_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.last_name"
          :error="form.errors.last_name"
          label="Last Name"
        />
      </div>

      <div>
        <TextInput
          id="fiscal_code"
          type="text"
          class="mt-1 block w-full"
          v-model="form.fiscal_code"
          :error="form.errors.fiscal_code"
          label="Fiscal Code"
        />
      </div>

      <div>
        <SelectInput
          id="gender"
          v-model="form.gender"
          :error="form.errors.gender"
          class="mt-1 block w-full"
          label="Gender"
        >
          <option :value="null" />
          <option value="M">M</option>
          <option value="F">F</option>
        </SelectInput>
      </div>

      <div>
        <TextInput
          id="dob"
          type="date"
          class="mt-1 block w-full"
          v-model="form.dob"
          :error="form.errors.dob"
          label="Date of Birth"
        />
      </div>

      <div>
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full"
          v-model="form.address"
          :error="form.errors.address"
          label="Address"
        />
      </div>

      <div>
        <TextInput
          id="city"
          type="text"
          class="mt-1 block w-full"
          v-model="form.city"
          :error="form.errors.city"
          label="City"
        />
      </div>

      <div>
        <SelectInput
          id="province"
          v-model="form.province"
          :error="form.errors.province"
          class="mt-1 block w-full"
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
        </SelectInput>
      </div>
      <div>
        <TextInput
          id="postal_code"
          type="text"
          class="mt-1 block w-full"
          v-model="form.postal_code"
          :error="form.errors.postal_code"
          label="Postal Code"
        />
      </div>

      <div>
        <TextInput
          id="phone"
          type="tel"
          class="mt-1 block w-full"
          v-model="form.phone"
          :error="form.errors.phone"
          label="Phone"
        />
        <span class="italic text-gray-700 text-sm">Attenzione: è obbligatorio inserire un numero di cellulare valido per procedere alla firma elettronica, altrimenti sarà impossibile completare l'operazione.</span>
      </div>

      <div class="md:col-span-2">
        <p class="my-4 font-bold">Campi per preselezionare l'offerta di prodotto</p>
      </div>
      <template v-if="product.conditional_fields.length">
        <div
          v-for="(conditional_field, index) in product.conditional_fields"
          :key="index"
        >
          <div v-if="conditional_field.field_type == 'select'">
            <SelectInput
              :id="conditional_field.field_name"
              class="mt-1 block w-full"
              :label="conditional_field.field_label"
              :error="form.errors.product_client_details"
              v-model="form.product_client_details[index].pivot.selected_field_value"
            >
              <option
                v-for="(option_value, option_key) in JSON.parse(
                  conditional_field.field_value
                )"
                :value="option_key"
                v-text="option_value"
                :selected="option_key == 1"
              ></option>
            </SelectInput>
          </div>
          <div v-if="conditional_field.field_type == 'text'">
            <TextInput
              :id="conditional_field.field_name"
              :label="conditional_field.field_label"
              :error="form.errors.product_client_details"
              v-model="form.product_client_details[index].pivot.selected_field_value"
            />
          </div>
        </div>
      </template>
      <div class="md:col-span-2">
        <div class="my-2 text-sm" v-html="product.client_details_declaration"></div>
        <label class="flex items-center mt-6 select-none" for="confirmation">
          <input
            id="confirmation"
            v-model="form.confirmation"
            class="mr-4 p-3"
            type="checkbox"
          />
          <span class="text-lg text-red-500">{{
            __("I confirm the correctness of the above data")
          }}</span>
        </label>
        <label v-if="product.id == 4" class="flex items-center mt-6 select-none" for="confirmation-sinistri">
          <SelectInput
          id="confirmation_sinistri"
          v-model="form.confirmation_sinistri"
          :error="form.errors.confirmation"
          class="mt-1 block w-full"
          label="Presenza di sinistri"
        >
          <option value="si">Sì</option>
          <option value="no">No</option>
          
        </SelectInput>
        </label>
      </div>
    </div>
    <div>
      <BreezeValidationErrors class="my-4" />
    </div>
    <div>
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
</template>

<script>
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";
import Layout from "@/Shared/Layouts/EmptyLayout.vue"
import TextInput from "@/Shared/TextInput.vue";
import SelectInput from "@/Shared/SelectInput.vue";
import HTwo from "@/Shared/Front/HTwo";

export default {
  components: {
    BuyLoadingButton,
    BreezeValidationErrors,
    TextInput,
    SelectInput,
    HTwo,
  },
  props: {
    cart: Object,
    product: Object,
  },
  data() {
    
    return {
      form: this.$inertia.form({
        user_id: this.$page.props.auth.user.id,
        first_name: this.$page.props.clientDetails.first_name,
        last_name: this.$page.props.clientDetails.last_name,
        fiscal_code: this.$page.props.clientDetails.fiscal_code,
        gender: this.$page.props.clientDetails.gender,
        dob: this.$page.props.clientDetails.dob,
        address: this.$page.props.clientDetails.address,
        city: this.$page.props.clientDetails.city,
        province: this.$page.props.clientDetails.province,
        postal_code: this.$page.props.clientDetails.postal_code,
        country: "IT",
        phone: this.$page.props.clientDetails.phone,
        product_client_details: this.$page.props.clientDetails.product_client_details,
        confirmation: false,
        confirmation_sinistri: (this.product.id == 4) ? "si" : "no",
      }),
    };
  },
  computed: {
    we(){
      console.log("ecco i product client details");
      console.log(this.$page.props.clientDetails.product_client_details);
    }
  },
  methods: {
    submit() {
      this.form.post(
        this.route("front.product.store_step1", {
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
