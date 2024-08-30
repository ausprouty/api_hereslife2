<template>
  <div class="email-form">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" v-model="email" id="email" value="bobprouty12@gmail.com" required />
      </div>

      <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" v-model="subject" value="This is a Test" id="subject" required />
      </div>

      <div class="form-group">
        <label for="message">Message:</label>
        <TinyMce v-model="message" id="message" required />
      </div>

      <div class="form-group button-group">
        <button type="submit" class="send-button">Send Email</button>
      </div>
    </form>

    <div v-if="responseMessage" class="response-message">
      {{ responseMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import TinyMce from '@/components/TinyMce.vue';

// Define the email form state
const email = ref('');
const subject = ref('');
const message = ref('');
const responseMessage = ref('');
const hlApiKey = import.meta.env.VITE_APP_HL_API_KEY;
const apiKey = import.meta.env.VITE_APP_HL_API_KEY;
const apiBackendUrl = apiUrl + 'email/send';

// Handle form submission
const handleSubmit = async () => {
  try {
    console.log (message)
    const payload = {
      email: email.value,
      subject: subject.value,
      message: message.value,
      apiKey: apiKey
    };
    console.log ('payload', payload);

    const response = await axios.post(apiBackendUrl, payload);

    responseMessage.value = 'Email sent successfully!';
    email.value = '';
    subject.value = '';
    message.value = '';

  } catch (error) {
    responseMessage.value = 'Failed to send email. Please try again later.';
    console.error('Error sending email:', error);
  }
};
</script>

<style scoped>
.email-form {
  width: 90%;
  max-width: 800px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: 16px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.form-group label {
  font-weight: bold;
  margin-bottom: 8px;
}

.form-group input {
  width: 100%;
  max-width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

#subject {
  max-width: 100%; /* Extend the subject line to be longer */
}

.button-group {
  text-align: center;
  margin-top: 20px;
}

.send-button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 12px 24px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.send-button:hover {
  background-color: #45a049; /* Darker green */
}

.response-message {
  margin-top: 16px;
  color: green;
}
</style>
