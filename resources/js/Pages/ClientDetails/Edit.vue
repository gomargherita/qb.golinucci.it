<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-primary hover:text-secondary-400"
        :href="route('clientDetails')"
        >{{ __("Client details") }}</Link
      >
      <span class="text-gray font-medium">/</span>
      <span>{{ form.last_name }} {{ form.first_name }}</span>
    </h1>
    <trashed-message v-if="client_detail.deleted_at" class="mb-6" @restore="restore"
      >{{ __("This user detail has been deleted") }}.</trashed-message
    >
    <div class=" bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First Name"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last Name"
          />
          <text-input
            v-model="form.fiscal_code"
            :error="form.errors.fiscal_code"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Fiscal Code"
          />
          <select-input
            v-model="form.gender"
            :error="form.errors.gender"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Gender"
          >
            <option :value="null" />
            <option value="M">M</option>
            <option value="F">F</option>
          </select-input>
          <text-input
            v-model="form.dob"
            :error="form.errors.dob"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Date of Birth"
          />
          <text-input
            v-model="form.phone"
            :error="form.errors.phone"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Phone"
          />
          <text-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Address"
          />
          <text-input
            v-model="form.city"
            :error="form.errors.city"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="City"
          />
          <text-input
            v-model="form.province"
            :error="form.errors.province"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Province"
            maxlength="2"
            onkeyup="this.value = this.value.toUpperCase();"
          />
          <select-input
            v-model="form.country"
            :error="form.errors.country"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Country"
          >
            <option :value="null" />
            <option value="IT">Italia</option>
          </select-input>
          <text-input
            v-model="form.postal_code"
            :error="form.errors.postal_code"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Postal code"
          />
          <textarea-input
            v-model="form.note"
            :error="form.errors.note"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Note"
          />
          <checkbox-input
            v-model="form.with_accident"
            :error="form.errors.with_accident"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="With accident"
            :checked="form.with_accident"
          />
          <checkbox-input
            v-model="form.flag_prev_2022"
            :error="form.errors.flag_prev_2022"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Flag Prev 2022"
            :checked="form.flag_prev_2022"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!client_detail.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Client detail") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Update") }} {{ __("Client detail") }}</loading-button
          >
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Related User") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Last Name</th>
          <th class="pb-4 pt-6 px-6">First Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
        </tr>
        <tr v-if="user" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('users.edit', user.id)"
            >
              {{ user.last_name }}
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
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.first_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="user.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    LoadingButton,
    SelectInput,
    CheckboxInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    client_detail: Object,
    user: Object,
    can: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.client_detail.first_name,
        last_name: this.client_detail.last_name,
        fiscal_code: this.client_detail.fiscal_code,
        gender: this.client_detail.gender,
        dob: this.client_detail.dob,
        phone: this.client_detail.phone,
        address: this.client_detail.address,
        city: this.client_detail.city,
        province: this.client_detail.province,
        country: this.client_detail.country,
        postal_code: this.client_detail.postal_code,
        note: this.client_detail.note,
        with_accident: this.client_detail.with_accident,
        flag_prev_2022: this.client_detail.flag_prev_2022,
      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/clientDetails/${this.client_detail.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this client_detail?")) {
        this.$inertia.delete(`/admin/clientDetails/${this.client_detail.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this client_detail?")) {
        this.$inertia.put(`/admin/clientDetails/${this.client_detail.id}/restore`);
      }
    },
  },
};
</script>
