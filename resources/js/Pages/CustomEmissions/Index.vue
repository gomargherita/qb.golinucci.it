<template>
  <div>
    <Head title="Emissione personalizzata" />
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-3xl font-bold">Emissione personalizzata</h1>
      <Link class="btn-gol-primary" :href="route('customEmissions.create')">Nuova bozza</Link>
    </div>
    <p class="mb-4 text-sm text-gray-600">
      Bozze per polizze non standard. Il salvataggio non invia nulla a Lloyd's.
    </p>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Prodotto</th>
            <th class="pb-4 pt-6 px-6">Contraente</th>
            <th class="pb-4 pt-6 px-6">Effetto</th>
            <th class="pb-4 pt-6 px-6">Scadenza</th>
            <th class="pb-4 pt-6 px-6">Stato</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="emissions.length === 0">
            <td class="border-t px-6 py-4 text-gray-500" colspan="5">Nessuna bozza.</td>
          </tr>
          <tr v-for="row in emissions" :key="row.id" class="hover:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="route('customEmissions.show', row.id)">{{ row.product_name }}</Link>
            </td>
            <td class="border-t px-6 py-4">{{ row.holder }}</td>
            <td class="border-t px-6 py-4">{{ row.start_date }}</td>
            <td class="border-t px-6 py-4">{{ row.end_date }}</td>
            <td class="border-t px-6 py-4">{{ row.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";

export default {
  components: { Head, Link },
  layout: Layout,
  props: { emissions: Array },
};
</script>
