<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ __(label) }}:</label>
    <div class="editor-menu">
      <div class="inline-flex rounded-md shadow-sm" role="group">
        <button
          type="button"
          class="rounded-tl-lg py-2 px-4 text-sm font-medium text-gray-900 bg-white border-t border-x border-gray-200 hover:bg-gray-100 hover:text-primary focus:z-10"
          tabindex="-1"
          title="Bold"
          @click="editor.chain().focus().toggleBold().run()"
        >
          {{ __("Bold") }}
        </button>
        <button
          type="button"
          class="rounded-tr-lg py-2 px-4 text-sm font-medium text-gray-900 bg-white border-t border-x border-gray-200 hover:bg-gray-100 hover:text-primary focus:z-10"
          tabindex="-1"
          title="Italic"
          @click="editor.chain().focus().toggleItalic().run()"
        >
          {{ __("Italic") }}
        </button>
      </div>
    </div>
    <editor-content
      :id="id"
      v-bind="{ ...$attrs, class: null }"
      :editor="editor"
      class="tiptap-editor form-editor"
      :class="{ error: error }"
    />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from "uuid";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Button from "@/Shared/Button";

export default {
  inheritAttrs: false,

  components: {
    EditorContent,
    Button,
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
