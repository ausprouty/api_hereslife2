<template>
  <div class="tinymce-editor">
    <editor
      :api-key="apiKey"
      :init="editorConfig"
      :value="modelValue"
      @EditorChange="handleEditorChange"
      @init="handleEditorInit"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Editor from '@tinymce/tinymce-vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const apiUrl = import.meta.env.VITE_APP_API_URL;
const apiKey = import.meta.env.VITE_APP_HL_API_KEY;
const apiBackendUrl = apiUrl + 'email/images/upload/tinymce';

// Define the TinyMCE configuration using a ref
const editorConfig = ref({
  height: 500,
  width: '100%',
  menubar: true,
  plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
  toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | image',
  automatic_uploads: true,
  images_upload_url: apiBackendUrl,
});

const handleEditorInit = (editor) => {
  console.log('TinyMCE Editor Initialized');
};

// Handle content changes in TinyMCE editor
const handleEditorChange = (event) => {
  const content = event.editor.getContent();
  console.log('Editor content changed:', content);
  emit('update:modelValue', content);
};
</script>

<style scoped>
.tinymce-editor {
  width: 90%;
  margin: 0 auto;
  padding: 20px;
  background-color: #e4e4e4;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
