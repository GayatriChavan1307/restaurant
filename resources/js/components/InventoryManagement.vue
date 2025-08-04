<template>
  <div class="inventory-management">
    <!-- Inventory Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-400">
        <div class="flex items-center">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
            <p class="text-2xl font-bold text-red-600">{{ lowStockCount }}</p>
          </div>
          <div class="text-3xl text-red-500">⚠️</div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-400">
        <div class="flex items-center">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">Out of Stock</p>
            <p class="text-2xl font-bold text-yellow-600">{{ outOfStockCount }}</p>
          </div>
          <div class="text-3xl text-yellow-500">📦</div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-400">
        <div class="flex items-center">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">Total Items</p>
            <p class="text-2xl font-bold text-green-600">{{ totalItems }}</p>
          </div>
          <div class="text-3xl text-green-500">📋</div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-400">
        <div class="flex items-center">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">Monthly Usage</p>
            <p class="text-2xl font-bold text-blue-600">{{ monthlyUsage | currency }}</p>
          </div>
          <div class="text-3xl text-blue-500">📊</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Inventory Management</h3>
        <div class="space-x-2">
          <button
            @click="showAddModal = true"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            Add Item
          </button>
          <button
            @click="showStockModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Update Stock
          </button>
          <button
            @click="generateInventoryReport"
            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700"
          >
            Generate Report
          </button>
        </div>
      </div>

      <!-- Search and Filter -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search items..."
            class="w-full border rounded px-3 py-2"
          />
        </div>
        <div>
          <select v-model="categoryFilter" class="w-full border rounded px-3 py-2">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>
        <div>
          <select v-model="stockFilter" class="w-full border rounded px-3 py-2">
            <option value="">All Items</option>
            <option value="low">Low Stock</option>
            <option value="out">Out of Stock</option>
            <option value="good">Good Stock</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Inventory Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in filteredItems" :key="item.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                  <div class="text-sm text-gray-500">{{ item.supplier?.name }}</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.category }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium" :class="getStockColorClass(item)">
                  {{ item.current_quantity }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.unit }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.minimum_quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.unit_cost | currency }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(item)" class="px-2 py-1 rounded text-xs font-medium">
                  {{ getItemStatus(item) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button @click="editItem(item)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                <button @click="updateStock(item)" class="text-blue-600 hover:text-blue-900">Update</button>
                <button @click="viewHistory(item)" class="text-gray-600 hover:text-gray-900">History</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Item Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Add New Inventory Item</h3>
        <form @submit.prevent="addItem">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Item Name</label>
              <input
                v-model="newItem.name"
                type="text"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Category</label>
              <select v-model="newItem.category" required class="w-full border rounded px-3 py-2">
                <option value="">Select Category</option>
                <option value="Vegetables">Vegetables</option>
                <option value="Meat">Meat</option>
                <option value="Dairy">Dairy</option>
                <option value="Grains">Grains</option>
                <option value="Spices">Spices</option>
                <option value="Beverages">Beverages</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Current Quantity</label>
                <input
                  v-model.number="newItem.current_quantity"
                  type="number"
                  step="0.01"
                  required
                  class="w-full border rounded px-3 py-2"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Unit</label>
                <select v-model="newItem.unit" required class="w-full border rounded px-3 py-2">
                  <option value="kg">Kilograms</option>
                  <option value="lbs">Pounds</option>
                  <option value="pcs">Pieces</option>
                  <option value="liters">Liters</option>
                  <option value="bottles">Bottles</option>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Minimum Quantity</label>
                <input
                  v-model.number="newItem.minimum_quantity"
                  type="number"
                  step="0.01"
                  required
                  class="w-full border rounded px-3 py-2"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Unit Cost</label>
                <input
                  v-model.number="newItem.unit_cost"
                  type="number"
                  step="0.01"
                  required
                  class="w-full border rounded px-3 py-2"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Supplier</label>
              <select v-model="newItem.supplier_id" class="w-full border rounded px-3 py-2">
                <option value="">Select Supplier</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                  {{ supplier.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="flex space-x-2 mt-6">
            <button
              type="submit"
              class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700"
            >
              Add Item
            </button>
            <button
              type="button"
              @click="showAddModal = false"
              class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Update Stock Modal -->
    <div v-if="showStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Update Stock - {{ selectedItem?.name }}</h3>
        <form @submit.prevent="submitStockUpdate">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Transaction Type</label>
              <select v-model="stockUpdate.type" required class="w-full border rounded px-3 py-2">
                <option value="purchase">Purchase (Add Stock)</option>
                <option value="usage">Usage (Remove Stock)</option>
                <option value="adjustment">Adjustment</option>
                <option value="waste">Waste/Loss</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Quantity</label>
              <input
                v-model.number="stockUpdate.quantity"
                type="number"
                step="0.01"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Notes</label>
              <textarea
                v-model="stockUpdate.notes"
                class="w-full border rounded px-3 py-2"
                rows="3"
              ></textarea>
            </div>
          </div>
          <div class="flex space-x-2 mt-6">
            <button
              type="submit"
              class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
            >
              Update Stock
            </button>
            <button
              type="button"
              @click="showStockModal = false"
              class="flex-1 bg-gray-400 text-white py-2 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Stock History Modal -->
    <div v-if="showHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-4xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Stock History - {{ selectedItem?.name }}</h3>
          <button @click="showHistoryModal = false" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in stockHistory" :key="transaction.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(transaction.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getTransactionTypeClass(transaction.type)" class="px-2 py-1 rounded text-xs font-medium">
                    {{ formatTransactionType(transaction.type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="transaction.type === 'usage' || transaction.type === 'waste' ? 'text-red-600' : 'text-green-600'">
                  {{ transaction.type === 'usage' || transaction.type === 'waste' ? '-' : '+' }}{{ transaction.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ transaction.quantity_after }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ transaction.notes }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'InventoryManagement',
  data() {
    return {
      inventoryItems: [],
      suppliers: [],
      categories: [],
      searchTerm: '',
      categoryFilter: '',
      stockFilter: '',
      showAddModal: false,
      showStockModal: false,
      showHistoryModal: false,
      selectedItem: null,
      stockHistory: [],
      newItem: {
        name: '',
        category: '',
        current_quantity: 0,
        unit: '',
        minimum_quantity: 0,
        unit_cost: 0,
        supplier_id: ''
      },
      stockUpdate: {
        type: '',
        quantity: 0,
        notes: ''
      }
    }
  },
  computed: {
    filteredItems() {
      let items = this.inventoryItems;
      
      if (this.searchTerm) {
        items = items.filter(item => 
          item.name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      }
      
      if (this.categoryFilter) {
        items = items.filter(item => item.category === this.categoryFilter);
      }
      
      if (this.stockFilter) {
        items = items.filter(item => {
          switch (this.stockFilter) {
            case 'low':
              return item.current_quantity <= item.minimum_quantity && item.current_quantity > 0;
            case 'out':
              return item.current_quantity === 0;
            case 'good':
              return item.current_quantity > item.minimum_quantity;
            default:
              return true;
          }
        });
      }
      
      return items;
    },
    lowStockCount() {
      return this.inventoryItems.filter(item => 
        item.current_quantity <= item.minimum_quantity && item.current_quantity > 0
      ).length;
    },
    outOfStockCount() {
      return this.inventoryItems.filter(item => item.current_quantity === 0).length;
    },
    totalItems() {
      return this.inventoryItems.length;
    },
    monthlyUsage() {
      // Calculate from stock transactions
      return this.inventoryItems.reduce((total, item) => total + (item.monthly_usage || 0), 0);
    }
  },
  async mounted() {
    await this.loadInventoryData();
    this.setupRealTimeListeners();
  },
  methods: {
    async loadInventoryData() {
      try {
        const [itemsRes, suppliersRes] = await Promise.all([
          axios.get('/api/inventory/items'),
          axios.get('/api/inventory/suppliers')
        ]);
        
        this.inventoryItems = itemsRes.data;
        this.suppliers = suppliersRes.data;
        this.categories = [...new Set(this.inventoryItems.map(item => item.category))];
      } catch (error) {
        console.error('Failed to load inventory data:', error);
      }
    },
    setupRealTimeListeners() {
      if (this.$echo) {
        this.$echo.channel('inventory-updates')
          .listen('InventoryUpdated', (e) => {
            this.updateInventoryItem(e.item);
            this.$emit('inventory-updated', e.item);
          });
      }
    },
    async addItem() {
      try {
        const response = await axios.post('/api/inventory/items', this.newItem);
        this.inventoryItems.push(response.data);
        this.showAddModal = false;
        this.newItem = {
          name: '',
          category: '',
          current_quantity: 0,
          unit: '',
          minimum_quantity: 0,
          unit_cost: 0,
          supplier_id: ''
        };
        this.$toast.success('Item added successfully');
      } catch (error) {
        this.$toast.error('Failed to add item');
      }
    },
    updateStock(item) {
      this.selectedItem = item;
      this.stockUpdate = { type: '', quantity: 0, notes: '' };
      this.showStockModal = true;
    },
    async submitStockUpdate() {
      try {
        await axios.post(`/api/inventory/items/${this.selectedItem.id}/stock`, this.stockUpdate);
        await this.loadInventoryData();
        this.showStockModal = false;
        this.$toast.success('Stock updated successfully');
      } catch (error) {
        this.$toast.error('Failed to update stock');
      }
    },
    async viewHistory(item) {
      try {
        this.selectedItem = item;
        const response = await axios.get(`/api/inventory/items/${item.id}/history`);
        this.stockHistory = response.data;
        this.showHistoryModal = true;
      } catch (error) {
        this.$toast.error('Failed to load stock history');
      }
    },
    editItem(item) {
      // Implementation for editing items
      console.log('Edit item:', item);
    },
    async generateInventoryReport() {
      try {
        const response = await axios.get('/api/inventory/reports/generate');
        window.open(response.data.download_url, '_blank');
      } catch (error) {
        this.$toast.error('Failed to generate report');
      }
    },
    getStockColorClass(item) {
      if (item.current_quantity === 0) return 'text-red-600 font-bold';
      if (item.current_quantity <= item.minimum_quantity) return 'text-yellow-600 font-bold';
      return 'text-green-600';
    },
    getStatusBadgeClass(item) {
      if (item.current_quantity === 0) return 'bg-red-100 text-red-800';
      if (item.current_quantity <= item.minimum_quantity) return 'bg-yellow-100 text-yellow-800';
      return 'bg-green-100 text-green-800';
    },
    getItemStatus(item) {
      if (item.current_quantity === 0) return 'Out of Stock';
      if (item.current_quantity <= item.minimum_quantity) return 'Low Stock';
      return 'In Stock';
    },
    getTransactionTypeClass(type) {
      switch (type) {
        case 'purchase': return 'bg-green-100 text-green-800';
        case 'usage': return 'bg-blue-100 text-blue-800';
        case 'adjustment': return 'bg-purple-100 text-purple-800';
        case 'waste': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    formatTransactionType(type) {
      return type.charAt(0).toUpperCase() + type.slice(1);
    },
    formatDate(date) {
      return moment(date).format('MM/DD/YY HH:mm');
    },
    updateInventoryItem(updatedItem) {
      const index = this.inventoryItems.findIndex(item => item.id === updatedItem.id);
      if (index !== -1) {
        this.inventoryItems.splice(index, 1, updatedItem);
      }
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