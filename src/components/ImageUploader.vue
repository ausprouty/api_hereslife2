<template>
    <div class="image-uploader">
      <input type="file" @change="handleImageUpload" />
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const handleImageUpload = async (event) => {
    const file = event.target.files[0];
    if (file) {
      const formData = new FormData();
      formData.append('image', file);
      try {
        const response = await axios.post('https://api.hereslife.com/images/emails', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        emit('imageUploaded', response.data.imageUrl);
        alert('Image uploaded successfully!');
      } catch (error) {
        console.error('Failed to upload image:', error);
        alert('Image upload failed.');
      }
    }
  };
  
  defineEmits(['imageUploaded']);
  </script>
  
  <style scoped>
  .image-uploader input[type="file"] {
    display: block;
    margin-bottom: 10px;
  }
  </style>
  