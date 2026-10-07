<template>
  <div>
    <Head :title="`Email: ${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('emailTemplates')"
        >Email templates</Link
      >
      <span class="text-gray font-medium">/</span>
      <span>{{ form.name }}</span>
    </h1>

    <div class="mb-4 text-sm text-gray-600">
      Key: <code>{{ template.key }}</code> — variabili:
      <code>{{ variables_help }}</code>
    </div>

    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Name"
          />
          <text-input
            v-model="form.subject"
            :error="form.errors.subject"
            class="pb-8 pr-6 w-full"
            label="Subject"
          />
          <textarea-input
            v-model="form.body"
            :error="form.errors.body"
            class="pb-8 pr-6 w-full"
            label="Body"
          />
          <text-input
            v-model="form.bcc"
            :error="form.errors.bcc"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="BCC (optional)"
          />
          <checkbox-input
            v-model="form.is_active"
            :error="form.errors.is_active"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Active"
            :checked="form.is_active"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-gol-primary" type="submit"
            >Save</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import TextareaInput from "@/Shared/TextareaInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: { Head, Link, TextInput, TextareaInput, CheckboxInput, LoadingButton },
  layout: Layout,
  props: {
    template: Object,
    variables_help: String,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        name: this.template.name,
        subject: this.template.subject,
        body: this.template.body,
        bcc: this.template.bcc,
        is_active: this.template.is_active,
      }),
    };
  },
  methods: {
    submit() {
      this.form.put(this.route("emailTemplates.update", this.template.id));
    },
  },
};
</script>
