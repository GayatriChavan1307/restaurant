<template>
  <div class="reception-dashboard bg-gray-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-purple-600 text-white p-4">
      <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Reception Dashboard</h1>
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <span class="bg-purple-700 px-3 py-1 rounded text-sm">
              {{ activeTablesCount }} Active Tables
            </span>
            <span class="bg-purple-700 px-3 py-1 rounded text-sm">
              {{ todayRevenue | currency }} Today
            </span>
          </div>
          <div class="relative">
            <button @click="showNotifications = !showNotifications" class="relative">
              <span class="text-2xl">🔔</span>
              <span v-if="unreadNotifications > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ unreadNotifications }}
              </span>
            </button>
          </div>
          <span>{{ $user?.name }}</span>
          <button @click="logout" class="bg-purple-700 px-3 py-1 rounded">Logout</button>
        </div>
      </div>
    </nav>

    <!-- Real-time Stats Bar -->
    <div class="bg-white border-b p-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
          <div class="text-2xl font-bold text-green-600">{{ totalOrders }}</div>
          <div class="text-sm text-gray-600">Total Orders</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-blue-600">{{ activeOrders }}</div>
          <div class="text-sm text-gray-600">Active Orders</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-yellow-600">{{ pendingOrders }}</div>
          <div class="text-sm text-gray-600">Pending Orders</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-purple-600">{{ availableTables }}</div>
          <div class="text-sm text-gray-600">Available Tables</div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
      <!-- Tab Navigation -->
      <div class="mb-6">
        <nav class="flex space-x-4">
          <button
            @click="activeTab = 'overview'"
            :class="['px-4 py-2 rounded', activeTab === 'overview' ? 'bg-purple-600 text-white' : 'bg-white text-gray-700']"
          >
            Overview
          </button>
          <button
            @click="activeTab = 'tables'"
            :class="['px-4 py-2 rounded', activeTab === 'tables' ? 'bg-purple-600 text-white' : 'bg-white text-gray-700']"
          >
            Tables
          </button>
          <button
            @click="activeTab = 'orders'"
            :class="['px-4 py-2 rounded', activeTab === 'orders' ? 'bg-purple-600 text-white' : 'bg-white text-gray-700']"
          >
            Orders
          </button>
          <button
            @click="activeTab = 'billing'"
            :class="['px-4 py-2 rounded', activeTab === 'billing' ? 'bg-purple-600 text-white' : 'bg-white text-gray-700']"
          >
            Billing
          </button>
        </nav>
      </div>

      <!-- Overview Tab -->
      <div v-if="activeTab === 'overview'" class="space-y-6">
        <!-- Live Activity Feed -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Live Activity Feed</h3>
          <div class="space-y-3 max-h-80 overflow-y-auto">
            <div
              v-for="activity in activityFeed"
              :key="activity.id"
              :class="[
                'p-3 rounded border-l-4 text-sm',
                getActivityClass(activity.type)
              ]"
            >
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-medium">{{ activity.message }}</p>
                  <p class="text-gray-600">{{ activity.details }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ formatTime(activity.timestamp) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">Quick Actions</h4>
            <div class="space-y-2">
              <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                New Reservation
              </button>
              <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Process Payment
              </button>
              <button class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">
                Generate Report
              </button>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">Today's Performance</h4>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span>Revenue:</span>
                <span class="font-bold text-green-600">{{ todayRevenue | currency }}</span>
              </div>
              <div class="flex justify-between">
                <span>Orders:</span>
                <span class="font-bold">{{ todayOrdersCount }}</span>
              </div>
              <div class="flex justify-between">
                <span>Avg Order:</span>
                <span class="font-bold">{{ averageOrderValue | currency }}</span>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">Kitchen Status</h4>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span>Preparing:</span>
                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">{{ kitchenStats.preparing }}</span>
              </div>
              <div class="flex justify-between">
                <span>Ready:</span>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ kitchenStats.ready }}</span>
              </div>
              <div class="flex justify-between">
                <span>Avg Prep Time:</span>
                <span class="font-bold">{{ kitchenStats.avgPrepTime }} min</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tables Tab -->
      <div v-if="activeTab === 'tables'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div
          v-for="table in tables"
          :key="table.id"
          :class="[
            'bg-white p-4 rounded-lg shadow-md border-l-4 transition-all',
            getTableBorderClass(table.status)
          ]"
        >
          <div class="text-center">
            <h3 class="text-lg font-bold mb-2">{{ table.name }}</h3>
            <p class="text-sm mb-2">Capacity: {{ table.capacity }}</p>
            <div class="mb-3">
              <span :class="getTableStatusClass(table.status)" class="px-2 py-1 rounded text-xs font-medium">
                {{ formatStatus(table.status) }}
              </span>
            </div>
            
            <!-- Current Order Info -->
            <div v-if="table.current_order" class="mt-3 text-sm">
              <p class="font-medium">Order #{{ table.current_order.id }}</p>
              <p>Waiter: {{ table.current_order.waiter?.name }}</p>
              <p>Status: {{ formatStatus(table.current_order.status) }}</p>
              <p>Total: {{ table.current_order.total_amount | currency }}</p>
              <p class="text-xs text-gray-600">{{ formatTime(table.current_order.created_at) }}</p>
            </div>
            
            <!-- Table Actions -->
            <div class="mt-4 space-y-2">
              <button
                v-if="table.current_order"
                @click="viewOrderDetails(table.current_order)"
                class="w-full bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700"
              >
                View Order
              </button>
              
              <button
                v-if="table.current_order && table.current_order.status === 'ready'"
                @click="generateBill(table.current_order)"
                class="w-full bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700"
              >
                Generate Bill
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="activeTab === 'orders'" class="space-y-4">
        <div
          v-for="order in allOrders"
          :key="order.id"
          class="bg-white p-4 rounded-lg shadow-md"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <h3 class="text-lg font-bold">Order #{{ order.id }}</h3>
              <p class="text-sm text-gray-600">Table: {{ order.restaurant_table?.name }}</p>
              <p class="text-sm text-gray-600">Waiter: {{ order.waiter?.name }}</p>
              <p class="text-sm text-gray-600">{{ formatTime(order.created_at) }}</p>
            </div>
            <div class="text-right">
              <span :class="getOrderStatusClass(order.status)" class="px-3 py-1 rounded text-sm font-medium">
                {{ formatStatus(order.status) }}
              </span>
              <p class="text-lg font-bold mt-1">{{ order.total_amount | currency }}</p>
            </div>
          </div>
          
          <!-- Order Items -->
          <div class="mb-4">
            <h4 class="font-medium mb-2">Items:</h4>
            <div class="space-y-1">
              <div
                v-for="item in order.order_items"
                :key="item.id"
                class="flex justify-between text-sm"
              >
                <span>{{ item.quantity }}x {{ item.menu_item?.name }}</span>
                <span>{{ (item.quantity * item.price_at_order) | currency }}</span>
              </div>
            </div>
          </div>
          
          <!-- Order Actions -->
          <div class="flex space-x-2">
            <button
              @click="viewOrderDetails(order)"
              class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
            >
              View Details
            </button>
            <button
              v-if="order.status === 'ready'"
              @click="generateBill(order)"
              class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
            >
              Generate Bill
            </button>
            <button
              v-if="order.status === 'ready'"
              @click="markAsPaid(order)"
              class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700"
            >
              Mark as Paid
            </button>
          </div>
        </div>
      </div>

      <!-- Billing Tab -->
      <div v-if="activeTab === 'billing'" class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Pending Bills</h3>
          <div class="space-y-4">
            <div
              v-for="order in pendingBills"
              :key="order.id"
              class="flex justify-between items-center p-4 border rounded"
            >
              <div>
                <h4 class="font-medium">Order #{{ order.id }} - Table {{ order.restaurant_table?.name }}</h4>
                <p class="text-sm text-gray-600">{{ order.order_items?.length }} items</p>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold">{{ order.total_amount | currency }}</p>
                <div class="space-x-2 mt-2">
                  <button
                    @click="generateBill(order)"
                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                  >
                    Generate Bill
                  </button>
                  <button
                    @click="markAsPaid(order)"
                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                  >
                    Mark Paid
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications Sidebar -->
    <div v-if="showNotifications" class="fixed right-0 top-0 h-full w-96 bg-white shadow-xl z-50 overflow-y-auto">
      <div class="p-4 border-b">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-bold">Notifications</h3>
          <button @click="showNotifications = false" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>
      </div>
      <div class="p-4 space-y-3">
        <div
          v-for="notification in notifications"
          :key="notification.id"
          :class="[
            'p-3 rounded border-l-4',
            notification.read_at ? 'bg-gray-50 border-gray-300' : 'bg-blue-50 border-blue-400'
          ]"
        >
          <p class="font-medium">{{ notification.title }}</p>
          <p class="text-sm text-gray-600">{{ notification.message }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ formatTime(notification.created_at) }}</p>
          <button
            v-if="!notification.read_at"
            @click="markNotificationAsRead(notification)"
            class="text-xs text-blue-600 hover:text-blue-800 mt-1"
          >
            Mark as read
          </button>
        </div>
      </div>
    </div>

    <!-- Order Details Modal -->
    <div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Order #{{ selectedOrder?.id }} Details</h3>
          <button @click="showOrderModal = false" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>
        
        <div v-if="selectedOrder" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p><strong>Table:</strong> {{ selectedOrder.restaurant_table?.name }}</p>
              <p><strong>Waiter:</strong> {{ selectedOrder.waiter?.name }}</p>
              <p><strong>Customers:</strong> {{ selectedOrder.customer_count }}</p>
            </div>
            <div>
              <p><strong>Status:</strong> {{ formatStatus(selectedOrder.status) }}</p>
              <p><strong>Created:</strong> {{ formatTime(selectedOrder.created_at) }}</p>
              <p><strong>Total:</strong> {{ selectedOrder.total_amount | currency }}</p>
            </div>
          </div>
          
          <div>
            <h4 class="font-medium mb-2">Order Items:</h4>
            <div class="space-y-2">
              <div
                v-for="item in selectedOrder.order_items"
                :key="item.id"
                class="flex justify-between items-center p-2 bg-gray-50 rounded"
              >
                <div>
                  <span class="font-medium">{{ item.quantity }}x {{ item.menu_item?.name }}</span>
                  <p class="text-sm text-gray-600">{{ item.menu_item?.description }}</p>
                </div>
                <span class="font-bold">{{ (item.quantity * item.price_at_order) | currency }}</span>
              </div>
            </div>
          </div>
          
          <div v-if="selectedOrder.notes" class="p-3 bg-yellow-50 border border-yellow-200 rounded">
            <p class="text-sm"><strong>Notes:</strong> {{ selectedOrder.notes }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'ReceptionDashboard',
  data() {
    return {
      activeTab: 'overview',
      tables: [],
      allOrders: [],
      notifications: [],
      activityFeed: [],
      showNotifications: false,
      showOrderModal: false,
      selectedOrder: null,
      kitchenStats: {
        preparing: 0,
        ready: 0,
        avgPrepTime: 0
      },
      todayStats: {
        revenue: 0,
        ordersCount: 0
      }
    }
  },
  computed: {
    activeTablesCount() {
      return this.tables.filter(t => t.status === 'occupied').length;
    },
    availableTables() {
      return this.tables.filter(t => t.status === 'available').length;
    },
    totalOrders() {
      return this.allOrders.length;
    },
    activeOrders() {
      return this.allOrders.filter(o => !['paid', 'cancelled'].includes(o.status)).length;
    },
    pendingOrders() {
      return this.allOrders.filter(o => o.status === 'pending').length;
    },
    pendingBills() {
      return this.allOrders.filter(o => o.status === 'ready');
    },
    todayRevenue() {
      return this.todayStats.revenue;
    },
    todayOrdersCount() {
      return this.todayStats.ordersCount;
    },
    averageOrderValue() {
      return this.todayOrdersCount > 0 ? this.todayRevenue / this.todayOrdersCount : 0;
    },
    unreadNotifications() {
      return this.notifications.filter(n => !n.read_at).length;
    }
  },
  async mounted() {
    await this.loadDashboardData();
    this.setupRealTimeListeners();
    this.startAutoRefresh();
  },
  methods: {
    async loadDashboardData() {
      try {
        const [tablesRes, ordersRes, notificationsRes, statsRes] = await Promise.all([
          axios.get('/api/reception/tables'),
          axios.get('/api/reception/orders'),
          axios.get('/api/reception/notifications'),
          axios.get('/api/reception/stats')
        ]);
        
        this.tables = tablesRes.data;
        this.allOrders = ordersRes.data;
        this.notifications = notificationsRes.data;
        this.todayStats = statsRes.data.today;
        this.kitchenStats = statsRes.data.kitchen;
        this.activityFeed = statsRes.data.activities || [];
      } catch (error) {
        console.error('Failed to load dashboard data:', error);
      }
    },
    setupRealTimeListeners() {
      if (this.$echo) {
        this.$echo.channel('restaurant-updates')
          .listen('OrderCreated', (e) => {
            this.handleOrderCreated(e);
          })
          .listen('OrderUpdated', (e) => {
            this.handleOrderUpdated(e);
          })
          .listen('TableStatusChanged', (e) => {
            this.handleTableStatusChanged(e);
          });

        // Private notifications
        if (this.$user) {
          this.$echo.private(`user.${this.$user.id}`)
            .notification((notification) => {
              this.notifications.unshift(notification);
              this.showNotificationToast(notification);
            });
        }
      }
    },
    startAutoRefresh() {
      // Refresh data every 30 seconds
      setInterval(() => {
        this.loadDashboardData();
      }, 30000);
    },
    handleOrderCreated(event) {
      this.allOrders.unshift(event.order);
      this.addToActivityFeed('order_created', `New order #${event.order.id} created`, `Table ${event.order.restaurant_table?.name}`);
    },
    handleOrderUpdated(event) {
      const index = this.allOrders.findIndex(o => o.id === event.order.id);
      if (index !== -1) {
        this.allOrders.splice(index, 1, event.order);
      }
      this.addToActivityFeed('order_updated', `Order #${event.order.id} updated`, `Status: ${event.order.status}`);
    },
    handleTableStatusChanged(event) {
      const index = this.tables.findIndex(t => t.id === event.table.id);
      if (index !== -1) {
        this.tables.splice(index, 1, event.table);
      }
      this.addToActivityFeed('table_updated', `Table ${event.table.name} status changed`, `Now: ${event.table.status}`);
    },
    addToActivityFeed(type, message, details) {
      this.activityFeed.unshift({
        id: Date.now(),
        type,
        message,
        details,
        timestamp: new Date().toISOString()
      });
      
      // Keep only last 50 activities
      if (this.activityFeed.length > 50) {
        this.activityFeed = this.activityFeed.slice(0, 50);
      }
    },
    viewOrderDetails(order) {
      this.selectedOrder = order;
      this.showOrderModal = true;
    },
    async generateBill(order) {
      try {
        const response = await axios.get(`/api/reception/orders/${order.id}/bill`);
        // Open bill in new window or download
        window.open(response.data.bill_url, '_blank');
        this.$toast.success('Bill generated successfully');
      } catch (error) {
        this.$toast.error('Failed to generate bill');
      }
    },
    async markAsPaid(order) {
      if (confirm('Mark this order as paid?')) {
        try {
          await axios.post(`/api/reception/orders/${order.id}/paid`);
          const index = this.allOrders.findIndex(o => o.id === order.id);
          if (index !== -1) {
            this.allOrders[index].status = 'paid';
          }
          this.$toast.success('Order marked as paid');
        } catch (error) {
          this.$toast.error('Failed to mark order as paid');
        }
      }
    },
    async markNotificationAsRead(notification) {
      try {
        await axios.post(`/api/notifications/${notification.id}/read`);
        notification.read_at = new Date().toISOString();
      } catch (error) {
        console.error('Failed to mark notification as read');
      }
    },
    getTableBorderClass(status) {
      switch (status) {
        case 'available': return 'border-green-400';
        case 'occupied': return 'border-red-400';
        case 'reserved': return 'border-yellow-400';
        default: return 'border-gray-400';
      }
    },
    getTableStatusClass(status) {
      switch (status) {
        case 'available': return 'bg-green-100 text-green-800';
        case 'occupied': return 'bg-red-100 text-red-800';
        case 'reserved': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    getOrderStatusClass(status) {
      switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-blue-100 text-blue-800';
        case 'preparing': return 'bg-orange-100 text-orange-800';
        case 'ready': return 'bg-green-100 text-green-800';
        case 'paid': return 'bg-purple-100 text-purple-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    getActivityClass(type) {
      switch (type) {
        case 'order_created': return 'bg-green-50 border-green-400';
        case 'order_updated': return 'bg-blue-50 border-blue-400';
        case 'table_updated': return 'bg-yellow-50 border-yellow-400';
        default: return 'bg-gray-50 border-gray-400';
      }
    },
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1);
    },
    formatTime(timestamp) {
      return moment(timestamp).format('HH:mm');
    },
    showNotificationToast(notification) {
      // Show toast notification
      this.$toast.info(notification.message);
    },
    logout() {
      this.$router.push('/');
    }
  },
  filters: {
    currency(value) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(value || 0);
    }
  }
}
</script>

<style scoped>
.reception-dashboard {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>