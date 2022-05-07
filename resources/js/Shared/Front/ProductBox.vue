<template>
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
                  <icon :name="product.type" class="mx-auto object-scale-down h-8 w-full" />
                </div>
                <h2
                  class="text-2xl font-bold mt-4"
                  :style="`color: ${product.hex_color};`"
                >
                  {{ product.name }}
                </h2>
                <div class="mt-4" v-html="product.description"></div>
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
</template>

<script>
export default {
  props: {
    products: Array,
  },
};
</script>
