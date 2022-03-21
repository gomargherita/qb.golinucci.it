<template>
  <FrontLayout>
    <Head title="Site" />
    <h1 class="mb-8 text-3xl font-bold">Home</h1>
    <p class="mb-8 leading-normal">
      {{ __("Text example") }} e <a class="gol-link" href="#">link</a>
    </p>
  </FrontLayout>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Shared/FrontLayout";

export default {
  components: {
    Head,
    FrontLayout,
  },
};
</script>
