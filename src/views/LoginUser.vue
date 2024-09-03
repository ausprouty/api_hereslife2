<template>
  <div>
    <!-- Show the registration form if no user exists -->
    <div v-if="!authStore.administratorExists">
      <h2>Register</h2>
      <form @submit.prevent="handleRegister">
      <div class="form-group">
          <label for="first_name">First Name:</label>
          <input type="text" class="short-input" v-model="registerData.first_name" required />
        </div>
        <div class="form-group">
          <label for="last_name">Last Name:</label>
          <input type="text" class="short-input"  v-model="registerData.last_name" required />
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" class="short-input"  v-model="registerData.username" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="short-input" v-model="registerData.password" required />
        </div>
        <button type="submit">Register</button>
      </form>
    </div>

    <!-- Show the login form if a user already exists -->
    <div v-else>
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text"  class="short-input" v-model="loginData.username" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="short-input" v-model="loginData.password" required />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script>
import { useRouter, useRoute } from 'vue-router';
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/AuthStore';

export default {
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const route = useRoute();
    const registerData = ref({
      first_name: '',
      last_name: '',
      username: '',
      password: '',
    });

    const loginData = ref({
      username: '',
      password: ''
    });

    const handleRegister = async () => {
      try {
        const response = await authStore.register(registerData.value);
        console.log (response)
      } catch (error) {
        console.error('Registration failed:', error);
      }
    };

    const handleLogin = async () => {
      try {
        const response = await authStore.login(loginData.value);
        console.log
        if (response == 'Success') {
          // Redirect to the dashboard after successful login
          router.push('/email/series/any/1');
        }
      } catch (error) {
        console.error('Login failed:', error);
      }
    };

    onMounted(() => {
      // Check if the administratpor exists when the component is mounted
      authStore.checkIfAdministratorExists();
    });

    return {
      authStore,
      registerData,
      loginData,
      handleRegister,
      handleLogin,
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
