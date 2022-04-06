<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('roles')">{{
        __("Roles")
      }}</Link>
      <span class="text-gray font-medium">/</span>
      <span>{{ form.name }}</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Name"
          />
          <text-input
            v-model="form.guard_name"
            :error="form.errors.guard_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Guard name"
          />
          <select-input
            v-model="form.account_id"
            :error="form.errors.account_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Account"
          >
            <option :value="null" />
            <option v-for="account in accounts" :key="account.id" :value="account.id">
              {{ account.name }}
            </option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Role") }} 
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Update") }} {{ __("Role") }} </loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    role: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        name: this.role.name,
        guard_name: this.role.guard_name,
        account_id: this.role.account_id,
      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/roles/${this.role.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this role?")) {
        this.$inertia.delete(`/admin/roles/${this.role.id}`);
      }
    },
  },
};
</script>
