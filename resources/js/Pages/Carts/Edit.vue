<template>
  <div>
    <Head :title="`${form.cart_ref}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-primary hover:text-secondary-400"
        :href="route('carts')"
        >{{ __("Carts") }}</Link
      >
      <span class="text-gray font-medium">/</span>
      <span>{{ form.cart_ref }} </span>
    </h1>
    <trashed-message v-if="cart.deleted_at" class="mb-6" @restore="restore"
      >{{ __("This user detail has been deleted") }}.</trashed-message
    >
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.product"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First Name"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!cart.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Cart") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Update") }} {{ __("Cart") }}</loading-button
          >
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Related Payment") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Last Name</th>
          <th class="pb-4 pt-6 px-6">First Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
        </tr>
        <tr v-if="user" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('users.edit', user.id)"
            >
              {{ user.last_name }}
              <icon
                v-if="user.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.first_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="user.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
        </tr>
      </table>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Related User") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Last Name</th>
          <th class="pb-4 pt-6 px-6">First Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
        </tr>
        <tr v-if="user" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('users.edit', user.id)"
            >
              {{ user.last_name }}
              <icon
                v-if="user.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.first_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="user.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    LoadingButton,
    SelectInput,
    CheckboxInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    cart: Object,
    user: Object,
    can: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        cart_ref: this.cart.cart_ref,
      }),
    };
  },
  methods: {
    update() {
      this.form.put(`/admin/carts/${this.cart.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this cart?")) {
        this.$inertia.delete(`/admin/carts/${this.cart.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this cart?")) {
        this.$inertia.put(`/admin/carts/${this.cart.id}/restore`);
      }
    },
  },
};
</script>
