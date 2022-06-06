<template>
  <div>
    <Head :title="`${doprint_counter.year} ${doprint_counter.prefix}`" />
    <div class="flex justify-start mb-8">
      <h1 class="text-3xl font-bold">
        <span>{{ __("Doprint Counters") }}</span>
        <span class="text-gray font-medium">/</span>
        <Link
          class="text-primary hover:text-secondary-400"
          :href="route('products.edit', doprint_counter.product_id)"
          >{{ __("Back to") }} {{ __("Product") }}</Link
        >
      </h1>
    </div>
    <trashed-message v-if="doprint_counter.deleted_at" class="mb-6" @restore="restore">
      {{ __("This Doprint Counter has been deleted") }}.
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
          <text-input
            v-model="form.postfix"
            :error="form.errors.postfix"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Postfix"
          />
        </div>

        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
          >
            {{ __("Update") }} {{ __("Doprint Counter") }}
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
    doprint_counter: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        product_id: this.doprint_counter.product_id,
        year: this.doprint_counter.year.toString(),
        prefix: this.doprint_counter.prefix,
        counter: this.doprint_counter.counter.toString(),
        postfix: this.doprint_counter.postfix,
      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/doprintCounters/${this.doprint_counter.id}`);
    },
  },
};
</script>
