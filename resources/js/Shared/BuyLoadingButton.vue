<template>
  <button :disabled="loading" class="flex items-center relative">
    <div v-if="loading" class="absolute btn-spinner mr-4 mt-1 right-0" />
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
  },
};
</script>
