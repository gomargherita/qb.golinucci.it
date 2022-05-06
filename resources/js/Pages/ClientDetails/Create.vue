<template>
  <div>
    <Head title="Create ClientDetail" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-primary hover:text-secondary-400"
        :href="route('clientDetails')"
        >{{ __("Client details") }}</Link
      >
      <span class="text-gray font-medium">/</span>{{ __("Create for")
      }}<span class="ml-2">{{ user.email }}</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.user_id" :error="form.errors.user_id" type="hidden" />
        </div>
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
            v-model="form.region"
            :error="form.errors.region"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Province/State"
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
          <textarea-input
            v-model="form.note"
            :error="form.errors.note"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Note"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit"
            >{{ __("Create") }} {{ __("Client detail") }}</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    Head,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    user: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        user_id: this.$props.user.id,
        first_name: null,
        last_name: null,
        phone: null,
        address: null,
        city: null,
        region: null,
        country: null,
        postal_code: null,
        note: null,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/admin/clientDetails");
    },
  },
};
</script>
