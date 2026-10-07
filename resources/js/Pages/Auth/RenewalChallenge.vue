<template>
  <Head title="Codice rinnovo" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-primary-800">
    <div class="w-full max-w-md">
      <icon
        name="golinucci-logo"
        class="block mx-auto w-full max-w-xs fill-white"
        height="50"
      />

      <div class="my-8 text-sm text-white">
        Per continuare il rinnovo inserisci il codice inviato a
        <strong v-if="email_hint">{{ email_hint }}</strong
        ><span v-else>la tua email</span>.
        Il codice scade tra {{ ttl_minutes }} minuti.
      </div>

      <div v-if="status" class="mb-4 font-medium text-sm text-green-300">
        {{ status }}
      </div>

      <BreezeValidationErrors class="mb-4" />

      <form @submit.prevent="submit">
        <div>
          <TextInput
            id="code"
            type="text"
            class="mt-1 block w-full"
            v-model="form.code"
            :error="form.errors.code"
            label="Codice"
            required
            autofocus
            autocomplete="one-time-code"
            inputmode="numeric"
          />
        </div>

        <div class="flex items-center justify-between mt-4 gap-3">
          <loading-button
            :loading="resendForm.processing"
            class="btn-gol-primary opacity-80"
            type="button"
            :disabled="resendForm.processing"
            @click="resend"
          >
            Reinvia codice
          </loading-button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            :disabled="form.processing"
          >
            Continua
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
    TextInput,
    LoadingButton,
    BreezeValidationErrors,
  },
  props: {
    cart_ref: String,
    email_hint: String,
    ttl_minutes: Number,
    status: String,
  },
  data() {
    return {
      form: this.$inertia.form({
        code: "",
      }),
      resendForm: this.$inertia.form({}),
    };
  },
  methods: {
    submit() {
      this.form.post(this.route("front.renewal.challenge.verify"));
    },
    resend() {
      this.resendForm.post(this.route("front.renewal.challenge.resend"));
    },
  },
};
</script>
