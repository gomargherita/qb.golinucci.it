<template>
  <div>
    <Head :title="`${product_counter.year} ${product_counter.prefix}`" />
    <div class="flex justify-start mb-8">
      <h1 class="text-3xl font-bold">
        <span>{{ __("Product Counters") }}</span>
        <span class="text-gray font-medium">/</span>
        <Link
          class="text-primary hover:text-secondary-400"
          :href="route('products.edit', product_counter.product_id)"
          >{{ __("Back to") }} {{ __("Product") }}</Link
        >
      </h1>
    </div>
    <trashed-message v-if="product_counter.deleted_at" class="mb-6" @restore="restore">
      {{ __("This product has been deleted") }}.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.year"
            :error="form.errors.year"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Year"
          />
          <text-input
            v-model="form.prefix"
            :error="form.errors.prefix"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Prefix"
          />
          <text-input
            v-model="form.counter"
            :error="form.errors.counter"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Counter"
          />
        </div>

        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
          >
            {{ __("Update") }} {{ __("Product Counter") }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import Editor from "@/Shared/Editor";

export default {
  components: {
    LoadingButton,
    TextInput,
    TrashedMessage,
    Editor,
  },
  layout: Layout,
  props: {
    product_counter: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        payment_gateway_id: this.product_counter.payment_gateway_id,
        year: this.product_counter.year,
        prefix: this.product_counter.prefix,
        counter: this.product_counter.counter.toString(),
      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/productCounters/${this.product_counter.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this product counter?")) {
        this.$inertia.delete(`/admin/productCounters/${this.product.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this product counter?")) {
        this.$inertia.put(`/admin/productCounters/${this.product.id}/restore`);
      }
    },
  },
};
</script>
