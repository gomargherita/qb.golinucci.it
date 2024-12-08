<template>
  <div class="ml-4">
    <Link :href="route('language', [selectable_locale])">
      <icon class="w-5 h-5" :name="selectable_locale" />
    </Link>
  </div>
</template>

<script>
import { usePage } from "@inertiajs/vue3";

export default {
  computed: {
    selectable_locale() {
      if (usePage().props.value.locale == "it") {
        return "en";
      }
      return "it";
    },
  },
};
</script>
