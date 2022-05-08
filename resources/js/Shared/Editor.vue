<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ __(label) }}:</label>
    <editor-content
      :id="id"
      v-bind="{ ...$attrs, class: null }"
      :editor="editor"
      class="tiptap-editor form-editor"
      :class="{ error: error }"
    />
  </div>
  <div v-if="error" class="form-error">{{ error }}</div>
</template>

<script>
import { v4 as uuid } from "uuid";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";

export default {
  inheritAttrs: false,

  components: {
    EditorContent,
  },

  props: {
    id: {
      type: String,
      default() {
        return `editor-input-${uuid()}`;
      },
    },
    modelValue: {
      type: String,
      default: "",
    },
    error: String,
    label: String,
  },

  data() {
    return {
      editor: null,
    };
  },

  watch: {
    modelValue(value) {
      // HTML
      const isSame = this.editor.getHTML() === value;

      // JSON
      // const isSame = JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)

      if (isSame) {
        return;
      }

      this.editor.commands.setContent(value, false);
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [StarterKit],
      content: this.modelValue,
      onUpdate: () => {
        // HTML
        this.$emit("update:modelValue", this.editor.getHTML());

        // JSON
        // this.$emit('update:modelValue', this.editor.getJSON())
      },
    });
  },

  beforeUnmount() {
    this.editor.destroy();
  },
};
</script>
