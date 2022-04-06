<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-primary hover:text-secondary-400" :href="route('users')">{{
          __("Users")
        }}</Link>
        <span class="text-gray font-medium">/</span>
        <span>{{ form.first_name }} {{ form.last_name }}</span>
      </h1>
      <img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      This user has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First name"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last name"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Email"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="password"
            autocomplete="new-password"
            label="Password"
          />
          <select-input
            v-model="form.owner"
            :error="form.errors.owner"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Admin"
          >
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <file-input
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Photo"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!user.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("User") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
          >
            {{ __("Update") }} {{ __("User") }}
          </loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Client details") }}</h2>
    <div class="flex items-center justify-between mb-6">
      <div class=""></div>
      <div class="">
        <Link class="btn-gol-primary" :href="route('clientDetails.create', user.id)">
          <span>{{ __("Create") }}</span>
          <span class="hidden md:inline">&nbsp;{{ __("Client details") }}</span>
        </Link>
      </div>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ __("Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ __("City") }}</th>
          <th class="pb-4 pt-6 px-6" colspan="2">{{ __("Phone") }}</th>
        </tr>
        <tr
          v-for="clientDetail in user.client_details"
          :key="clientDetail.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('clientDetails.edit', clientDetail.id)"
            >
              {{ clientDetail.last_name }} {{ clientDetail.first_name }}
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
        <tr v-if="user.client_details.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No client details found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: "",
        owner: this.user.owner,
        photo: null,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/admin/users/${this.user.id}`, {
        onSuccess: () => this.form.reset("password", "photo"),
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this user?")) {
        this.$inertia.delete(`/admin/users/${this.user.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this user?")) {
        this.$inertia.put(`/admin/users/${this.user.id}/restore`);
      }
    },
  },
};
</script>
