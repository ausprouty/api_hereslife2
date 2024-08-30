import { defineStore } from 'pinia';
import axios from 'axios';

const apiUrl = import.meta.env.VITE_APP_API_URL;

const apiKey = import.meta.env.VITE_APP_HL_API_KEY;
console.log(import.meta.env)

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
  },
  
  actions: {
    async administratorExists() {
      try {
        console.log (`${apiUrl}admin/exists`);
        userData.apiKey = apiKey;
        const response = await axios.get(`${apiUrl}admin/exists`, userData);
        this.token = response.data.token;
        this.user = response.data.user;
      } catch (error) {
        console.error('Registration failed', error);
      }
    },
    async register(userData) {
      try {
        console.log (`${apiUrl}admin/register`);
        userData.apiKey = apiKey;
        console.log (userData);
        const response = await axios.post(`${apiUrl}admin/create`, userData);
        this.token = response.data.token;
        this.user = response.data.user;
      } catch (error) {
        console.error('Registration failed', error);
      }
    },

    async login(credentials) {
      try {
        credentials.apiKey = apiKey;
        const response = await axios.post(`${apiUrl}admin/login`, credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        // Optionally, store token in localStorage
        localStorage.setItem('authToken', this.token);
      } catch (error) {
        console.error('Login failed', error);
      }
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('authToken');
    },

    async checkAuth() {
      const token = localStorage.getItem('authToken');
      if (token) {
        try {
          const response = await axios.get(`${apiUrl}user/authentication`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.user = response.data.user;
          this.token = token;
        } catch (error) {
          this.logout();
        }
      }
    },
    async checkUserExists() {
        try {
          const response = await axios.get('your-api-url/checkUserExists');
          this.userExists = response.data.exists;
        } catch (error) {
          console.error('Failed to check if user exists:', error);
          this.userExists = false;
        }
      },
  },
});
