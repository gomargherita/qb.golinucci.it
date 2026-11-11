<template>
  <div>
    <Head :title="emission.product_name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('customEmissions')">Emissione personalizzata</Link>
      <span class="text-gray font-medium">/</span>
      <span>{{ emission.product_name }}</span>
    </h1>
    <p class="mb-6 text-sm text-gray-700">
      Bozza {{ emission.id }}, stato {{ emission.status }}. Questa pagina non ha inviato la pratica a Lloyd's.
    </p>
    <div class="bg-white rounded-md shadow p-8 mb-6">
      <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div><dt class="text-gray-500">Riferimento coverholder</dt><dd>{{ emission.coverholder_ref }}</dd></div>
        <div><dt class="text-gray-500">Tipo di contratto</dt><dd>{{ emission.contract_type }}</dd></div>
        <div><dt class="text-gray-500">Versione testo</dt><dd>{{ emission.policy_text_version }}</dd></div>
        <div><dt class="text-gray-500">Contraente</dt><dd>{{ emission.first_name }} {{ emission.last_name }}</dd></div>
        <div><dt class="text-gray-500">Codice fiscale</dt><dd>{{ emission.fiscal_code }}</dd></div>
        <div><dt class="text-gray-500">Effetto / scadenza</dt><dd>{{ emission.start_date }} / {{ emission.end_date }}</dd></div>
        <div><dt class="text-gray-500">Premio netto</dt><dd>{{ emission.premium_net }}</dd></div>
        <div><dt class="text-gray-500">Accessori</dt><dd>{{ emission.premium_accessories }}</dd></div>
        <div><dt class="text-gray-500">Tasse</dt><dd>{{ emission.premium_tax }}</dd></div>
        <div><dt class="text-gray-500">Lordo</dt><dd>{{ emission.premium_gross }}</dd></div>
      </dl>
      <ul class="mt-6">
        <li v-for="file in emission.files" :key="file.label">
          <a class="text-primary" :href="file.url">{{ file.label }}</a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";

export default {
  components: { Head, Link },
  layout: Layout,
  props: { emission: Object },
};
</script>
