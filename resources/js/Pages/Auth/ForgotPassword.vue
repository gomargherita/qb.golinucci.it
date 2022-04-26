<template>
  <Head title="Forgot Password" />

  <div class="mb-4 text-sm text-gray-600">
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
      <BreezeLabel for="email" value="Email" />
      <BreezeInput
        id="email"
        type="email"
        class="mt-1 block w-full"
        v-model="form.email"
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
</template>

<script>
import BreezeGuestLayout from "@/Shared/Guest.vue";
import BreezeInput from "@/Shared/TextInput.vue";
import BreezeLabel from "@/Shared/Label.vue";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

export default {
  layout: BreezeGuestLayout,

  components: {
    BreezeInput,
    BreezeLabel,
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
