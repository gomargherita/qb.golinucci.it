<template>
  <div class="uppercase text-white">
    <div class="mb-4 md:mb-0 md:inline-block">
      <Link class="group flex items-center py-3 md:px-6" href="/account">
        <div class="text-primary">
          {{ __("Account") }}
        </div>
      </Link>
    </div>
    <div class="mb-4 md:mb-0 md:inline-block">
      <Link class="group flex items-center py-3 md:px-6" href="/account/products">
        <div class="text-primary">
          {{ __("My Products") }}
        </div>
      </Link>
    </div>
    <div class="mb-4 md:mb-0 md:inline-block">
      <Link class="group flex items-center py-3 md:px-6" href="/logout">
        <div class="text-primary">
          {{ __("Logout") }}
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
};
</script>
