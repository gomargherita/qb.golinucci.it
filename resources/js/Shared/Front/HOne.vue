<template>
  <section class="relative mx-auto py-24">
    <div
      class="absolute inset-0 bg-cover opacity-50"
      style="background-image: url('/img/sign.jpg')"
    ></div>
    <div class="absolute inset-0 container mx-auto py-8">
      <h1 class="">{{ __(title) }}</h1>
      <hr class="gol-hr" />
    </div>
  </section>
</template>

<script>
export default {
  props: {
    title: String,
  },
};
</script>
