<template>
  <Link href="#">
    <div :class="active ? activeStyle : standardStyle">
      <slot />
    </div>
  </Link>
</template>

<script>
export default {
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
