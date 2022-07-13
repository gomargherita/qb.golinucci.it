<template>
  <Head title="Register" />

  <div class="flex items-center justify-center p-6 min-h-screen bg-primary-800">
    <div class="w-full max-w-md">
      <icon
        name="golinucci-logo"
        class="block mx-auto w-full max-w-xs fill-white"
        height="50"
      />
      <form
        class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
        @submit.prevent="submit"
      >
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">{{ __("Register") }}</h1>
          <div class="mt-6 mx-auto w-24 border-b-2" />
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="mt-10"
            label="First Name"
            type="text"
            autofocus
          />

          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="mt-4"
            label="Last Name"
            type="text"
          />

          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="mt-4"
            label="Email"
            type="email"
            autocapitalize="off"
          />

          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="mt-4"
            label="Password"
            type="password"
            autocomplete="new-password"
          />

          <text-input
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
            class="mt-4"
            label="Confirm password"
            type="password"
            autocomplete="new-password"
          />

        </div>

        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <div>
            <div>
              <Link
                :href="route('login')"
                class="underline text-sm text-gray-600 hover:text-gray-900"
              >
                {{ __("Already registered?") }}
              </Link>
            </div>
          </div>
          <loading-button
            :loading="form.processing"
            :disabled="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Register") }}</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import EmptyLayout from "@/Shared/Layouts/EmptyLayout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  layout: EmptyLayout,

  components: {
    LoadingButton,
    TextInput,
  },

  data() {
    return {
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("register.store"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
  },
};
</script>
