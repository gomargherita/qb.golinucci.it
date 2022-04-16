<template>
  <div class="mx-auto py-8">
    <h2 class="">{{ __(title) }}</h2>
    <hr class="gol-hr" />
  </div>
</template>

<script>
export default {
  props: {
    title: String,
  },
};
</script>
