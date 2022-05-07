<template>
  <div>
    <Head :title="`${form.name} ${form.last_name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-primary hover:text-secondary-400" :href="route('products')">{{
          __("Products")
        }}</Link>
        <span class="text-gray font-medium">/</span>
        <span>{{ form.name }}</span>
      </h1>
    </div>
    <trashed-message v-if="product.deleted_at" class="mb-6" @restore="restore">
      {{ __("This product has been deleted") }}.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
          <editor
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
            type="hex_color"
            autocomplete="new-hex_color"
            label="Color"
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
          <!-- <file-input
            v-model="form.icon"
            :error="form.errors.icon"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Image"
          /> -->
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!product.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Product") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
          >
            {{ __("Update") }} {{ __("Product") }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import Editor from "@/Shared/Editor";

export default {
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
    Editor,
  },
  layout: Layout,
  props: {
    product: Object,
    product_types: Array,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        name: this.product.name,
        product_type_id: this.product.product_type_id,
        description: this.product.description,
        detailed_description: this.product.detailed_description,
        conditions: this.product.conditions,
        hex_color: this.product.hex_color,
        //icon: this.product.icon,
        active: this.product.active,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/admin/products/${this.product.id}`, {
        // onSuccess: () => this.form.reset("password", "photo"),
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this product?")) {
        this.$inertia.delete(`/admin/products/${this.product.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this product?")) {
        this.$inertia.put(`/admin/products/${this.product.id}/restore`);
      }
    },
  },
};
</script>
