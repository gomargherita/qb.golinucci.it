<template>
  <Head>
    <title>Golinucci Q&amp;B</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&amp;B default meta description"
    />
  </Head>
  <div>
    <section class="mt-1 bg-white" scroll-region>
      <flash-messages />
      <slot />
    </section>
  </div>
</template>

<script>
import FlashMessages from "@/Shared/FlashMessages";

export default {
  components: {
    FlashMessages,
  },
};
</script>
