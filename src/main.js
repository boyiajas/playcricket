import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js/dist/vue.es';
import App from './App.vue';
import './style.css';
import { Ziggy } from './ziggy';

console.log('Mounting Vue app...');
try {
    const app = createApp(App);
    // app.use(ZiggyVue, Ziggy);
    app.mount('#app');
    console.log('Vue app mounted successfully');
} catch (error) {
    console.error('Error mounting Vue app:', error);
}
