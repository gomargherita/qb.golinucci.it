<template>
  <div ref="buy">
    <HTwo :title="__('Buy this Product')" />

    <div class="flex items-center gap-4">
      <template v-for="(num, key) in numSteps" :key="key">
        <div class="flex items-center text-primary relative">
          <div
            class="rounded-full h-12 w-12 py-3 border-2 border-gray-300"
            :class="{ 'border-primary bg-primary text-white': current_step == num }"
          >
            <div class="text-center font-bold" v-text="num"></div>
          </div>
        </div>
        <div v-if="key + 1 != numSteps" class="flex-auto border-t-2"></div>
      </template>
    </div>

    <Step1
      v-if="route().current('front.product.step1')"
      :product="product"
      :conditions="conditions"
    />
    <Step2
      v-if="route().current('front.product.step2')"
      :product="product"
      :conditions="conditions"
    />
    <Step3
      v-if="route().current('front.product.step3')"
      :product="product"
      :conditions="conditions"
    />
    <Step4
      v-if="route().current('front.product.step4')"
      :product="product"
      :conditions="conditions"
    />
    <Step5
      v-if="route().current('front.product.step5')"
      :product="product"
      :conditions="conditions"
    />
    <Step6
      v-if="route().current('front.product.step6')"
      :product="product"
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
    conditions: Array,
    numSteps: {
      type: Number,
      default: 6,
    },
  },
  computed: {
    current_step() {
      // console.log(this.$inertia.page.url.slice(-1));
      // ATTENTION: it only works with max 9 steps
      return this.$inertia.page.url.slice(-1);
    },
  },
};
</script>
