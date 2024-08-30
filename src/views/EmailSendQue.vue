<template>
    <div class="letter-sender">
      <h2>Select a Letter</h2>
      <select v-model="selectedLetter" @change="viewLetter">
        <option v-for="letter in letters" :key="letter.id" :value="letter.id">
          {{ letter.subject }}
        </option>
      </select>
  
      <div v-if="selectedLetterContent" class="letter-content">
        <h3>{{ selectedLetterContent.title }}</h3>
        <p v-html="selectedLetterContent.body"></p>
      </div>
  
      <h2>Select a Group</h2>
      <select v-model="selectedGroup">
        <option v-for="group in groups" :key="group.code" :value="group.code">
          {{ group.name }}
        </option>
      </select>
  
      <button @click="sendLetter" class="send-button">Que Letters for Sending</button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  const apiUrl = import.meta.env.VITE_APP_API_URL;
  const hlApiKey = import.meta.env.VITE_APP_HL_API_KEY;

  const letters = ref([]);
  const groups = ref([
    { code: 'test', name: 'Test'},
    { code: 'australia', name: 'Australia' },
    { code: 'australia_not_power_to_change', name: 'Australia (Not Power to Change)' },
    { code: 'australia_nsw', name: 'Australia NSW' },
    { code: 'australia_nt', name: 'Australia NT' },
    { code: 'australia_qld', name: 'Australia QLD' },
    { code: 'australia_sa', name: 'Australia SA' },
    { code: 'australia_vic', name: 'Australia VIC' },
    { code: 'canada', name: 'Canada' },
    { code: 'french_speaking_countries', name: 'French-speaking Countries' },
    { code: 'indian', name: 'Indian' },
    { code: 'lunar_new_year', name: 'Lunar New Year' },
    { code: 'muslim', name: 'Muslim' },
    { code: 'non_muslim', name: 'Non-Muslim' },
    { code: 'not_australia', name: 'Not Australia' },
    { code: 'not_usa', name: 'Not USA' },
    { code: 'usa', name: 'USA' },
  ]);
  
  const selectedLetter = ref(null);
  const selectedGroup = ref(null);
  const selectedLetterContent = ref(null);
  
  const fetchLetters = async () => {
    try {
      const response = await axios.get(apiUrl + 'email/blog/recent/5');
      letters.value = response.data.data; // Assuming the API returns an array of letters
      console.log('Fetched letters:', letters.value);
    } catch (error) {
      console.error('Error fetching letters:', error);
      alert('Failed to fetch letters');
    }
  };
  
  onMounted(fetchLetters);
  
  const viewLetter = async () => {
    try {
      const response = await axios.get(apiUrl + 'email/view/' + selectedLetter.value); 
      console.log('Fetched letter content:', response);
      selectedLetterContent.value = response.data.data;
    } catch (error) {
      console.error('Error fetching letters:', error);
      alert('Failed to fetch letters');
    }
  };
  
  const sendLetter = async () => {
    if (selectedLetter.value && selectedGroup.value) {
      const payload = {
        data: {
          letterId: selectedLetter.value,
          groupCode: selectedGroup.value,
          apiKey: hlApiKey
        }
      };
      try {
        const response = await axios.post(apiUrl + 'email/que/emails', payload);
        alert (response.data.message)
      } catch (error) {
        alert('Failed to send letter');
        console.error(error);
      }
    } else {
      alert('Please select both a letter and a group.');
    }
  };
  </script>
  
  <style>
  
  </style>
  