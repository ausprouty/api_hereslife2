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
        const response = await axiosService.get('admin/exists',{ skipUserId: true });
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
        console.log(userData);
        const response = await axiosService.post('admin/create', userData,{ skipUserId: true });
        if (response.message == 'success'){
          console.log('admin created successfully');
          this.token = response.data.token;
          this.user = response.data.user;
          this.administratorExists = true;
          // Optionally, store token in sessionStorage
          sessionStorage.setItem('userToken', this.token);
          sessionStorage.setItem('userId', this.user);
        }
        else{
          alert('Administrator not created. Reprogramming required');
          this.administratorExists = false;
        }
      } catch (error) {
        console.error('Registration failed', error);
      }
    },

    async login(credentials) {
      try {
        console.log(credentials);
        const response = await axiosService.post('admin/login', credentials,{ skipUserId: true });
        this.token = response.data.token;
        this.user = response.data.user;
        // Optionally, store token in sessionStorage
        sessionStorage.setItem('userToken', this.token);
        sessionStorage.setItem('userId', this.user);
      } catch (error) {
        console.error('Login failed', error);
      }
    },

    logout() {
      this.token = null;
      this.user = null;
      sessionStorage.removeItem('userToken');
      sessionStorage.removeItem('userId');
    },

    async checkAuth() {
      const token = sessionStorage.getItem('userToken');
      if (token) {
        try {
          const response = await axiosService.get('user/authentication' );
          this.user = response.data.user;
          this.token = token;
        } catch (error) {
          this.logout();
        }
      }
    },
  },
  
});
