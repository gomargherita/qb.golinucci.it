<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ __(label) }}:</label>
    <input
      :id="id"
      v-bind="{ ...$attrs, class: null }"
      class="rounded border-gray-300 text-primary shadow-sm focus:border-secondary-400 focus:ring focus:ring-primary focus:ring-opacity-50"
      :class="{ error: error }"
      type="checkbox"
      :value="value"
      v-model="proxyChecked"
    />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `checkbox-input-${uuid()}`;
      },
    },
    checked: {
      type: [Array, Boolean],
      default: false,
    },
    value: {
      default: null,
    },
    error: String,
    label: String,
  },
  emits: ["update:checked"],
  computed: {
    proxyChecked: {
      get() {
        return this.checked;
      },
      set(val) {
        this.$emit("update:checked", val);
      },
    },
  },
};
</script>
