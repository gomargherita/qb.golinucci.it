<template>
  <div>
  <div class="mt-8">
    <p class="text-center">
      <br />
      <span class="font-bold text-lg">
        Polizza registrata.
      </span>
    </p>
  </div>
</div>
</template>

<script>
import HTwo from "@/Shared/Front/HTwo";
import Layout from "@/Shared/Layouts/Layout";



export default {
  components: {
    HTwo,
  },
  layout: Layout,

  props: {
    cart: Object,
    
  },
  data() {
    return {
      cartid: this.cart.id
    };
  },
  mounted() {
    setTimeout(() => {
      console.log("prova");
      // Assuming you have 'router' defined somewhere in your component
      router.push({ name: 'carts.edit', params: { cartid: this.cartid } });
    }, 1000);
  },
};
</script>
