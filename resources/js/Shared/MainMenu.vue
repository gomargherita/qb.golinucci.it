<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/sites">
        <div
          :class="isUrl('sites') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'"
        >
          {{ __("Home") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/sites/about-us">
        <div
          :class="
            isUrl('sites/about-us') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("About us") }}
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/sites/products">
        <div
          :class="
            isUrl('sites/products') ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
          "
        >
          {{ __("Products") }}
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
