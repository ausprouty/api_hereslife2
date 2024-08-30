<template>
  <div class="email-editor">
    <h1>Email Editor</h1>

    <div v-if="loading" class="loading">Loading...</div>

    <form v-else @submit.prevent="saveEmail">
      <div class="form-group">
        <label for="series">Series:</label>
        <input
          type="text"
          size="35"
          id="series"
          v-model="email.series"
          class="form-control short-input"
          :readonly="isEdit"
          required
        />

        <label for="sequence" class="right-label">Sequence:</label>
        <input
          type="number"
          size="4"
          id="sequence"
          v-model="email.sequence"
          class="form-control right-input"
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
        <div v-if="email.imageUrl" class="upload-info">Image URL: {{ email.imageUrl }}</div>
        <div v-if="email.errorMessage" class="error-message">{{ email.errorMessage }}</div>

        <editor
          :api-key="tinyMCEApiKey"
          :init="editorConfig"
          v-model="email.body"
        />
      </div>

      <div class="form-group button-group">
        <button type="submit" class="save-button">Save Email</button>
        <button type="button" class="load-button" @click="loadOrCreateEmail">
          Load/Create Email
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import Editor from '@tinymce/tinymce-vue';

const props = defineProps({
  series: {
    type: String,
    default: '',
  },
  sequence: {
    type: Number,
    default: 1,
  },
});

const hlApiKey = import.meta.env.VITE_APP_HL_API_KEY;
const tinyMCEApiKey = import.meta.env.VITE_APP_TINYCME_API_KEY
const route = useRoute();
const apiUrl = import.meta.env.VITE_APP_API_URL;
const imageUploadUrl = `${apiUrl}email/images/upload/tinymce`;

const email = ref({
  subject: '',
  series: '',
  sequence: 1,
  body: '',
  imageUrl: '',
  errorMessage: ''
});
const loading = ref(false);
const isEdit = ref(false);

const editorConfig = ref({
  height: 500,
  width: '100%',
  menubar: true,
  plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
  toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | image',
  automatic_uploads: true,
  images_upload_credentials: true,
  images_upload_url: imageUploadUrl,
  images_upload_handler: handleImageUpload,
});

function handleImageUpload(blobInfo) {
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
        if (jsonResponse?.location) {
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

    formData.append('apiKey', hlApiKey);
    formData.append('file', blobInfo.blob());

    xhr.send(formData);
  });
}

const initializeEmail = () => {
  email.value.series = route.params.series || props.series || '';
  email.value.sequence = route.params.sequence || props.sequence || 1;
};

const loadOrCreateEmail = async () => {
  loading.value = true;
  try {
    const { series, sequence } = email.value;
    if (series && sequence) {
      const response = await axios.get(`${apiUrl}email/series/${series}/${sequence}`);
      if (response.data.data) {
        email.value = { ...response.data.data };
        isEdit.value = true;
      } else {
        email.value.subject = '';
        email.value.body = '';
        isEdit.value = false;
      }
    } else {
      alert('Please enter a valid series and sequence.');
    }
  } catch (error) {
    console.error('Failed to load or create email:', error);
  } finally {
    loading.value = false;
  }
};

const saveEmail = async () => {
  try {
    loading.value = true;
    const data = {
      action: isEdit.value ? 'update' : 'create',
      data: email.value,
    };
    data.data.apiKey = apiKey;
    await axios.post(`${apiUrl}email/series`, data);

    email.value = {
      subject: '',
      series: email.value.series,
      sequence: '',
      body: '',
    };
    isEdit.value = false;
  } catch (error) {
    console.error('Failed to save email:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  initializeEmail();
  if (email.value.series && email.value.sequence) {
    loadOrCreateEmail();
  }
});

watch(route, initializeEmail);

onBeforeUnmount(() => {
  console.log("TinyMCE component will unmount");
});
</script>

<style scoped>

</style>
