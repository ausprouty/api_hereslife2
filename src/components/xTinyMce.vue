<template>
    <div>
      <div v-if="!showHtml">
        <textarea id="editor"></textarea>
      </div>
      <div v-else>
        <textarea v-model="editorData" rows="10" style="width: 100%;"></textarea>
      </div>
      <div>
        <button @click="toggleHtmlView">{{ showHtml ? 'Switch to Rich Text' : 'Edit HTML Source' }}</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  
  const editorData = ref('<p>This is the initial content of the editor.</p>');
  const showHtml = ref(false);
  let editorInstance = null;
  
  const toggleHtmlView = () => {
    if (showHtml.value) {
      // Switch to Rich Text
      tinymce.activeEditor.setContent(editorData.value);
      tinymce.activeEditor.show();
    } else {
      // Switch to HTML Source
      editorData.value = tinymce.activeEditor.getContent();
      tinymce.activeEditor.hide();
    }
    showHtml.value = !showHtml.value;
  };
  
  onMounted(() => {
    tinymce.init({
      selector: '#editor',
      plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
      toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
      height: 500,
      setup(editor) {
        editorInstance = editor;
        editor.on('init', () => {
          editor.setContent(editorData.value);
        });
        editor.on('change', () => {
          editorData.value = editor.getContent();
        });
      }
    });
  });
  
  // Ensure TinyMCE content is updated when switching back to the rich text view
  watch(showHtml, (newVal) => {
    if (!newVal && editorInstance) {
      editorInstance.setContent(editorData.value);
    }
  });
  
  </script>
  
  <style scoped>
  /* Add any additional styling as needed */
  </style>
  