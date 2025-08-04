import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

// Import components
import OwnerDashboard from './components/OwnerDashboard.vue';
import ReceptionDashboard from './components/ReceptionDashboard.vue';
import WaiterDashboard from './components/WaiterDashboard.vue';
import KitchenDashboard from './components/KitchenDashboard.vue';
import TableLayout from './components/TableLayout.vue';
import OrderManagement from './components/OrderManagement.vue';
import InventoryManagement from './components/InventoryManagement.vue';
import Login from './components/Login.vue';

console.log('Starting Restaurant Management App...');

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Setup Echo for real-time features
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

// Router configuration
const routes = [
    { path: '/', name: 'login', component: Login },
    { path: '/owner', name: 'owner', component: OwnerDashboard },
    { path: '/reception', name: 'reception', component: ReceptionDashboard },
    { path: '/waiter', name: 'waiter', component: WaiterDashboard },
    { path: '/kitchen', name: 'kitchen', component: KitchenDashboard },
    { path: '/tables', name: 'tables', component: TableLayout },
    { path: '/orders', name: 'orders', component: OrderManagement },
    { path: '/inventory', name: 'inventory', component: InventoryManagement },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Create Vue app
const app = createApp({
    data() {
        return {
            user: null,
            notifications: [],
            isConnected: false
        }
    },
    async mounted() {
        console.log('Restaurant Management System loaded');
        await this.loadUser();
        this.setupRealTimeListeners();
    },
    methods: {
        async loadUser() {
            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                console.error('Failed to load user:', error);
            }
        },
        setupRealTimeListeners() {
            if (window.Echo) {
                // Listen for order updates
                window.Echo.channel('restaurant-updates')
                    .listen('OrderCreated', (e) => {
                        this.handleOrderCreated(e);
                    })
                    .listen('OrderUpdated', (e) => {
                        this.handleOrderUpdated(e);
                    })
                    .listen('InventoryUpdated', (e) => {
                        this.handleInventoryUpdated(e);
                    })
                    .listen('TableStatusChanged', (e) => {
                        this.handleTableStatusChanged(e);
                    });

                // Private notifications for each user
                if (this.user) {
                    window.Echo.private(`user.${this.user.id}`)
                        .notification((notification) => {
                            this.notifications.unshift(notification);
                        });
                }

                this.isConnected = true;
            }
        },
        handleOrderCreated(event) {
            this.$emit('order-created', event.order);
        },
        handleOrderUpdated(event) {
            this.$emit('order-updated', event.order);
        },
        handleInventoryUpdated(event) {
            this.$emit('inventory-updated', event.item);
        },
        handleTableStatusChanged(event) {
            this.$emit('table-status-changed', event.table);
        }
    },
    provide() {
        return {
            $echo: window.Echo,
            $user: this.user
        }
    }
});

// Register global components
app.component('owner-dashboard', OwnerDashboard);
app.component('reception-dashboard', ReceptionDashboard);
app.component('waiter-dashboard', WaiterDashboard);
app.component('kitchen-dashboard', KitchenDashboard);
app.component('table-layout', TableLayout);
app.component('order-management', OrderManagement);
app.component('inventory-management', InventoryManagement);

app.use(router);

// Mount the app
const appElement = document.getElementById('app');
if (appElement) {
    app.mount('#app');
    console.log('Restaurant Management System mounted successfully');
} else {
    console.error('No #app element found in DOM');
}