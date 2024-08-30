import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig(({ mode }) => {
  // Load the environment variables from the appropriate .env file
  const env = loadEnv(mode, process.cwd(), '');

  return {
    plugins: [
      vue({
        template: {
          compilerOptions: {
            // Treat 'ckeditor' as a custom element
            isCustomElement: (tag) => tag === 'ckeditor',
          },
        },
      }),
    ],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, './src'),  // Default alias for 'src'
        //'@ckeditor': '/node_modules/@ckeditor'  // Alias for CKEditor
      },
    },
    // You can use the loaded environment variables like this:
    define: {
      'process.env': env,  // This makes the env variables available in your app
    },
  };
});
