<template>
  <div ref="buy">
    <HTwo :title="__('Buy this Product')" />

    <div class="text-center mx-auto">
        Widget per punti
    </div>

    <Step1 :product="product" :conditions="conditions" />
    <!-- <Step2 :product="product" :conditions="conditions" /> -->
  </div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Step1 from "@/Shared/Front/Steps/Step1";
import Step2 from "@/Shared/Front/Steps/Step2";

export default {
  components: {
    HTwo,
    Step1,
    Step2,
  },
  props: {
    product: Object,
    conditions: Array,
  },
};
</script>
