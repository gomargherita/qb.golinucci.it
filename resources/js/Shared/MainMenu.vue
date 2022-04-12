<template>
  <div class="uppercase text-white">
    <div class="mb-2 md:mb-0 md:inline-block">
      <NavLink
        :href="route('front.aboutus')"
        class="group font-medium flex items-center py-3 md:px-6"
        :active="$page.component === 'Sites/About-us'"
        :standardStyle="'group-hover:underline'"
        :activeStyle="'text-yellow-400'"
      >
        {{ __("About us") }}
      </NavLink>
    </div>
    <div class="mb-2 md:mb-0 md:inline-block">
      <NavLink
        :href="route('front.products')"
        class="group font-medium flex items-center py-3 md:px-6"
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
