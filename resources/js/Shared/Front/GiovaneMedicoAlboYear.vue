<template>
  <div v-if="currentYear" class="text-center mt-6">
    <p class="mb-3">
      {{ __("To get a quote, enter the year of enrollment in the professional register") }}
    </p>
    <label class="block mb-2 font-medium" for="albo_enrollment_year">
      {{ __("Year of enrollment in the professional register") }}
    </label>
    <select
      id="albo_enrollment_year"
      class="form-select mx-auto"
      :value="modelValue == null ? '' : modelValue"
      @change="$emit('update:modelValue', $event.target.value ? Number($event.target.value) : null)"
    >
      <option value="">{{ __("Select year") }}</option>
      <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
    </select>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: [Number, String],
    currentYear: Number,
    minYear: Number,
  },
  emits: ["update:modelValue"],
  computed: {
    years() {
      const max = this.currentYear || new Date().getFullYear();
      const min = this.minYear || max - 10;
      const list = [];
      for (let y = max; y >= min; y--) {
        list.push(y);
      }
      return list;
    },
  },
  mounted() {
    if (this.modelValue) {
      return;
    }
    try {
      const saved = window.localStorage.getItem("qb_albo_enrollment_year");
      if (saved) {
        this.$emit("update:modelValue", Number(saved));
      }
    } catch (e) {}
  },
  watch: {
    modelValue(v) {
      try {
        if (v) {
          window.localStorage.setItem("qb_albo_enrollment_year", String(v));
        }
      } catch (e) {}
    },
  },
};
</script>
