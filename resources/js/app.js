import { createApp } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import ReceptionDashboard from './components/ReceptionDashboard.vue';
import WaiterOrders from './components/WaiterOrders.vue'; // Assuming this component
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

console.log('Starting app.js...');

window.Pusher = Pusher;
try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY || 'dbojjm0nblmau0sqzoki',
        wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
        wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
        forceTLS: false,
        enabledTransports: ['ws'],
    });
    console.log('Echo initialized:', window.Echo);
} catch (error) {
    console.error('Echo initialization failed:', error);
}

const app = createApp({
    mounted() {
        console.log('Vue app mounted successfully');
    },
    errorCaptured(err, instance, info) {
        console.error('Vue error:', err, instance, info);
        return false;
    },
});

app.component('reception-dashboard', ReceptionDashboard);
app.component('waiter-orders', WaiterOrders);

const appElement = document.getElementById('app');
if (appElement) {
    const mounted = app.mount('#app');
    console.log('Mounted app:', mounted);
} else {
    console.error('No #app element found in DOM');
}