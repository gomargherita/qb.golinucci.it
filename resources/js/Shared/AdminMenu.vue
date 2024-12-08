<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('dashboard')">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(false, 'admin/dashboard')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/dashboard')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Dashboard") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('products')">
        <icon
          name="products"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(false, 'admin/products')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/products')
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
            isUrl(false, 'admin/roles')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/roles')
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
            isUrl(false, 'admin/users')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/users')
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
            isUrl(false, 'admin/clientDetails')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/clientDetails')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Client details") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('carts', 1)">
        <icon
          name="cart"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(true, 'admin/carts/1')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(true, 'admin/carts/1')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Carrelli Medici") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('carts', 2)">
        <icon
          name="cart"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(true, 'admin/carts/2')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(true, 'admin/carts/2')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Carrelli Bancari") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('bordereauxs')">
        <icon
          name="bordereaux"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(false, 'admin/bordereauxs')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/bordereauxs')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Bordereaux") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('importolibero')">
        <icon
          name="importolibero"
          class="mr-2 w-4 h-4"
          :class="
            isUrl(false, 'admin/importolibero')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl(false, 'admin/importolibero')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          Importo libero
        </div>
      </Link>
    </div>
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
import { Link } from "@inertiajs/vue3";

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
    isUrl(strict, ...urls) {
      let currentUrl = this.$page.url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      if(strict){
        return currentUrl == urls[0];
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    },
  },
};
</script>
