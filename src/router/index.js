import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/AuthStore'; // Import your Pinia store

import SeriesEditor from '@/views/SeriesEditor.vue'; // Adjust the path as necessary
import HelloWorld from '@/views/HelloWorld.vue'; // Adjust the path as necessary
import EmailSendDirect from '@/views/EmailSendDirect.vue'; 
import EmailSendQue from '@/views/EmailSendQue.vue';
import LoginUser from '@/views/LoginUser.vue';

const routes = [
  
  {
    path: '/hello-world',
    name: 'HelloWorld',
    component: HelloWorld,
  },
  {
    path: '/email/series/:series?/:sequence?',
    name: 'SeriesEditor',
    component: SeriesEditor,
  },
  {
    path: '/email/direct',
    name: 'EmailSendDirect',
    component: EmailSendDirect,
  },
  {
    path: '/email/que',
    name: 'EmailSendQue',
    component: EmailSendQue,
  },
  {
    path: '/',
    name: 'LoginUser',
    component: LoginUser,
  },
  
  // Add more routes here
];

const router = createRouter({
  history: createWebHistory('/'),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore(); // Access the Pinia store

  // Check if the route is the login page, if so, allow access
  if (to.name === 'LoginUser') {
    next();
  } else {
    // If not, check if the user is authenticated
    if (!authStore.isAuthenticated) {
      next({ name: 'LoginUser' });
    } else {
      next();
    }
  }
});

export default router;
