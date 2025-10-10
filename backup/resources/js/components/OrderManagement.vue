<template>
  <div class="order-management p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Order Management</h2>
      <button 
        @click="showCreateModal = true"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        Create New Order
      </button>
    </div>

    <!-- Filter and Search -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
      <input
        v-model="searchTerm"
        type="text"
        placeholder="Search orders..."
        class="border rounded px-3 py-2"
      />
      <select v-model="statusFilter" class="border rounded px-3 py-2">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="preparing">Preparing</option>
        <option value="ready">Ready</option>
        <option value="served">Served</option>
        <option value="paid">Paid</option>
      </select>
      <select v-model="tableFilter" class="border rounded px-3 py-2">
        <option value="">All Tables</option>
        <option v-for="table in tables" :key="table.id" :value="table.id">
          Table {{ table.name }}
        </option>
      </select>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="order in filteredOrders"
        :key="order.id"
        class="bg-white p-4 rounded-lg shadow-md border-l-4"
        :class="getOrderBorderClass(order.status)"
      >
        <!-- Order Header -->
        <div class="flex justify-between items-start mb-3">
          <div>
            <h3 class="text-lg font-bold">Order #{{ order.id }}</h3>
            <p class="text-sm text-gray-600">Table {{ order.table_name }}</p>
            <p class="text-sm text-gray-600">{{ formatTime(order.created_at) }}</p>
          </div>
          <span :class="getStatusBadgeClass(order.status)" class="px-2 py-1 rounded text-xs font-medium">
            {{ formatStatus(order.status) }}
          </span>
        </div>

        <!-- Order Items -->
        <div class="mb-4">
          <h4 class="font-medium mb-2">Items ({{ order.items.length }}):</h4>
          <div class="space-y-1 max-h-32 overflow-y-auto">
            <div
              v-for="item in order.items"
              :key="item.id"
              class="flex justify-between text-sm"
            >
              <span>{{ item.quantity }}x {{ item.name }}</span>
              <span>${{ (item.quantity * item.price).toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Order Total -->
        <div class="mb-4 text-right">
          <span class="text-lg font-bold">Total: ${{ order.total.toFixed(2) }}</span>
        </div>

        <!-- Actions -->
        <div class="flex space-x-2">
          <button
            @click="viewOrder(order)"
            class="flex-1 bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700"
          >
            View
          </button>
          <button
            v-if="order.status === 'pending'"
            @click="updateOrderStatus(order, 'confirmed')"
            class="flex-1 bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700"
          >
            Confirm
          </button>
          <button
            v-if="order.status === 'ready'"
            @click="updateOrderStatus(order, 'served')"
            class="flex-1 bg-purple-600 text-white py-1 px-3 rounded text-sm hover:bg-purple-700"
          >
            Serve
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredOrders.length === 0" class="text-center py-12">
      <div class="text-gray-400 text-xl mb-4">📋</div>
      <p class="text-gray-600">No orders found</p>
    </div>

    <!-- Create Order Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Create New Order</h3>
        <form @submit.prevent="createOrder">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Table</label>
            <select v-model="newOrder.table_id" required class="w-full border rounded px-3 py-2">
              <option value="">Select Table</option>
              <option v-for="table in availableTables" :key="table.id" :value="table.id">
                Table {{ table.name }} ({{ table.capacity }} seats)
              </option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Customer Count</label>
            <input
              v-model.number="newOrder.customer_count"
              type="number"
              min="1"
              required
              class="w-full border rounded px-3 py-2"
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Notes</label>
            <textarea
              v-model="newOrder.notes"
              class="w-full border rounded px-3 py-2"
              rows="3"
            ></textarea>
          </div>
          <div class="flex space-x-2">
            <button
              type="submit"
              class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700"
            >
              Create Order
            </button>
            <button
              type="button"
              @click="showCreateModal = false"
              class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'OrderManagement',
  data() {
    return {
      orders: [],
      tables: [],
      searchTerm: '',
      statusFilter: '',
      tableFilter: '',
      showCreateModal: false,
      newOrder: {
        table_id: '',
        customer_count: 1,
        notes: ''
      }
    }
  },
  computed: {
    filteredOrders() {
      let filtered = this.orders;
      
      if (this.searchTerm) {
        filtered = filtered.filter(order => 
          order.id.toString().includes(this.searchTerm) ||
          order.table_name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      }
      
      if (this.statusFilter) {
        filtered = filtered.filter(order => order.status === this.statusFilter);
      }
      
      if (this.tableFilter) {
        filtered = filtered.filter(order => order.table_id === parseInt(this.tableFilter));
      }
      
      return filtered;
    },
    availableTables() {
      return this.tables.filter(table => table.status === 'available');
    }
  },
  async mounted() {
    await this.loadOrders();
    await this.loadTables();
  },
  methods: {
    async loadOrders() {
      try {
        const response = await axios.get('/api/orders');
        this.orders = response.data;
      } catch (error) {
        console.error('Failed to load orders:', error);
        // Mock data for development
        this.orders = [
          {
            id: 1,
            table_id: 1,
            table_name: 'T1',
            status: 'pending',
            customer_count: 2,
            total: 45.50,
            created_at: new Date().toISOString(),
            items: [
              { id: 1, name: 'Burger', quantity: 2, price: 15.00 },
              { id: 2, name: 'Fries', quantity: 2, price: 7.75 }
            ]
          },
          {
            id: 2,
            table_id: 2,
            table_name: 'T2',
            status: 'preparing',
            customer_count: 4,
            total: 89.25,
            created_at: new Date(Date.now() - 30 * 60000).toISOString(),
            items: [
              { id: 3, name: 'Pizza', quantity: 1, price: 25.00 },
              { id: 4, name: 'Salad', quantity: 2, price: 12.50 },
              { id: 5, name: 'Drinks', quantity: 4, price: 9.81 }
            ]
          }
        ];
      }
    },
    async loadTables() {
      try {
        const response = await axios.get('/api/tables');
        this.tables = response.data;
      } catch (error) {
        console.error('Failed to load tables:', error);
        // Mock data for development
        this.tables = [
          { id: 1, name: 'T1', capacity: 2, status: 'available' },
          { id: 2, name: 'T2', capacity: 4, status: 'occupied' },
          { id: 3, name: 'T3', capacity: 6, status: 'available' },
          { id: 4, name: 'T4', capacity: 2, status: 'available' }
        ];
      }
    },
    async createOrder() {
      try {
        const response = await axios.post('/api/orders', this.newOrder);
        this.orders.unshift(response.data);
        this.showCreateModal = false;
        this.newOrder = { table_id: '', customer_count: 1, notes: '' };
        this.$toast?.success('Order created successfully');
      } catch (error) {
        this.$toast?.error('Failed to create order');
      }
    },
    async updateOrderStatus(order, status) {
      try {
        await axios.patch(`/api/orders/${order.id}`, { status });
        order.status = status;
        this.$toast?.success(`Order ${status} successfully`);
      } catch (error) {
        this.$toast?.error('Failed to update order status');
      }
    },
    viewOrder(order) {
      this.$emit('order-selected', order);
    },
    getOrderBorderClass(status) {
      switch (status) {
        case 'pending': return 'border-yellow-400';
        case 'confirmed': return 'border-blue-400';
        case 'preparing': return 'border-orange-400';
        case 'ready': return 'border-green-400';
        case 'served': return 'border-purple-400';
        case 'paid': return 'border-gray-400';
        default: return 'border-gray-400';
      }
    },
    getStatusBadgeClass(status) {
      switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-blue-100 text-blue-800';
        case 'preparing': return 'bg-orange-100 text-orange-800';
        case 'ready': return 'bg-green-100 text-green-800';
        case 'served': return 'bg-purple-100 text-purple-800';
        case 'paid': return 'bg-gray-100 text-gray-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1);
    },
    formatTime(timestamp) {
      return moment(timestamp).format('HH:mm');
    }
  }
}
</script>