<template>
  <div class="uppercase text-white">
    <div class="mb-4 md:mb-0 md:inline-block">
      <NavLink
        href="/about-us"
        class="group flex items-center py-3 md:px-6"
        :active="$page.component === 'Sites/About-us'"
        :standardStyle="'group-hover:underline'"
        :activeStyle="'text-yellow-400'"
      >
        {{ __("About Us") }}
      </NavLink>
    </div>
    <div class="mb-4 md:mb-0 md:inline-block">
      <NavLink
        href="/products"
        class="group flex items-center py-3 md:px-6"
        :active="$page.component === 'Sites/Products'"
        :standardStyle="'group-hover:underline'"
        :activeStyle="'text-yellow-400'"
      >
        {{ __("Products") }}
      </NavLink>
    </div>
  </div>
</template>

<script>
import NavLink from "@/Shared/NavLink";

export default {
  components: {
    NavLink,
  },
};
</script>
