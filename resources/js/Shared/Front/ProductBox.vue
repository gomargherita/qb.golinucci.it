<template>
  <div class="my-8">
    <div class="flex flex-col justify-between gap-8">
      <div v-for="(product, index) in products" class="bg-gray-100 md:max-h-96 min-h-max">
        <div class="md:flex">
          <div class="w-full md:w-3/5">
            <img src="/img/medic.jpg" class="w-full h-96 object-cover" />
          </div>
          <div class="w-full md:w-2/5" :class="{ 'md:order-first': index % 2 !== 0 }">
            <div
              :class="{
                'md:border-l-8': index % 2 === 0,
                'md:border-r-8': index % 2 !== 0,
              }"
              :style="`border-color: ${product.color};`"
              class="border-t-8 md:border-t-0 p-8 h-96"
            >
              <div>{{ product.icon }}</div>
              <div>{{ product.title }}</div>
              <div>{{ product.description }}</div>
              <div>{{ product.label }}</div>
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
