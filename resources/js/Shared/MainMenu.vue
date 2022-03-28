<template>
  <div class="uppercase text-white">
    <div class="mb-4 md:mb-0 md:inline-block">
      <Link class="group flex items-center py-3 md:px-6" href="/about-us">
        <div :class="isUrl('about-us') ? 'text-primary' : 'group-hover:text-primary'">
          {{ __("About Us") }}
        </div>
      </Link>
    </div>
    <div class="mb-4 md:mb-0 md:inline-block">
      <Link class="group flex items-center py-3 md:px-6" href="/products">
        <div :class="isUrl('products') ? 'text-primary' : 'group-hover:text-primary'">
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
