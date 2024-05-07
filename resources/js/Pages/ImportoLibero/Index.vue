<template>
  <div>
      <Head title="Importo libero" />
      <h1 class="mb-8 text-3xl font-bold">Importo libero</h1>
    
      <div class="flex items-center justify-between mb-6">
      <Link class="btn-gol-primary" :href="route('importolibero.create')">
        Crea prodotto
      </Link>
      </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">

      

      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Causale</th>
            <th class="pb-4 pt-6 px-6">Importo</th>
            <th class="pb-4 pt-6 px-6">URL</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="prodotto in prodottiImportoLibero"
            :key="prodotto.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('front.importoLiberoPreimpostato', [prodotto.id])"
              >
                {{ prodotto.causal }}
                
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('front.importoLiberoPreimpostato', [prodotto.id])"
              >
                {{ prodotto.total_payable }}€
                
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('front.importoLiberoPreimpostato', [prodotto.id])"
              >
                https://qb.golinucci.it/paga-online/{{ prodotto.id }}
                
              </Link>
            </td>
            
          </tr>
          <tr v-if="prodottiImportoLibero.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">
              Nessun prodotto presente.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import Pagination from "@/Shared/Pagination";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    prodottiImportoLibero: Array,
  },
};
</script>
