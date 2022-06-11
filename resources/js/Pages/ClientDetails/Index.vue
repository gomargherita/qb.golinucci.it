<template>
  <div>
    <Head title="Client details" />
    <h1 class="mb-8 text-3xl font-bold">{{ __("Client details") }}</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">{{ __("Accidents") }}:</label>
        <select v-model="form.with_accident" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="no">{{ __("Without") }} {{ __("Accident") }}</option>
          <option value="yes">{{ __("With") }} {{ __("Accident") }}</option>
        </select>
        <label class="block text-gray-700">{{ __("Trashed") }}:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __("With Trashed") }}</option>
          <option value="only">{{ __("Only Trashed") }}</option>
        </select>
      </search-filter>
    </div>
    <pagination class="mb-6" :links="clientDetails.links" />
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">{{ __("Name") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("City") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Phone") }}</th>
            <th class="pb-4 pt-6 px-6" colspan="2">{{ __("User") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="clientDetail in clientDetails.data"
            :key="clientDetail.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('clientDetails.edit', clientDetail.id)"
              >
                {{ clientDetail.name }}
                <icon
                  v-if="clientDetail.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('clientDetails.edit', clientDetail.id)"
                tabindex="-1"
              >
                {{ clientDetail.city }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('clientDetails.edit', clientDetail.id)"
                tabindex="-1"
              >
                {{ clientDetail.phone }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 bg-orange-100"
                :href="route('clientDetails.edit', clientDetail.id)"
                tabindex="-1"
              >
                {{ clientDetail.user.name }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="route('clientDetails.edit', clientDetail.id)"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="clientDetails.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">
              {{ __("No user details found") }}.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="clientDetails.links" />
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
    clientDetails: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        with_accident: this.filters.accident,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/admin/clientDetails", pickBy(this.form), {
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
