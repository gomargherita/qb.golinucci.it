<template>
  <Head title="Site" />
  <section class="container mx-auto p-2">
    <h1 class="mb-8 text-3xl font-bold">About us</h1>
    <p class="mb-8 leading-normal">
      {{ __("Text example") }} e <a class="gol-link" href="#">link</a>
    </p>
  </section>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Shared/FrontLayout";

export default {
  layout: FrontLayout,
  components: {
    Head,
  },
};
</script>
