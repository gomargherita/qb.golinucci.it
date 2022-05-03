<template>
  <Head title="Forgot Password" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-primary-800">
    <div class="w-full max-w-md">
      <icon
        name="golinucci-logo"
        class="block mx-auto w-full max-w-xs fill-white"
        height="50"
      />

      <div class="my-8 text-sm text-gray-600">
        {{
          __(
            "Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one."
          )
        }}
      </div>

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>

      <BreezeValidationErrors class="mb-4" />

      <form @submit.prevent="submit">
        <div>
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            label="Email"
            required
            autofocus
            autocomplete="username"
          />
        </div>

        <div class="flex items-center justify-end mt-4">
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            :disabled="form.processing"
          >
            {{ __("Email Password Reset Link") }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import EmptyLayout from "@/Shared/Layouts/EmptyLayout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

export default {
  layout: EmptyLayout,
  components: {
    LoadingButton,
    TextInput,
    BreezeValidationErrors,
  },
  props: {
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("password.email"));
    },
  },
};
</script>
