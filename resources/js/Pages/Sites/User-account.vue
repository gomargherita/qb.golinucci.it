<template>
  <Head>
    <title>{{ __("User account") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B about us meta description"
    />
  </Head>
  <HOne :title="`User account`" />

  <section id="main-content" class="container mx-auto p-2">
    <h2>
      {{ __("User data") }}
    </h2>
    <div class="">asd</div>
    <h2>
      {{ __("My products") }}
    </h2>
    <div class="">asd</div>
    <h2>
      {{ __("My documents") }}
    </h2>
    <div class="">asd</div>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";

export default {
  components: {
    HOne,
  },
};
</script>
