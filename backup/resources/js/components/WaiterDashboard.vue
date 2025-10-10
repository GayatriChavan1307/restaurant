<template>
  <div class="waiter-dashboard bg-gray-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-blue-600 text-white p-4">
      <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Waiter Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span>{{ $user?.name }}</span>
          <button @click="logout" class="bg-blue-700 px-3 py-1 rounded">Logout</button>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
      <!-- Tab Navigation -->
      <div class="mb-6">
        <nav class="flex space-x-4">
          <button
            @click="activeTab = 'tables'"
            :class="['px-4 py-2 rounded', activeTab === 'tables' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700']"
          >
            Tables
          </button>
          <button
            @click="activeTab = 'orders'"
            :class="['px-4 py-2 rounded', activeTab === 'orders' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700']"
          >
            Active Orders
          </button>
        </nav>
      </div>

      <!-- Tables Tab -->
      <div v-if="activeTab === 'tables'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div
          v-for="table in tables"
          :key="table.id"
          :class="[
            'p-4 rounded-lg shadow-md cursor-pointer transition-all',
            getTableStatusClass(table)
          ]"
          @click="handleTableClick(table)"
        >
          <div class="text-center">
            <h3 class="text-lg font-bold mb-2">{{ table.name }}</h3>
            <p class="text-sm mb-2">Capacity: {{ table.capacity }}</p>
            <div class="mb-3">
              <span :class="getStatusBadgeClass(table.status)" class="px-2 py-1 rounded text-xs font-medium">
                {{ formatStatus(table.status) }}
              </span>
            </div>
            
            <!-- Table Actions -->
            <div class="space-y-2">
              <button
                v-if="table.status === 'available'"
                @click.stop="assignTable(table)"
                class="w-full bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700"
              >
                Assign Table
              </button>
              
              <button
                v-if="table.status === 'occupied' && table.current_order"
                @click.stop="viewOrder(table.current_order)"
                class="w-full bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700"
              >
                View Order
              </button>
              
              <button
                v-if="table.status === 'occupied'"
                @click.stop="unassignTable(table)"
                class="w-full bg-red-600 text-white py-1 px-3 rounded text-sm hover:bg-red-700"
              >
                Clear Table
              </button>
            </div>
            
            <!-- Current Order Info -->
            <div v-if="table.current_order" class="mt-3 text-xs text-gray-600">
              <p>Order #{{ table.current_order.id }}</p>
              <p>Items: {{ table.current_order.items_count }}</p>
              <p>Total: ${{ table.current_order.total_amount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="activeTab === 'orders'" class="space-y-4">
        <div
          v-for="order in activeOrders"
          :key="order.id"
          class="bg-white p-4 rounded-lg shadow-md"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <h3 class="text-lg font-bold">Order #{{ order.id }}</h3>
              <p class="text-sm text-gray-600">Table: {{ order.restaurant_table?.name }}</p>
              <p class="text-sm text-gray-600">Customers: {{ order.customer_count }}</p>
            </div>
            <div class="text-right">
              <span :class="getOrderStatusClass(order.status)" class="px-3 py-1 rounded text-sm font-medium">
                {{ formatStatus(order.status) }}
              </span>
              <p class="text-lg font-bold mt-1">${{ order.total_amount }}</p>
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
                <span>${{ (item.quantity * item.price_at_order).toFixed(2) }}</span>
              </div>
            </div>
          </div>
          
          <!-- Order Actions -->
          <div class="flex space-x-2">
            <button
              @click="addItemToOrder(order)"
              class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
            >
              Add Item
            </button>
            <button
              @click="modifyOrder(order)"
              class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
            >
              Modify
            </button>
            <button
              @click="sendToKitchen(order)"
              class="bg-orange-600 text-white px-3 py-1 rounded text-sm hover:bg-orange-700"
            >
              Send to Kitchen
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <!-- Assign Table Modal -->
    <div v-if="showAssignModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Assign Table {{ selectedTable?.name }}</h3>
        <form @submit.prevent="confirmAssignTable">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Number of Customers</label>
            <input
              v-model.number="assignForm.customer_count"
              type="number"
              min="1"
              :max="selectedTable?.capacity"
              required
              class="w-full border rounded px-3 py-2"
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Notes (Optional)</label>
            <textarea
              v-model="assignForm.notes"
              class="w-full border rounded px-3 py-2"
              rows="3"
            ></textarea>
          </div>
          <div class="flex space-x-2">
            <button
              type="submit"
              class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700"
            >
              Assign & Create Order
            </button>
            <button
              type="button"
              @click="showAssignModal = false"
              class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Add Item Modal -->
    <div v-if="showAddItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4">Add Item to Order #{{ selectedOrder?.id }}</h3>
        
        <!-- Menu Categories -->
        <div class="mb-4">
          <div class="flex space-x-2 mb-4">
            <button
              v-for="category in menuCategories"
              :key="category.id"
              @click="selectedCategory = category.id"
              :class="[
                'px-4 py-2 rounded text-sm',
                selectedCategory === category.id ? 'bg-blue-600 text-white' : 'bg-gray-200'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
          
          <!-- Menu Items -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="item in filteredMenuItems"
              :key="item.id"
              class="border rounded p-3 hover:bg-gray-50 cursor-pointer"
              @click="selectMenuItem(item)"
            >
              <h4 class="font-medium">{{ item.name }}</h4>
              <p class="text-sm text-gray-600">{{ item.description }}</p>
              <p class="text-lg font-bold text-green-600">${{ item.price }}</p>
            </div>
          </div>
        </div>
        
        <!-- Selected Items -->
        <div v-if="orderItems.length > 0" class="mb-4">
          <h4 class="font-medium mb-2">Selected Items:</h4>
          <div class="space-y-2">
            <div
              v-for="(item, index) in orderItems"
              :key="index"
              class="flex justify-between items-center bg-gray-50 p-2 rounded"
            >
              <span>{{ item.name }}</span>
              <div class="flex items-center space-x-2">
                <button @click="decreaseQuantity(index)" class="bg-red-500 text-white px-2 py-1 rounded text-xs">-</button>
                <span>{{ item.quantity }}</span>
                <button @click="increaseQuantity(index)" class="bg-green-500 text-white px-2 py-1 rounded text-xs">+</button>
                <button @click="removeItem(index)" class="bg-red-600 text-white px-2 py-1 rounded text-xs">Remove</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex space-x-2">
          <button
            @click="addItemsToOrder"
            :disabled="orderItems.length === 0"
            class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700 disabled:opacity-50"
          >
            Add Items
          </button>
          <button
            @click="showAddItemModal = false"
            class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WaiterDashboard',
  data() {
    return {
      activeTab: 'tables',
      tables: [],
      activeOrders: [],
      menuCategories: [],
      menuItems: [],
      showAssignModal: false,
      showAddItemModal: false,
      selectedTable: null,
      selectedOrder: null,
      selectedCategory: null,
      orderItems: [],
      assignForm: {
        customer_count: 1,
        notes: ''
      }
    }
  },
  computed: {
    filteredMenuItems() {
      if (!this.selectedCategory) return this.menuItems;
      return this.menuItems.filter(item => item.category_id === this.selectedCategory);
    }
  },
  async mounted() {
    await this.loadTables();
    await this.loadActiveOrders();
    await this.loadMenuData();
    this.setupRealTimeListeners();
  },
  methods: {
    async loadTables() {
      try {
        const response = await axios.get('/api/waiter/tables');
        this.tables = response.data;
      } catch (error) {
        console.error('Failed to load tables:', error);
      }
    },
    async loadActiveOrders() {
      try {
        const response = await axios.get('/api/waiter/orders');
        this.activeOrders = response.data;
      } catch (error) {
        console.error('Failed to load orders:', error);
      }
    },
    async loadMenuData() {
      try {
        const [categoriesResponse, itemsResponse] = await Promise.all([
          axios.get('/api/menu/categories'),
          axios.get('/api/menu/items')
        ]);
        this.menuCategories = categoriesResponse.data;
        this.menuItems = itemsResponse.data;
        if (this.menuCategories.length > 0) {
          this.selectedCategory = this.menuCategories[0].id;
        }
      } catch (error) {
        console.error('Failed to load menu data:', error);
      }
    },
    setupRealTimeListeners() {
      if (this.$echo) {
        this.$echo.channel('restaurant-updates')
          .listen('OrderUpdated', (e) => {
            this.updateOrderInList(e.order);
          })
          .listen('TableStatusChanged', (e) => {
            this.updateTableInList(e.table);
          });
      }
    },
    getTableStatusClass(table) {
      switch (table.status) {
        case 'available': return 'bg-green-100 border-green-300';
        case 'occupied': return 'bg-red-100 border-red-300';
        case 'reserved': return 'bg-yellow-100 border-yellow-300';
        default: return 'bg-gray-100 border-gray-300';
      }
    },
    getStatusBadgeClass(status) {
      switch (status) {
        case 'available': return 'bg-green-600 text-white';
        case 'occupied': return 'bg-red-600 text-white';
        case 'reserved': return 'bg-yellow-600 text-white';
        default: return 'bg-gray-600 text-white';
      }
    },
    getOrderStatusClass(status) {
      switch (status) {
        case 'pending': return 'bg-yellow-600 text-white';
        case 'confirmed': return 'bg-blue-600 text-white';
        case 'preparing': return 'bg-orange-600 text-white';
        case 'ready': return 'bg-green-600 text-white';
        default: return 'bg-gray-600 text-white';
      }
    },
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1);
    },
    handleTableClick(table) {
      if (table.status === 'available') {
        this.assignTable(table);
      } else if (table.current_order) {
        this.viewOrder(table.current_order);
      }
    },
    assignTable(table) {
      this.selectedTable = table;
      this.assignForm = { customer_count: 1, notes: '' };
      this.showAssignModal = true;
    },
    async confirmAssignTable() {
      try {
        const response = await axios.post(`/api/waiter/tables/${this.selectedTable.id}/assign`, this.assignForm);
        await this.loadTables();
        await this.loadActiveOrders();
        this.showAssignModal = false;
        this.$toast.success('Table assigned successfully');
      } catch (error) {
        this.$toast.error('Failed to assign table');
      }
    },
    async unassignTable(table) {
      if (confirm('Are you sure you want to clear this table?')) {
        try {
          await axios.post(`/api/waiter/tables/${table.id}/unassign`);
          await this.loadTables();
          await this.loadActiveOrders();
          this.$toast.success('Table cleared successfully');
        } catch (error) {
          this.$toast.error('Failed to clear table');
        }
      }
    },
    viewOrder(order) {
      this.activeTab = 'orders';
    },
    addItemToOrder(order) {
      this.selectedOrder = order;
      this.orderItems = [];
      this.showAddItemModal = true;
    },
    selectMenuItem(item) {
      const existingItem = this.orderItems.find(oi => oi.id === item.id);
      if (existingItem) {
        existingItem.quantity++;
      } else {
        this.orderItems.push({ ...item, quantity: 1 });
      }
    },
    increaseQuantity(index) {
      this.orderItems[index].quantity++;
    },
    decreaseQuantity(index) {
      if (this.orderItems[index].quantity > 1) {
        this.orderItems[index].quantity--;
      }
    },
    removeItem(index) {
      this.orderItems.splice(index, 1);
    },
    async addItemsToOrder() {
      try {
        const items = this.orderItems.map(item => ({
          menu_item_id: item.id,
          quantity: item.quantity,
          price_at_order: item.price
        }));
        
        await axios.post(`/api/waiter/orders/${this.selectedOrder.id}/items`, { items });
        await this.loadActiveOrders();
        this.showAddItemModal = false;
        this.$toast.success('Items added to order');
      } catch (error) {
        this.$toast.error('Failed to add items');
      }
    },
    async sendToKitchen(order) {
      try {
        await axios.post(`/api/waiter/orders/${order.id}/send-to-kitchen`);
        await this.loadActiveOrders();
        this.$toast.success('Order sent to kitchen');
      } catch (error) {
        this.$toast.error('Failed to send order to kitchen');
      }
    },
    modifyOrder(order) {
      // Implementation for modifying orders
      this.addItemToOrder(order);
    },
    updateOrderInList(updatedOrder) {
      const index = this.activeOrders.findIndex(o => o.id === updatedOrder.id);
      if (index !== -1) {
        this.activeOrders.splice(index, 1, updatedOrder);
      }
    },
    updateTableInList(updatedTable) {
      const index = this.tables.findIndex(t => t.id === updatedTable.id);
      if (index !== -1) {
        this.tables.splice(index, 1, updatedTable);
      }
    },
    logout() {
      // Use the provided logout method from app
      if (this.$logout) {
        this.$logout();
      } else {
        // Fallback
        window.location.reload();
      }
    }
  }
}
</script>