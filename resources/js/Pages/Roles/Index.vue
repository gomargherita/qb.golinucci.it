<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-8 text-3xl font-bold">{{ __("Roles") }}</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        :withDropdown="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
      </search-filter>
      <Link class="btn-gol-primary" href="/admin/roles/create">
        <span>{{ __("Create") }}</span
        ><span class="hidden md:inline ml-1">{{ __("Role") }}</span>
      </Link>
    </div>
    <pagination class="my-6" :links="roles.links" />
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ __("Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ __("Guard Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ __("Account") }}</th>
        </tr>
        <tr
          v-for="role in roles.data"
          :key="role.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="`/admin/roles/${role.id}/edit`"
            >
              {{ role.name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/admin/roles/${role.id}/edit`"
              tabindex="-1"
            >
              {{ role.guard_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/admin/roles/${role.id}/edit`"
              tabindex="-1"
            >
              {{ role.account }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="`/admin/roles/${role.id}/edit`"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="roles.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">{{ __("No roles found") }}.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="roles.links" />
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    roles: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/admin/roles", pickBy(this.form), {
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
