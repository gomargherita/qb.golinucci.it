<template>
  <div class="my-8">
    <div class="flex flex-col justify-between gap-12">
      <div v-if="product" class="bg-gray-100 md:min-h-96 min-h-max shadow">
        <div class="md:flex">
          <div class="w-full md:w-2/5">
            <img src="/img/medic.jpg" class="w-full min-h-96 h-full object-cover" />
          </div>
          <div class="w-full md:w-3/5">
            <div
              :style="`border-color: ${product.hex_color};`"
              class="md:border-l-8 border-t-8 md:border-t-0 p-6 md:px-12 md:py-8 min-h-96 h-full"
            >
              <div>
                <div class="flex items-between gap-4">
                  <div class="flex-none">
                    <div
                      class="flex items-center h-16 rounded-full w-16 mx-auto shadow"
                      :style="`background-color: ${product.hex_color};`"
                    >
                      <icon
                        :name="product.type"
                        class="mx-auto object-scale-down h-8 w-full"
                      />
                    </div>
                  </div>
                  <div
                    class="flex-1 text-2xl font-bold min-h-16"
                    :style="`color: ${product.hex_color};`"
                  >
                    {{ product.name }}
                  </div>
                </div>

                <div class="mt-4">{{ product.detailed_description }}</div>
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
                  <button
                    type="button"
                    class="gol-btn text-center mt-8"
                    :style="`background-color: ${product.hex_color};`"
                    @click="scrollMeTo('offers')"
                  >
                    {{ __("Discover our Offers") }}
                  </button>
                  <button
                    type="button"
                    class="gol-btn text-center mt-8"
                    :style="`background-color: ${product.hex_color};`"
                    @click="scrollMeTo('buy')"
                  >
                    {{ __("Buy Now") }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div ref="offers">
    <HTwo :title="__('Our Offers')" />
    <Offers :product="product" :conditions="conditions"> </Offers>
  </div>
  <div ref="buy">
    <HTwo :title="__('Buy this Product')" />
    <div>Buy</div>
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Offers from "@/Shared/Front/Offers";

export default {
  components: {
    HTwo,
    Offers,
  },
  props: {
    product: Object,
    conditions: Array,
  },
  methods: {
    scrollMeTo(refName) {
      var element = this.$refs[refName];
      element.scrollIntoView({ block: "start", behavior: "smooth" });
    },
  },
};
</script>
