<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-8 text-3xl font-bold">{{ __("Users") }}</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Role:</label>
        <select v-model="form.role" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="user">User</option>
          <option value="owner">Admin</option>
        </select>
        <label class="block mt-4 text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-gol-primary" href="/admin/users/create">
        <span>{{ __("Create") }}</span>
        <span class="hidden md:inline">&nbsp;{{ __("User") }}</span>
      </Link>
    </div>
    <pagination class="my-6" :links="users.links" />
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Email</th>
          <th class="pb-4 pt-6 px-6">Account</th>
          <th class="pb-4 pt-6 px-6">Role</th>
        </tr>
        <tr
          v-for="user in users.data"
          :key="user.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="`/admin/users/${user.id}/edit`"
            >
              <img
                v-if="user.photo"
                class="block -my-2 mr-2 w-5 h-5 rounded-full"
                :src="user.photo"
              />
              {{ user.name }}
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
              :href="`/admin/users/${user.id}/edit`"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/admin/users/${user.id}/edit`"
              tabindex="-1"
            >
              {{ user.account }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/admin/users/${user.id}/edit`"
              tabindex="-1"
            >
              {{ user.owner ? "Admin" : "User" }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="`/admin/users/${user.id}/edit`"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="users.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">{{ __("No users found") }}.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="users.links" />
  </div>
</template>

<script>
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout";
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
    users: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/admin/users", pickBy(this.form), {
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
