<template>
  <div>
    <Head title="Nuova emissione personalizzata" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('customEmissions')">Emissione personalizzata</Link>
      <span class="text-gray font-medium">/</span>
      <span>Nuova bozza</span>
    </h1>
    <p class="mb-4 text-sm text-gray-600">Il salvataggio tiene la bozza in QB. Non parte nessuna chiamata a Lloyd's.</p>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.coverholder_ref" :error="form.errors.coverholder_ref" class="pb-8 pr-6 w-full lg:w-1/2" label="Riferimento stampabile del coverholder" />
          <text-input v-model="form.contract_type" :error="form.errors.contract_type" class="pb-8 pr-6 w-full lg:w-1/2" label="Tipo di contratto" />
          <text-input v-model="form.product_name" :error="form.errors.product_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Nome del prodotto" />
          <text-input v-model="form.policy_text_version" :error="form.errors.policy_text_version" class="pb-8 pr-6 w-full lg:w-1/2" label="Versione del testo della polizza" />
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Nome" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Cognome" />
          <text-input v-model="form.fiscal_code" :error="form.errors.fiscal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Codice fiscale" />
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label">Sesso:</label>
            <select v-model="form.gender" class="form-input" :class="{ error: form.errors.gender }">
              <option value="M">M</option>
              <option value="F">F</option>
            </select>
            <div v-if="form.errors.gender" class="form-error">{{ form.errors.gender }}</div>
          </div>
          <text-input v-model="form.dob" :error="form.errors.dob" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Data di nascita" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Telefono" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full" label="Indirizzo" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Comune" />
          <text-input v-model="form.province" :error="form.errors.province" class="pb-8 pr-6 w-full lg:w-1/4" label="Provincia" />
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/4" label="CAP" />
          <text-input v-model="form.country" :error="form.errors.country" class="pb-8 pr-6 w-full lg:w-1/4" label="Nazione" />
          <text-input v-model="form.start_date" :error="form.errors.start_date" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Data effetto" />
          <text-input v-model="form.end_date" :error="form.errors.end_date" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Data scadenza" />
          <text-input v-model="form.premium_net" :error="form.errors.premium_net" class="pb-8 pr-6 w-full lg:w-1/4" label="Premio netto" />
          <text-input v-model="form.premium_accessories" :error="form.errors.premium_accessories" class="pb-8 pr-6 w-full lg:w-1/4" label="Accessori" />
          <text-input v-model="form.premium_tax" :error="form.errors.premium_tax" class="pb-8 pr-6 w-full lg:w-1/4" label="Tasse" />
          <text-input v-model="form.premium_gross" :error="form.errors.premium_gross" class="pb-8 pr-6 w-full lg:w-1/4" label="Premio lordo" />
          <div v-for="file in files" :key="file.key" class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label">{{ file.label }}:</label>
            <input class="form-input" type="file" accept="application/pdf" @change="setFile(file.key, $event)" />
            <div v-if="form.errors[file.key]" class="form-error">{{ form.errors[file.key] }}</div>
          </div>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit">Salva bozza</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: { Head, Link, TextInput, LoadingButton },
  layout: Layout,
  data() {
    return {
      files: [
        { key: "policy_schedule", label: "Scheda di polizza (PDF)" },
        { key: "wording", label: "Condizioni (PDF)" },
        { key: "attached_clauses", label: "Clausole allegate (PDF)" },
        { key: "warranty_statement", label: "Questionario (PDF)" },
      ],
      form: this.$inertia.form({
        coverholder_ref: "",
        contract_type: "",
        product_name: "",
        policy_text_version: "",
        first_name: "",
        last_name: "",
        fiscal_code: "",
        gender: "M",
        dob: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        country: "IT",
        postal_code: "",
        start_date: "",
        end_date: "",
        premium_net: "",
        premium_accessories: "",
        premium_tax: "",
        premium_gross: "",
        policy_schedule: null,
        wording: null,
        attached_clauses: null,
        warranty_statement: null,
      }),
    };
  },
  methods: {
    setFile(key, event) {
      this.form[key] = event.target.files[0] || null;
    },
    submit() {
      this.form.post(this.route("customEmissions.store"), { forceFormData: true });
    },
  },
};
</script>
