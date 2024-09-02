import { defineStore } from 'pinia';
import axiosService from '@/services/axiosService';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    administratorExists: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
  },

  actions: {
    async checkIfAdministratorExists() {
      try {
        const response = await axiosService.get('admin/exists');
        console.log (response)
        if (response.data.data == 'TRUE'){
          console.log('admin exists');
          this.administratorExists = true; 
        }
        else{
          console.log('admin does not exist');
          this.administratorExists = false; 

        }

      } catch (error) {
        console.error('Failed to check if admin exists:', error);
        this.administratorExists = false; // Handle error case by setting a default value
      }
    },

    async register(userData) {
      try {
        const response = await axiosService.post('admin/create', userData);
        this.token = response.data.token;
        this.user = response.data.user;
        this.administratorExists = true;
        // Optionally, store token in localStorage
        localStorage.setItem('authToken', this.token);
  
      } catch (error) {
        console.error('Registration failed', error);
      }
    },

    async login(credentials) {
      try {
        console.log(credentials);
        const response = await axiosService.post('admin/login', credentials);
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
          const response = await axiosService.get('user/authentication', {
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
  },
  
});
