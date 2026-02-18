import path from 'path';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    port: 3000,
    host: '0.0.0.0',
    hmr: {
      host: 'localhost',
    },
  },
  plugins: [
    laravel({
      input: ['src/main.js'],
      publicDirectory: 'backend/laravel-app/public',
      buildDirectory: 'build',
      hotFile: 'backend/laravel-app/public/hot',
      refresh: ['backend/laravel-app/resources/views/**'],
    }),
    vue(),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
});
