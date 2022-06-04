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
        v-for="slide in [1]"
        class="relative inset-0 w-screen min-h-screen max-h-screen flex items-center md:items-left justify-center md:justify-start"
      >
        <div class="absolute inset-0 opacity-50">
          <img
            src="/img/hands.jpg"
            class="object-cover bg-no-repeat w-screen min-h-screen max-h-screen"
          />
        </div>
        <div class="container mx-auto">
          <div class="relative full md:w-1/3 text-center md:text-left m-12">
            <div class="text-4xl text-primary capitalize text-primary font-semibold mt-8">
              Dal 1952 Assicuriamo<br />la Vostra Serenità
            </div>
            <div class="mt-4">
              <p class="font-medium [text-shadow:0_2px_3px_rgba(255,255,255,0.72)]">
                Golinucci Srl in qualità di broker assicurativo sceglie il prodotto
                assicurativo più conveniente e più adatto alle vostre esigenze.
              </p>
            </div>
          </div>
        </div>
      </swiper-slide>
    </swiper>
  </section>
  <section id="main-content" class="container mx-auto p-2 bg-white mt-8">
    <h2>Le nostre polizze</h2>
    <hr class="gol-hr" />
    <div class="my-8">
      <div class="flex flex-col justify-between gap-12">
        <div
          v-for="(product, index) in products"
          class="bg-gray-100 md:min-h-96 min-h-max shadow"
        >
          <div class="md:flex">
            <div class="w-full md:w-3/5">
              <img src="/img/medic.jpg" class="w-full min-h-96 h-full object-cover" />
            </div>
            <div class="w-full md:w-2/5" :class="{ 'md:order-first': index % 2 !== 0 }">
              <div
                :class="{
                  'md:border-l-8': index % 2 === 0,
                  'md:border-r-8': index % 2 !== 0,
                }"
                :style="`border-color: ${product.hex_color};`"
                class="flex items-center border-t-8 md:border-t-0 p-6 md:px-12 md:py-8 min-h-96 h-full text-center"
              >
                <div>
                  <div
                    class="flex items-center h-16 rounded-full w-16 mx-auto shadow"
                    :style="`background-color: ${product.hex_color};`"
                  >
                    <icon
                      :name="product.type"
                      class="mx-auto object-scale-down h-8 w-full"
                    />
                  </div>
                  <h2
                    class="text-2xl font-bold mt-4"
                    :style="`color: ${product.hex_color};`"
                  >
                    {{ product.name }}
                  </h2>
                  <Link
                    class="gol-btn mt-8"
                    :style="`background-color: ${product.hex_color};`"
                    :href="route('front.product', product.id)"
                    tabindex="-1"
                  >
                    {{ __("More") }}
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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
  props: {
    products: Array,
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
