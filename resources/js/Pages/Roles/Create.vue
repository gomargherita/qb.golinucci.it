<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('roles')">Roles</Link>
      <span class="text-gray font-medium">/</span> Create
    </h1>
    <div class=" bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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
          <select-input
            v-model="form.client_detail_id"
            :error="form.errors.client_detail_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="ClientDetail"
          >
            <option :value="null" />
            <option
              v-for="client_detail in clientDetails"
              :key="client_detail.id"
              :value="client_detail.id"
            >
              {{ client_detail.name }}
            </option>
          </select-input>
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Email"
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
          />
          <select-input
            v-model="form.country"
            :error="form.errors.country"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Country"
          >
            <option :value="null" />
            <option value="CA">Canada</option>
            <option value="US">United States</option>
          </select-input>
          <text-input
            v-model="form.postal_code"
            :error="form.errors.postal_code"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Postal code"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit"
            >Create Contact</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    clientDetails: Array,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        client_detail_id: null,
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        country: "",
        postal_code: "",
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/admin/roles");
    },
  },
};
</script>
