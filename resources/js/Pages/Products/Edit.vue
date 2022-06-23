<template>
  <div>
    <Head :title="`${form.name} ${form.last_name}`" />
    <div class="flex justify-start mb-8">
      <h1 class="text-3xl font-bold">
        <Link class="text-primary hover:text-secondary-400" :href="route('products')">{{
          __("Products")
        }}</Link>
        <span class="text-gray font-medium">/</span>
        <span>{{ form.name }}</span>
      </h1>
    </div>
    <trashed-message v-if="product.deleted_at" class="mb-6" @restore="restore">
      {{ __("This product has been deleted") }}.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Name"
          />
          <select-input
            v-model="form.product_type_id"
            :error="form.errors.product_type_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Product Type"
          >
            <option
              v-for="product_type in product_types"
              :value="product_type.id"
              :selected="product_type.id == form.product_type_id"
            >
              {{ __(product_type.name) }}
            </option>
          </select-input>
          <editor
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full"
            label="Description"
          />
          <editor
            v-model="form.detailed_description"
            :error="form.errors.detailed_description"
            class="pb-8 pr-6 w-full"
            label="Detailed description"
          />
          <editor
            v-model="form.conditions"
            :error="form.errors.conditions"
            class="pb-8 pr-6 w-full"
            label="Conditions"
          />
          <editor
            v-model="form.client_details_declaration"
            :error="form.errors.client_details_declaration"
            class="pb-8 pr-6 w-full"
            label="Client details declaration"
          />
          <text-input
            v-model="form.hex_color"
            :error="form.errors.hex_color"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="hex_color"
            autocomplete="new-hex_color"
            label="Color"
          />
          <select-input
            v-model="form.active"
            :error="form.errors.active"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Active"
          >
            <option :value="1" :selected="form.active == 1">{{ __("Yes") }}</option>
            <option :value="0" :selected="form.active == 0">{{ __("No") }}</option>
          </select-input>
          <!-- <file-input
            v-model="form.image"
            :error="form.errors.image"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Image"
            @input="form.image = $event.target.files[0]"
          /> -->
        </div>
        <h2 class="text-2xl font-bold flex flex-wrap -mb-8 -mr-6 p-8">
          {{ __("Declarations") }}
        </h2>

        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <editor
            v-model="form.pre_declarations"
            :error="form.errors.pre_declarations"
            class="pb-8 pr-6 w-full"
            label="Testo pre dichiarazioni"
          />
          <editor
            v-model="form.post_declarations"
            :error="form.errors.post_declarations"
            class="pb-8 pr-6 w-full"
            label="Testo post dichiarazioni"
          />
        </div>

        <h2 class="text-2xl font-bold flex flex-wrap -mb-8 -mr-6 p-8">
          {{ __("Payments") }}
        </h2>

        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <editor
            v-model="form.payments_description"
            :error="form.errors.payments_description"
            class="pb-8 pr-6 w-full"
            label="Testo gateway di pagamento"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!product.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Product") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
          >
            {{ __("Update") }} {{ __("Product") }}
          </loading-button>
        </div>
      </form>
    </div>

    <h2 class="mt-12 text-2xl font-bold">{{ __("Product Counters") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Payment Gateway</th>
          <th class="pb-4 pt-6 px-6">Year</th>
          <th class="pb-4 pt-6 px-6">Prefix</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Counter</th>
        </tr>
        <tr
          v-if="product_counters"
          v-for="product_counter in product_counters"
          :key="product_counter.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('productCounters.edit', product_counter.id)"
            >
              {{ product_counter.payment_gateway.name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('productCounters.edit', product_counter.id)"
              tabindex="-1"
            >
              {{ product_counter.year }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('productCounters.edit', product_counter.id)"
              tabindex="-1"
            >
              {{ product_counter.prefix }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('productCounters.edit', product_counter.id)"
              tabindex="-1"
            >
              {{ product_counter.counter }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('productCounters.edit', product_counter.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="product_counters.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No product counters related.</td>
        </tr>
      </table>
    </div>

    <div v-if="doprint_counter">
      <h2 class="mt-12 text-2xl font-bold">{{ __("Doprint Counters") }}</h2>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Year</th>
            <th class="pb-4 pt-6 px-6">Prefix</th>
            <th class="pb-4 pt-6 px-6">Counter</th>
            <th class="pb-4 pt-6 px-6" colspan="2">Postfix</th>
          </tr>
          <tr
            :key="doprint_counter.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('doprintCounters.edit', doprint_counter.id)"
                tabindex="-1"
              >
                {{ doprint_counter.year }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('doprintCounters.edit', doprint_counter.id)"
                tabindex="-1"
              >
                {{ doprint_counter.prefix }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('doprintCounters.edit', doprint_counter.id)"
                tabindex="-1"
              >
                {{ doprint_counter.counter }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('doprintCounters.edit', doprint_counter.id)"
                tabindex="-1"
              >
                {{ doprint_counter.postfix }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="route('doprintCounters.edit', doprint_counter.id)"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import Editor from "@/Shared/Editor";

export default {
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
    Editor,
  },
  layout: Layout,
  props: {
    product: Object,
    product_types: Array,
    product_counters: Array,
    doprint_counter: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        name: this.product.name,
        product_type_id: this.product.product_type_id,
        description: this.product.description,
        detailed_description: this.product.detailed_description,
        conditions: this.product.conditions,
        client_details_declaration: this.product.client_details_declaration,
        pre_declarations: this.product.pre_declarations,
        post_declarations: this.product.post_declarations,
        payments_description: this.product.payments_description,
        hex_color: this.product.hex_color,
        // image: this.product.image,
        active: this.product.active,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/admin/products/${this.product.id}`, {
        // onSuccess: () => this.form.reset("image"),
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this product?")) {
        this.$inertia.delete(`/admin/products/${this.product.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this product?")) {
        this.$inertia.put(`/admin/products/${this.product.id}/restore`);
      }
    },
  },
};
</script>
