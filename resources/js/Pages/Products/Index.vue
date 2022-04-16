<template>
  <div>
    <Head title="Products" />
    <h1 class="mb-8 text-3xl font-bold">{{ __("Products") }}</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-gol-primary" :href="route('products.create')">
        <span>{{ __("Create") }}</span>
        <span class="hidden md:inline">&nbsp;{{ __("Product") }}</span>
      </Link>
    </div>
    <pagination class="my-6" :links="products.links" />
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ __("Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ __("Type") }}</th>
        </tr>
        <tr
          v-for="product in products.data"
          :key="product.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('products.edit', product.id)"
            >
              {{ product.name }}
              <icon
                v-if="product.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('products.edit', product.id)"
              tabindex="-1"
            >
              {{ product.type }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('products.edit', product.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="products.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">{{ __("No products found") }}.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="products.links" />
  </div>
</template>

<script>
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layouts/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    products: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/admin/products", pickBy(this.form), {
          preserveState: true,
          replace: true,
        });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
