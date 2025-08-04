<template>
  <div class="kitchen-dashboard bg-gray-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-orange-600 text-white p-4">
      <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Kitchen Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span class="bg-orange-700 px-3 py-1 rounded">{{ pendingOrdersCount }} Pending</span>
          <span>{{ $user?.name }}</span>
          <button @click="logout" class="bg-orange-700 px-3 py-1 rounded">Logout</button>
        </div>
      </div>
    </nav>

    <!-- Kitchen Orders Grid -->
    <div class="container mx-auto p-4">
      <!-- Status Filter -->
      <div class="mb-6">
        <nav class="flex space-x-4">
          <button
            @click="statusFilter = 'all'"
            :class="['px-4 py-2 rounded', statusFilter === 'all' ? 'bg-orange-600 text-white' : 'bg-white text-gray-700']"
          >
            All Orders ({{ orders.length }})
          </button>
          <button
            @click="statusFilter = 'pending'"
            :class="['px-4 py-2 rounded', statusFilter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700']"
          >
            Pending ({{ getPendingOrders.length }})
          </button>
          <button
            @click="statusFilter = 'preparing'"
            :class="['px-4 py-2 rounded', statusFilter === 'preparing' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700']"
          >
            Preparing ({{ getPreparingOrders.length }})
          </button>
          <button
            @click="statusFilter = 'ready'"
            :class="['px-4 py-2 rounded', statusFilter === 'ready' ? 'bg-green-600 text-white' : 'bg-white text-gray-700']"
          >
            Ready ({{ getReadyOrders.length }})
          </button>
        </nav>
      </div>

      <!-- Orders Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          :class="[
            'bg-white rounded-lg shadow-lg p-4 border-l-4 transition-all duration-300',
            getOrderBorderClass(order.status)
          ]"
        >
          <!-- Order Header -->
          <div class="flex justify-between items-start mb-4">
            <div>
              <h3 class="text-lg font-bold">Order #{{ order.id }}</h3>
              <p class="text-sm text-gray-600">Table: {{ order.restaurant_table?.name }}</p>
              <p class="text-sm text-gray-600">{{ formatTime(order.created_at) }}</p>
            </div>
            <div class="text-right">
              <span :class="getOrderStatusClass(order.status)" class="px-2 py-1 rounded text-xs font-medium">
                {{ formatStatus(order.status) }}
              </span>
              <p class="text-sm text-gray-600 mt-1">{{ order.customer_count }} guests</p>
            </div>
          </div>

          <!-- Order Items -->
          <div class="mb-4">
            <h4 class="font-medium mb-2 text-gray-700">Items:</h4>
            <div class="space-y-2">
              <div
                v-for="item in order.order_items"
                :key="item.id"
                :class="[
                  'flex justify-between items-center p-2 rounded text-sm',
                  getItemStatusClass(item.kitchen_status)
                ]"
              >
                <div>
                  <span class="font-medium">{{ item.quantity }}x {{ item.menu_item?.name }}</span>
                  <p class="text-xs text-gray-600" v-if="item.special_instructions">
                    Note: {{ item.special_instructions }}
                  </p>
                </div>
                <div class="flex items-center space-x-2">
                  <select
                    v-model="item.kitchen_status"
                    @change="updateItemStatus(order.id, item.id, item.kitchen_status)"
                    class="text-xs border rounded px-2 py-1"
                  >
                    <option value="pending">Pending</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="served">Served</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Preparation Time -->
          <div class="mb-4 text-sm">
            <span class="text-gray-600">Prep Time: </span>
            <span :class="getTimeClass(order.preparation_time)">
              {{ getPreparationTime(order.created_at) }} min
            </span>
          </div>

          <!-- Order Actions -->
          <div class="space-y-2">
            <div class="flex space-x-2">
              <button
                v-if="order.status === 'pending'"
                @click="startPreparing(order)"
                class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700 transition-colors"
              >
                Start Preparing
              </button>
              
              <button
                v-if="order.status === 'preparing'"
                @click="markAsReady(order)"
                class="flex-1 bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700 transition-colors"
              >
                Mark Ready
              </button>
              
              <button
                v-if="order.status === 'ready'"
                @click="markAsServed(order)"
                class="flex-1 bg-purple-600 text-white py-2 px-3 rounded text-sm hover:bg-purple-700 transition-colors"
              >
                Mark Served
              </button>
            </div>
            
            <div class="flex space-x-2">
              <button
                @click="addNote(order)"
                class="flex-1 bg-gray-600 text-white py-1 px-3 rounded text-sm hover:bg-gray-700"
              >
                Add Note
              </button>
              
              <button
                v-if="['pending', 'preparing'].includes(order.status)"
                @click="reportIssue(order)"
                class="flex-1 bg-red-600 text-white py-1 px-3 rounded text-sm hover:bg-red-700"
              >
                Report Issue
              </button>
            </div>
          </div>

          <!-- Order Notes -->
          <div v-if="order.kitchen_notes" class="mt-4 p-2 bg-yellow-50 border border-yellow-200 rounded">
            <p class="text-xs text-yellow-800">
              <strong>Kitchen Notes:</strong> {{ order.kitchen_notes }}
            </p>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredOrders.length === 0" class="text-center py-12">
        <div class="text-gray-400 text-xl mb-4">🍽️</div>
        <p class="text-gray-600">No orders found for the selected filter.</p>
      </div>
    </div>

    <!-- Add Note Modal -->
    <div v-if="showNoteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Add Kitchen Note - Order #{{ selectedOrder?.id }}</h3>
        <form @submit.prevent="saveNote">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Note</label>
            <textarea
              v-model="noteText"
              class="w-full border rounded px-3 py-2"
              rows="4"
              placeholder="Enter kitchen notes..."
              required
            ></textarea>
          </div>
          <div class="flex space-x-2">
            <button
              type="submit"
              class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
            >
              Save Note
            </button>
            <button
              type="button"
              @click="showNoteModal = false"
              class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Issue Modal -->
    <div v-if="showIssueModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Report Issue - Order #{{ selectedOrder?.id }}</h3>
        <form @submit.prevent="submitIssue">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Issue Type</label>
            <select v-model="issueForm.type" class="w-full border rounded px-3 py-2" required>
              <option value="">Select issue type</option>
              <option value="ingredient_shortage">Ingredient Shortage</option>
              <option value="equipment_failure">Equipment Failure</option>
              <option value="quality_issue">Quality Issue</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea
              v-model="issueForm.description"
              class="w-full border rounded px-3 py-2"
              rows="4"
              placeholder="Describe the issue..."
              required
            ></textarea>
          </div>
          <div class="flex space-x-2">
            <button
              type="submit"
              class="flex-1 bg-red-600 text-white py-2 rounded hover:bg-red-700"
            >
              Report Issue
            </button>
            <button
              type="button"
              @click="showIssueModal = false"
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
  name: 'KitchenDashboard',
  data() {
    return {
      orders: [],
      statusFilter: 'all',
      showNoteModal: false,
      showIssueModal: false,
      selectedOrder: null,
      noteText: '',
      issueForm: {
        type: '',
        description: ''
      }
    }
  },
  computed: {
    filteredOrders() {
      if (this.statusFilter === 'all') {
        return this.orders;
      }
      return this.orders.filter(order => order.status === this.statusFilter);
    },
    getPendingOrders() {
      return this.orders.filter(order => order.status === 'pending');
    },
    getPreparingOrders() {
      return this.orders.filter(order => order.status === 'preparing');
    },
    getReadyOrders() {
      return this.orders.filter(order => order.status === 'ready');
    },
    pendingOrdersCount() {
      return this.getPendingOrders.length;
    }
  },
  async mounted() {
    await this.loadOrders();
    this.setupRealTimeListeners();
    this.startAutoRefresh();
  },
  methods: {
    async loadOrders() {
      try {
        const response = await axios.get('/api/kitchen/orders');
        this.orders = response.data;
      } catch (error) {
        console.error('Failed to load orders:', error);
      }
    },
    setupRealTimeListeners() {
      if (this.$echo) {
        this.$echo.channel('kitchen-orders')
          .listen('OrderSentToKitchen', (e) => {
            this.addNewOrder(e.order);
            this.playNotificationSound();
          })
          .listen('OrderUpdated', (e) => {
            this.updateOrderInList(e.order);
          })
          .listen('OrderCancelled', (e) => {
            this.removeOrderFromList(e.order.id);
          });
      }
    },
    startAutoRefresh() {
      // Refresh orders every 30 seconds
      setInterval(() => {
        this.loadOrders();
      }, 30000);
    },
    async startPreparing(order) {
      try {
        await axios.post(`/api/kitchen/orders/${order.id}/start-preparing`);
        order.status = 'preparing';
        order.preparation_started_at = new Date().toISOString();
        this.$toast.success('Order marked as preparing');
      } catch (error) {
        this.$toast.error('Failed to update order status');
      }
    },
    async markAsReady(order) {
      try {
        await axios.post(`/api/kitchen/orders/${order.id}/mark-ready`);
        order.status = 'ready';
        order.preparation_completed_at = new Date().toISOString();
        this.$toast.success('Order marked as ready');
      } catch (error) {
        this.$toast.error('Failed to update order status');
      }
    },
    async markAsServed(order) {
      try {
        await axios.post(`/api/kitchen/orders/${order.id}/mark-served`);
        order.status = 'served';
        this.$toast.success('Order marked as served');
      } catch (error) {
        this.$toast.error('Failed to update order status');
      }
    },
    async updateItemStatus(orderId, itemId, status) {
      try {
        await axios.post(`/api/kitchen/orders/${orderId}/items/${itemId}/status`, { status });
        this.$toast.success('Item status updated');
      } catch (error) {
        this.$toast.error('Failed to update item status');
      }
    },
    addNote(order) {
      this.selectedOrder = order;
      this.noteText = order.kitchen_notes || '';
      this.showNoteModal = true;
    },
    async saveNote() {
      try {
        await axios.post(`/api/kitchen/orders/${this.selectedOrder.id}/note`, {
          note: this.noteText
        });
        this.selectedOrder.kitchen_notes = this.noteText;
        this.showNoteModal = false;
        this.$toast.success('Note saved');
      } catch (error) {
        this.$toast.error('Failed to save note');
      }
    },
    reportIssue(order) {
      this.selectedOrder = order;
      this.issueForm = { type: '', description: '' };
      this.showIssueModal = true;
    },
    async submitIssue() {
      try {
        await axios.post(`/api/kitchen/orders/${this.selectedOrder.id}/issue`, this.issueForm);
        this.showIssueModal = false;
        this.$toast.success('Issue reported');
      } catch (error) {
        this.$toast.error('Failed to report issue');
      }
    },
    getOrderBorderClass(status) {
      switch (status) {
        case 'pending': return 'border-yellow-400';
        case 'preparing': return 'border-blue-400';
        case 'ready': return 'border-green-400';
        case 'served': return 'border-purple-400';
        default: return 'border-gray-400';
      }
    },
    getOrderStatusClass(status) {
      switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'preparing': return 'bg-blue-100 text-blue-800';
        case 'ready': return 'bg-green-100 text-green-800';
        case 'served': return 'bg-purple-100 text-purple-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    getItemStatusClass(status) {
      switch (status) {
        case 'pending': return 'bg-yellow-50 border-yellow-200';
        case 'preparing': return 'bg-blue-50 border-blue-200';
        case 'ready': return 'bg-green-50 border-green-200';
        case 'served': return 'bg-purple-50 border-purple-200';
        default: return 'bg-gray-50 border-gray-200';
      }
    },
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1);
    },
    formatTime(timestamp) {
      return moment(timestamp).format('HH:mm');
    },
    getPreparationTime(createdAt) {
      return moment().diff(moment(createdAt), 'minutes');
    },
    getTimeClass(prepTime) {
      if (prepTime > 30) return 'text-red-600 font-bold';
      if (prepTime > 20) return 'text-yellow-600 font-medium';
      return 'text-green-600';
    },
    addNewOrder(order) {
      // Add new order to the beginning of the list
      this.orders.unshift(order);
    },
    updateOrderInList(updatedOrder) {
      const index = this.orders.findIndex(o => o.id === updatedOrder.id);
      if (index !== -1) {
        this.orders.splice(index, 1, updatedOrder);
      }
    },
    removeOrderFromList(orderId) {
      this.orders = this.orders.filter(o => o.id !== orderId);
    },
    playNotificationSound() {
      // Play a notification sound for new orders
      try {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(e => console.log('Could not play notification sound'));
      } catch (error) {
        console.log('Notification sound not available');
      }
    },
    logout() {
      this.$router.push('/');
    }
  }
}
</script>

<style scoped>
.kitchen-dashboard {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>