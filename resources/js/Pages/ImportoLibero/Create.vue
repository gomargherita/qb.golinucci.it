<template>
  <div>
    <Head title="Crea prodotto" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('importolibero')">
        Importo libero
      </Link>
      <span class="text-gray font-medium">/</span>{{ __("Create") }}
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.causale"
            :error="form.errors.causale"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Causale"
          />
          <text-input
            v-model="form.importo"
            :error="form.errors.importo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Importo"
          />
          
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit"
            >{{ __("Create") }} {{ __("Product") }}</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    LoadingButton,
    TextInput,
  },
  layout: Layout,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        causale: "",
        importo: "",
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/admin/storeimportolibero");
    },
  },
};
</script>
