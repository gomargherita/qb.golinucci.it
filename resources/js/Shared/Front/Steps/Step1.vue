<template>
  <Head :title="__('Client detail')" />
  <HTwo :title="__('Client detail')" />

  <BreezeValidationErrors class="mb-4" />

  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
      <div>
        <TextInput
          id="first_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.first_name"
          :error="form.errors.first_name"
          label="First Name"
          autofocus
        />
      </div>

      <div>
        <TextInput
          id="last_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.last_name"
          :error="form.errors.last_name"
          label="Last Name"
        />
      </div>

      <div>
        <TextInput
          id="fiscal_code"
          type="text"
          class="mt-1 block w-full"
          v-model="form.fiscal_code"
          :error="form.errors.fiscal_code"
          label="Fiscal Code"
        />
      </div>

      <div>
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full"
          v-model="form.address"
          :error="form.errors.address"
          label="Address"
        />
      </div>

      <div>
        <TextInput
          id="city"
          type="text"
          class="mt-1 block w-full"
          v-model="form.city"
          :error="form.errors.city"
          label="City"
        />
      </div>

      <div>
        <TextInput
          id="region"
          type="text"
          class="mt-1 block w-full"
          v-model="form.region"
          :error="form.errors.region"
          label="Region"
        />
      </div>

      <div>
        <TextInput
          id="postal_code"
          type="text"
          class="mt-1 block w-full"
          v-model="form.postal_code"
          :error="form.errors.postal_code"
          label="Postal Code"
        />
      </div>

      <div>
        <TextInput
          id="phone"
          type="text"
          class="mt-1 block w-full"
          v-model="form.phone"
          :error="form.errors.phone"
          label="Phone"
        />
      </div>
      <div>
        <label class="flex items-center mt-6 select-none" for="confirmation">
          <input
            id="confirmation"
            v-model="form.confirmation"
            class="mr-1"
            type="checkbox"
          />
          <span class="text-sm">{{
            __("I confirm the correctness of the above data")
          }}</span>
        </label>
      </div>
      <div>
        <buy-loading-button
          :loading="form.processing"
          :disabled="form.processing"
          :class="{ 'opacity-25': form.processing }"
          class="gol-btn my-8 bg-primary"
          type="submit"
          >{{ __("Next") }}</buy-loading-button
        >
      </div>
    </div>
  </form>
</template>

<script>
import BuyLoadingButton from "@/Shared/BuyLoadingButton";
import BreezeValidationErrors from "@/Shared/ValidationErrors.vue";

import TextInput from "@/Shared/TextInput.vue";
import HTwo from "@/Shared/Front/HTwo";

export default {
  components: {
    BuyLoadingButton,
    BreezeValidationErrors,
    TextInput,
    HTwo,
  },
  props: {
    cart: Object,
    product: Object,
    conditions: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        user_id: this.$page.props.auth.user.id,
        first_name: this.$page.props.clientDetails.first_name,
        last_name: this.$page.props.clientDetails.last_name,
        fiscal_code: this.$page.props.clientDetails.fiscal_code,
        address: this.$page.props.clientDetails.address,
        city: this.$page.props.clientDetails.city,
        region: this.$page.props.clientDetails.region,
        postal_code: this.$page.props.clientDetails.postal_code,
        country: "IT",
        phone: this.$page.props.clientDetails.phone,
        confirmation: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(
        this.route("front.product.store_step1", {
          product: this.product.id,
          cart: this.cart.cart_ref,
        }),
        {
          // onSuccess: () => this.form.reset('password', 'password_confirmation'),
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        }
      );
    },
  },
};
</script>
