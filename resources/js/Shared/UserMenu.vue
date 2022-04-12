<template>
  <div>
    <div class="mb-2 md:mb-0">
      <Link
        v-if="!isClient"
        class="block py-2 text-white hover:bg-primary"
        :href="route('admin')"
        >{{ __("Admin") }}
      </Link>
    </div>
    <div class="mb-2 md:mb-0">
      <NavLink
        :href="route('front.useraccount')"
        class="block py-2 text-white hover:bg-primary"
        :active="$page.component === 'Sites/User-account'"
        :activeStyle="'text-yellow-400'"
        >{{ __("My Profile") }}
      </NavLink>
    </div>
    <div class="mb-2 md:mb-0">
      <Link
        class="block py-2 w-full text-left text-white hover:bg-primary"
        :href="route('logout')"
        method="delete"
        as="button"
        >{{ __("Logout") }}
      </Link>
    </div>
  </div>
</template>

<script>
import NavLink from "@/Shared/NavLink";

export default {
  components: {
    NavLink,
  },
  props: {
    current_user: Object,
  },
  computed: {
    isClient() {
      let role = this.current_user.roles[0];
      return role.name == "Client User";
    },
  },
};
</script>
