// src/services/axiosService.js

/*
Example request without userId
axiosInstance.get('/login', { skipUserId: true });

Example request with userId automatically appended
axiosInstance.get('/profile');

This will automatically append the userId to the URL, like this:
?u=12345
*/

import axios from 'axios';

const apiUrl = import.meta.env.VITE_APP_API_URL;

const axiosInstance = axios.create({
  baseURL: apiUrl,
});

axiosInstance.interceptors.request.use(config => {
  const siteToken = import.meta.env.VITE_APP_HL_API_KEY;
  const userToken = sessionStorage.getItem('userToken');
  const userId = sessionStorage.getItem('userId');
  
  if (siteToken) {
    config.headers['Authorization'] = `Bearer ${siteToken}`;
  }
  
  if (userToken) {
    config.headers['User-Authorization'] = `Bearer ${userToken}`;
  }
  // Append userId as a query parameter `u` if:
  // 1. The userId exists.
  // 2. The request config does not have a `skipUserId` flag set to true.
  if (userId && !config.skipUserId) {
    config.params = config.params || {};
    config.params['u'] = userId;
  }

  console.log('Modified Axios Request Config:', config);
  return config;
}, error => {
  return Promise.reject(error);
});

export default axiosInstance;
