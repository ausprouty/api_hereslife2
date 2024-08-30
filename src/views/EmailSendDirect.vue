<template>
  <div class="email-editor">
    <h1>Email Editor</h1>

    <div v-if="loading" class="loading">Loading...</div>

    <form v-else @submit.prevent="saveEmail">
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input
          type="text"
          size="50"
          id="email"
          v-model="email.address"
          class="form-control short-input"
          :readonly="isEdit"
          required
        />
      </div>

      <div class="form-group">
        <label for="email-subject">Subject:</label>
        <input
          type="text"
          size="50"
          id="email-subject"
          v-model="email.subject"
          class="form-control"
          required
        />
      </div>

      <div class="form-group">
        <!-- Display Image URL or Error Message -->
        <div v-if="email.imageUrl" class="upload-info">Image URL: {{ email.imageUrl }}</div>
        <div v-if="email.errorMessage" class="error-message">{{ email.errorMessage }}</div>

        <editor
          :api-key="tinyMCEApiKey"
          :init="editorConfig"
          v-model="email.body"
        />
      </div>

      <div class="form-group button-group">
        <button type="submit" class="save-button">Send Email</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import Editor from '@tinymce/tinymce-vue';

const hlApiKey = import.meta.env.VITE_APP_HL_API_KEY; // Use apiKey consistently
const tinyMCEApiKey = import.meta.env.VITE_APP_TINYCME_API_KEY
const route = useRoute();
const apiUrl = import.meta.env.VITE_APP_API_URL;
console.log ('apiUrl', apiUrl);
const apiSendEmail = `${apiUrl}email/send`;
const imageUploadUrl = `${apiUrl}email/images/upload/tinymce`;
console.log ('imageUploadUrl', imageUploadUrl);

const email = ref({
  subject: '',
  address: '',
  body: '',
  imageUrl: '',
  errorMessage: ''
});
const loading = ref(false);
const isEdit = ref(false);

// Define the TinyMCE configuration using a ref
const editorConfig = ref({
  height: 500,
  width: '100%',
  menubar: true,
  plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
  toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | image',
  automatic_uploads: true,
  images_upload_credentials: false,
  images_upload_url: imageUploadUrl,
  images_upload_handler: handleImageUpload,
});

async function handleImageUpload(blobInfo) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();
    xhr.open('POST', imageUploadUrl);

    xhr.onload = () => {
      if (xhr.status < 200 || xhr.status >= 300) {
        reject(`HTTP Error: ${xhr.status}`);
        return;
      }
      try {
        const jsonResponse = JSON.parse(xhr.responseText);
        if (jsonResponse && jsonResponse.location) {
          resolve(jsonResponse.location);
        } else {
          reject(`Invalid JSON: ${xhr.responseText}`);
        }
      } catch (e) {
        reject(`Failed to parse JSON: ${xhr.responseText}`);
      }
    };

    xhr.onerror = () => {
      reject(`Image upload failed due to a XHR Transport error. Code: ${xhr.status}`);
    };

    // Add the API key as a field in the FormData
    formData.append('apiKey', hlApiKey);
    formData.append('file', blobInfo.blob());

    xhr.send(formData);
  });
}

const saveEmail = async () => {
  try {
    loading.value = true;
    const data = {
      action: 'create',
      data: email.value,
    };
    data.data.apiKey = apiKey;
    const response = await axios.post(apiSendEmail, data);
    console.log('Email saved successfully:', response);
    email.value = {
      address: '',
      subject: '',
      body: '',
      imageUrl: '',
      errorMessage: ''
    };
    isEdit.value = false;
  } catch (error) {
    console.error('Failed to save email:', error);
    email.value.errorMessage = 'Failed to save email. Please try again.';
  } finally {
    loading.value = false;
  }
};

watch(route, initializeEmail);

function initializeEmail() {
  console.log('Initializing email form');
  // Implement initialization logic if needed
}
</script>

<style scoped>
.email-editor {
  width: 90%;
  margin: 0 auto;
  padding: 20px;
  background-color: #e4e4e4;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-group {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.form-group label {
  margin-right: 10px;
  font-weight: bold;
}

.form-control,
#editor {
  flex: 1;
  min-width: 150px;
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.short-input {
  width: 100%;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.save-button {
  flex: 1;
  max-width: 200px;
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  border-radius: 4px;
  font-size: 16px;
}

.save-button:hover {
  background-color: #0056b3;
}

.loading {
  text-align: center;
  font-size: 18px;
  color: #007bff;
}

.upload-info {
  color: green;
  font-size: 14px;
  margin-top: 10px;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}
</style>
