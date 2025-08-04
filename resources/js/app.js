import { createApp } from 'vue';
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

// Make axios globally available
window.axios = axios;

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

// Simple toast notification system
const toast = {
    success: (message) => {
        console.log('✅ Success:', message);
        // You can replace this with a proper toast notification library later
        alert('Success: ' + message);
    },
    error: (message) => {
        console.error('❌ Error:', message);
        alert('Error: ' + message);
    },
    info: (message) => {
        console.log('ℹ️ Info:', message);
        alert('Info: ' + message);
    }
};

// Create Vue app
const app = createApp({
    data() {
        return {
            currentView: 'login', // login, owner, reception, waiter, kitchen
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
                // Auto-navigate based on user role if already logged in
                if (this.user) {
                    this.navigateToUserDashboard(this.user.role);
                }
            } catch (error) {
                console.log('User not logged in');
                this.currentView = 'login';
            }
        },
        navigateToUserDashboard(role) {
            switch (role) {
                case 'owner':
                case 'superadmin':
                    this.currentView = 'owner';
                    break;
                case 'reception':
                    this.currentView = 'reception';
                    break;
                case 'waiter':
                    this.currentView = 'waiter';
                    break;
                case 'kitchen':
                    this.currentView = 'kitchen';
                    break;
                default:
                    this.currentView = 'login';
            }
        },
        onLoginSuccess(user) {
            this.user = user;
            this.navigateToUserDashboard(user.role);
        },
        logout() {
            this.user = null;
            this.currentView = 'login';
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
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
            console.log('Order created:', event.order);
            toast.info(`New order #${event.order.id} created`);
        },
        handleOrderUpdated(event) {
            console.log('Order updated:', event.order);
            toast.info(`Order #${event.order.id} updated`);
        },
        handleInventoryUpdated(event) {
            console.log('Inventory updated:', event.item);
            toast.info(`Inventory item ${event.item.name} updated`);
        },
        handleTableStatusChanged(event) {
            console.log('Table status changed:', event.table);
            toast.info(`Table ${event.table.name} status changed`);
        }
    },
    provide() {
        return {
            $echo: window.Echo,
            $user: this.user,
            $http: axios,
            $toast: toast,
            $navigate: this.navigateToUserDashboard,
            $logout: this.logout
        }
    }
});

// Global properties
app.config.globalProperties.$http = axios;
app.config.globalProperties.$toast = toast;

// Register global components
app.component('owner-dashboard', OwnerDashboard);
app.component('reception-dashboard', ReceptionDashboard);
app.component('waiter-dashboard', WaiterDashboard);
app.component('kitchen-dashboard', KitchenDashboard);
app.component('table-layout', TableLayout);
app.component('order-management', OrderManagement);
app.component('inventory-management', InventoryManagement);
app.component('login', Login);

// Mount the app
const appElement = document.getElementById('app');
if (appElement) {
    app.mount('#app');
    console.log('Restaurant Management System mounted successfully');
} else {
    console.error('No #app element found in DOM');
}