<template>
  <Head>
    <title>Golinucci Q&amp;B</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B default meta description"
    />
  </Head>
  <div>
    <section>
      <PreHeader />
    </section>
    <section>
      <Header :current_user="current_user" />
    </section>

    <section class="bg-white" scroll-region>
      <flash-messages />
      <slot />
    </section>
    <section>
      <Footer />
    </section>
  </div>
</template>

<script>
import Header from "@/Shared/Front/Header";
import PreHeader from "@/Shared/Front/PreHeader";
import Footer from "@/Shared/Front/Footer";
import FlashMessages from "@/Shared/FlashMessages";

export default {
  components: {
    Header,
    PreHeader,
    Footer,
    FlashMessages,
  },
  computed: {
    current_user() {
      return this.$page.props.auth.user;
    },
  },
};
</script>
