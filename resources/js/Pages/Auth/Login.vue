<template>
  <Head title="Login" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-primary-800">
    <div class="w-full max-w-md">
      <icon
        name="golinucci-logo"
        class="block mx-auto w-full max-w-xs fill-white"
        height="50"
      />
      <form
        class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
        @submit.prevent="login"
      >
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">{{ __("Login") }}</h1>
          <div class="mt-6 mx-auto w-24 border-b-2" />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="mt-10"
            label="Email"
            type="email"
            autofocus
            autocapitalize="off"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="mt-6"
            label="Password"
            type="password"
          />
          <label class="flex items-center mt-6 select-none" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">{{ __("Remember me") }}</span>
          </label>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <Link href="/forgot-password" class="mt-3">
            {{ __("Forgot password") }}
          </Link>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Login") }}</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    LoadingButton,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: "masiorama@gmail.com",
        password: "password",
        remember: false,
      }),
    };
  },
  methods: {
    login() {
      this.form.post("/login");
    },
  },
};
</script>
