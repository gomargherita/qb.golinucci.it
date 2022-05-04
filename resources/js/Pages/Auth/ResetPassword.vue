<template>
  <Head title="Reset Password" />
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
            "Choose you new password"
          )
        }}
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

        <div class="mt-4">
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            label="Password"
            v-model="form.password"
            required
            autocomplete="new-password"
          />
        </div>

        <div class="mt-4">
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            label="Confirm Password"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />
        </div>

        <div class="flex items-center justify-end mt-4">
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            :disabled="form.processing"
          >
            {{ __("Reset Password") }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import EmptyLayout from "@/Shared/Layouts/EmptyLayout";
import TextInput from "@/Shared/TextInput.vue";
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
    email: String,
    token: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: "",
        password_confirmation: "",
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("password.update"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
  },
};
</script>
