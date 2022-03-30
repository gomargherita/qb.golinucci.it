<template>
  <Link href="#">
    <div :class="active ? activeStyle : standardStyle">
      <slot />
    </div>
  </Link>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: { Link },
  props: {
    active: Boolean,
    standardStyle: {
      type: String,
      default: "",
    },
    activeStyle: {
      type: String,
      default: "",
    },
  },
};
</script>
