<template>
  <div>
    <Head title="Create Product" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('products')">{{
        __("Products")
      }}</Link>
      <span class="text-gray font-medium">/</span>{{ __("Create") }}
    </h1>

    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Name"
          />
          <select-input
            v-model="form.product_type_id"
            :error="form.errors.product_type_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Product Type"
          >
            <option
              v-for="product_type in product_types"
              :value="product_type.id"
              :selected="product_type.id == form.product_type_id"
            >
              {{ __(product_type.name) }}
            </option>
          </select-input>
          <textarea-input
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Description"
          />
          <textarea-input
            v-model="form.detailed_description"
            :error="form.errors.detailed_description"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Detailed description"
          />
          <textarea-input
            v-model="form.conditions"
            :error="form.errors.conditions"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Conditions"
          />
          <text-input
            v-model="form.hex_color"
            :error="form.errors.hex_color"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Color"
          />
          <file-input
            v-model="form.icon"
            :error="form.errors.icon"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Image"
          />
          <select-input
            v-model="form.active"
            :error="form.errors.active"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Active"
          >
            <option :value="1" :selected="form.active == 1">{{ __("Yes") }}</option>
            <option :value="0" :selected="form.active == 0">{{ __("No") }}</option>
          </select-input>
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
import FileInput from "@/Shared/FileInput";
import TextInput from "@/Shared/TextInput";
import TextareaInput from "@/Shared/TextareaInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    user: Object,
    product_types: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        product_type_id: "",
        name: "",
        description: "",
        detailed_description: "",
        conditions: "",
        pre_declarations: "",
        post_declarations: "",
        payments_description: "",
        hex_color: "",
        icon: null,
        active: 1,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/admin/products");
    },
  },
};
</script>
