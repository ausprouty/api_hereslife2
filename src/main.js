import { createApp } from 'vue'
import './styles/global.css'
import App from './App.vue'
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import router from './router'; // Import the router
import { CKEditor } from '@ckeditor/ckeditor5-vue';

const app = createApp(App);

// Register CKEditor globally
app.component('CKEditor', CKEditor);
app.use(createPinia());
pinia.use(piniaPluginPersistedstate);
app.use(router).mount('#app');



