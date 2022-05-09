<template>
  <div ref="buy">
    <HTwo :title="__('Buy this Product')" />

    <div class="flex items-center justify-between mx-auto gap2 md:gap-4">
      <template v-for="(num, key) in numSteps" :key="key">
        <div class="flex items-center text-primary relative">
          <div
            class="rounded-full h-6 w-6 md:h-12 md:w-12 md:py-3 border-2 border-gray-300"
            :class="{ 'border-primary bg-primary text-white': current_step == num }"
          >
            <div class="text-center font-bold" v-text="num"></div>
          </div>
        </div>
        <div v-if="key + 1 != numSteps" class="hidden md:block flex-auto border-t-2"></div>
      </template>
    </div>

    <Step1
      v-if="current_step == 1"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
    <Step2
      v-if="current_step == 2"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
    <Step3
      v-if="current_step == 3"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
    <Step4
      v-if="current_step == 4"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
    <Step5
      v-if="current_step == 5"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
    <Step6
      v-if="current_step == 6"
      :product="product"
      :cart="cart"
      :conditions="conditions"
    />
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Step1 from "@/Shared/Front/Steps/Step1";
import Step2 from "@/Shared/Front/Steps/Step2";
import Step3 from "@/Shared/Front/Steps/Step3";
import Step4 from "@/Shared/Front/Steps/Step4";
import Step5 from "@/Shared/Front/Steps/Step5";
import Step6 from "@/Shared/Front/Steps/Step6";

export default {
  components: {
    HTwo,
    Step1,
    Step2,
    Step3,
    Step4,
    Step5,
    Step6,
  },
  props: {
    product: Object,
    cart: Object,
    conditions: Array,
    numSteps: {
      type: Number,
      default: 6,
    },
  },
  computed: {
    current_step() {
      return this.$page.props.cart.product_step_id;
    },
  },
};
</script>
