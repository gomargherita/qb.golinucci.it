<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4"
          :class="isUrl('') ? 'fill-primary' : 'fill-gray-900 group-hover:fill-primary'"
        />
        <div
          :class="isUrl('') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'"
        >
          {{ __("Dashboard") }}
        </div>
      </Link>
    </div>
    <div v-if="can('manage roles')" class="mb-4">
      <Link class="group flex items-center py-3" href="/roles">
        <icon
          name="users"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('roles') ? 'fill-primary' : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('roles') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Roles") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/users">
        <icon
          name="users"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('users') ? 'fill-primary' : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('users') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Users") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/clientDetails">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('clientDetails')
              ? 'fill-primary'
              : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('clientDetails')
              ? 'text-primary'
              : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Client details") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <icon
          name="printer"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('reports') ? 'fill-primary' : 'fill-gray-900 group-hover:fill-primary'
          "
        />
        <div
          :class="
            isUrl('reports') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Reports") }}
        </div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Icon,
    Link,
  },
  props: {
    can: Object
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
