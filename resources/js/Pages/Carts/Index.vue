<template>
  <div>
    <template v-if="product_type == 2">
      <Head title="Carrelli Bancari" />
      <h1 class="mb-8 text-3xl font-bold">{{ __("Carts") }}{{ __(" bancari") }}</h1>
    </template>
    <template v-if="product_type == 1">
      <Head title="Carrelli Medici" />
      <h1 class="mb-8 text-3xl font-bold">{{ __("Carts") }}{{ __(" medici") }}</h1>
    </template>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">{{ __("Current Step") }}:</label>
        <select v-model="form.steps" class="form-select mt-1 w-full">
          <option :value="null" />
            <option v-for="product_step in all_product_steps" :value="product_step.step_number">{{ product_step.step_number }}. {{ product_step.step_name }}</option>
        </select>
        <label class="block text-gray-700">{{ __("Trashed") }}:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __("With Trashed") }}</option>
          <option value="only">{{ __("Only Trashed") }}</option>
        </select>
      </search-filter>
      <template v-if="product_type == 2">
      <Link class="btn-gol-primary" :href="route('carts.create', [product_type])">
        <span>{{ __("Create") }}</span>
        <span class="hidden md:inline">&nbsp;{{ __("Cart") }}</span>
      </Link>
    </template>
    </div>
    <pagination class="mb-6" :links="carts.links" />
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">{{ __("Name") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Surname") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Cart ref") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Current step") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Product") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Product offer") }}</th>
            <th class="pb-4 pt-6 px-6">{{ __("Created at") }}</th>
            <th class="pb-4 pt-6 px-6" colspan="2">{{ __("User") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="cart in carts.data"
            :key="cart.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('carts.edit', cart.id)"
              >
                {{ cart.user.first_name }}
                <icon
                  v-if="cart.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('carts.edit', cart.id)"
              >
                {{ cart.user.last_name }}
                <icon
                  v-if="cart.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('carts.edit', cart.id)"
              >
                {{ cart.cart_ref }}
                <icon
                  v-if="cart.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                {{ cart.step }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                {{ cart.product_name }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                {{ cart.product_offer }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                {{ cart.created_at }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 bg-orange-100"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                {{ cart.user.email }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="route('carts.edit', cart.id)"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="carts.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">
              {{ __("No carts found") }}.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="carts.links" />
  </div>
</template>

<script>
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layouts/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    all_product_steps: Array,
    carts: Object,
    product_type: Number,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        steps: this.filters.steps,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/admin/carts/"+this.product_type, pickBy(this.form), {
          preserveState: true,
          replace: true,
        });
        console.log(this.cart);
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
