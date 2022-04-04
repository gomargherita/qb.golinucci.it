<template>
  <footer id="footer" class="w-full mx-auto bg-white my-8 border-t-8 border-customgray">
    <div class="container mx-auto flex px-3 md:px-0 py-8">
      <div class="w-full mx-auto flex flex-wrap">
        <div class="flex w-full lg:w-1/2">
          <div class="px-3 md:px-0">
            <p class="py-4">
              <logo class="hidden md:block fill-white" width="240" height="56" />
              <logo class="md:hidden fill-white" width="120" height="28" />
            </p>
            <p class="bg-customgray text-white p-1 mx-0 md:mx-2 text-xs">
              Q&amp;B Golinucci è un prodotto Golinucci srl
            </p>
          </div>
        </div>
        <div class="flex w-full lg:w-1/2 lg:justify-end lg:text-right">
          <div class="px-3 md:px-0">
            <p class="py-4">
              <logo class="hidden md:block fill-white" width="240" height="56" />
              <logo class="md:hidden fill-white" width="120" height="28" />
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="flex py-8 bg-customgray">
      <div class="container mx-auto">
        <div class="px-6">
          <p>asda</p>
        </div>
      </div>
    </div>
    <div class="flex py-8 bg-white">
      <div class="container mx-auto">
        <div class="px-6">
          <p>asda</p>
        </div>
      </div>
    </div>
  </footer>
</template>
<script>
import Logo from "@/Shared/Logo";

export default {
  components: {
    Logo,
  },
};
</script>
