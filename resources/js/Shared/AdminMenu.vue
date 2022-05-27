<template>
  <div>
    <!-- <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('dashboard')">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/dashboard')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/dashboard')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Dashboard") }}
        </div>
      </Link>
    </div> -->
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('products')">
        <icon
          name="products"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/products')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/products')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Products") }}
        </div>
      </Link>
    </div>
    <div v-if="isSuperadmin" class="mb-4">
      <Link class="group flex items-center py-3" :href="route('roles')">
        <icon
          name="roles"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/roles')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/roles')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Roles") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('users')">
        <icon
          name="users"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/users')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/users')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Users") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('clientDetails')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/clientDetails')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/clientDetails')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Client details") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('carts')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/carts')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/cart')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Carts") }}
        </div>
      </Link>
    </div>
    <!-- <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('reports')">
        <icon
          name="printer"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('admin/reports')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('admin/reports')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Reports") }}
        </div>
      </Link>
    </div> -->
    <div class="mb-4">
      <a class="group flex items-center py-3" href="/" target="_blank">
        <icon
          name="external-link"
          class="mr-2 w-4 h-4 fill-gray-900 group-hover:fill-primary"
        />
        <div class="text-gray-900 group-hover:text-primary">
          {{ __("Public") }}
        </div>
      </a>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
  computed: {
    isSuperadmin() {
      return this.$page.props.auth.user.is_superadmin;
    },
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    },
  },
};
</script>
