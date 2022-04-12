<template>
  <Head>
    <title>Dal 1952 assicuriamo la vostra serenità</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B home meta description"
    />
  </Head>
  <section id="home-swiper" class="relative shadow-md">
    <swiper
      :pagination="true"
      :navigation="true"
      :modules="modules"
      class="mySwiper"
      :slides-per-view="1"
      @swiper="onSwiper"
      @slideChange="onSlideChange"
    >
      <swiper-slide
        v-for="slide in [1, 2, 3]"
        class="inset-0 w-screen min-h-screen max-h-screen flex items-center justify-center text-5xl"
        v-text="'Hello' + slide"
      >
      </swiper-slide>
    </swiper>
  </section>
  <section class="container mx-auto p-2 bg-white mt-8">
    <h1>Le nostre polizze</h1>
    <hr class="gol-hr" />
  </section>

  <section class="bg-white py-8">
    <div class="container py-8 px-6 mx-auto">
      <a
        class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl mb-8"
        href="#"
      >
        About
      </a>

      <p class="mb-8"></p>
    </div>
  </section>
</template>

<script>
import MainMenu from "@/Shared/MainMenu";
import { Navigation, Pagination, Scrollbar, A11y } from "swiper";

import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";

export default {
  components: {
    MainMenu,
    Swiper,
    SwiperSlide,
  },
  setup() {
    const onSwiper = (swiper) => {
      // console.log(swiper);
    };
    const onSlideChange = () => {
      // console.log("slide change");
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Navigation, Pagination, Scrollbar, A11y],
    };
  },
};
</script>
