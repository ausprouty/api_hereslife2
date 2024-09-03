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
        const { data } = await axiosService.get('admin/exists', { skipUserId: true });
        this.administratorExists = data.data === 'TRUE';
      } catch (error) {
        console.error('Failed to check if admin exists:', error);
        this.administratorExists = false;
      }
    },

    async register(userData) {
      try {
        const { data } = await axiosService.post('admin/create', userData, { skipUserId: true });
        if (data.success === 'TRUE') {
          this.token = data.token;
          this.user = data.user;
          this.administratorExists = true;
        } else {
          alert('Administrator not created. Reprogramming required');
          this.administratorExists = false;
        }
      } catch (error) {
        console.error('Registration failed', error);
      }
    },

    async login(credentials) {
      try {
        const { data } = await axiosService.post('admin/login', credentials, { skipUserId: true });
        if (data.success === 'FALSE') {
          alert('Invalid username or password');
          return 'Invalid username or password';
        }
        this.token = data.token;
        this.user = data.user;
        return 'Success';
      } catch (error) {
        console.error('Login failed', error);
      }
    },

    logout() {
      this.token = null;
      this.user = null;
    },

    async checkAuth() {
      const token = sessionStorage.getItem('auth'); // Assuming the full state is persisted
      if (token) {
        try {
          const { data } = await axiosService.get('user/authentication');
          this.user = data.user;
          this.token = token;
        } catch (error) {
          this.logout();
        }
      }
    },
  },

  persist: {
    enabled: true,
    strategies: [
      {
        key: 'auth',
        storage: sessionStorage,
      },
    ],
  },
});
