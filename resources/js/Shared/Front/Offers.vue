<template>
  <div class="my-8">
    <div class="offer-filters">Dynamic filters to add (related only to the offers)</div>
    <div class="overflow-x-auto p-1 w-full">
      <table
        class="offer-table text-primary table-auto w-5/6 mx-auto border-collapse border border-primary shadow rounded-lg mt-8 overflow-hidden"
      >
        <thead class="rounded-lg">
          <tr class="uppercase bg-primary text-white">
            <th class="p-4 font-light"></th>
            <th class="p-4 font-light">Massimale per Sinistro/Anno</th>
            <th class="p-4 font-light">Franchigia</th>
            <th class="p-4 font-light">Retroattività</th>
            <th class="p-4 font-light">R.C. Patrimoniale</th>
            <th class="p-4 font-light">Costo Annuo</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!product.offers.length">
            <td colspan="6" class="p-4">
              {{ __("No data") }}
            </td>
          </tr>
          <tr
            v-for="(offer, index) in product.offers"
            class="odd:bg-white even:bg-slate-100"
          >
            <td class="p-4 border border-primary">{{ index }}</td>
            <td class="p-4 border border-primary">{{ offer.product_id }}</td>
            <td class="p-4 border border-primary">{{ offer.total_payable }}</td>
            <td class="p-4 border border-primary">{{ offer.price_with_tax }}</td>
            <td class="p-4 border border-primary">{{ offer.admin_fee }}</td>
            <td class="p-4 border border-primary">{{ offer.price }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: Object,
  },
};
</script>
