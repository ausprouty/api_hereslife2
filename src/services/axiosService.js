// src/services/axiosService.js
import axios from 'axios';

const apiUrl = import.meta.env.VITE_APP_API_URL;

const axiosInstance = axios.create({
  baseURL: apiUrl,
});

axiosInstance.interceptors.request.use(config => {
  const envToken = import.meta.env.VITE_APP_HL_API_KEY;
  const localStorageToken = localStorage.getItem('auth_token');
  
  if (envToken) {
    config.headers['Site-Authorization'] = `Bearer ${envToken}`;
  }
  
  if (localStorageToken) {
    config.headers['User-Authorization'] = `Bearer ${localStorageToken}`;
  }
   // Log the modified config object after setting headers
   console.log('Modified Axios Request Config:', config);
  return config;
}, error => {
  return Promise.reject(error);
});

export default axiosInstance;
