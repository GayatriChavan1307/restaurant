<template>
  <div class="table-layout p-6">
    <h2 class="text-2xl font-bold mb-6">Restaurant Table Layout</h2>
    
    <!-- Table Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div
        v-for="table in tables"
        :key="table.id"
        :class="[
          'relative p-6 rounded-lg border-2 cursor-pointer transition-all duration-300',
          getTableClass(table.status),
          'hover:shadow-lg transform hover:scale-105'
        ]"
        @click="selectTable(table)"
      >
        <!-- Table Number -->
        <div class="text-center">
          <div class="text-3xl font-bold mb-2">{{ table.name }}</div>
          <div class="text-sm text-gray-600 mb-2">{{ table.capacity }} seats</div>
          
          <!-- Status Indicator -->
          <div :class="getStatusBadgeClass(table.status)" class="inline-block px-3 py-1 rounded-full text-xs font-medium mb-2">
            {{ formatStatus(table.status) }}
          </div>
          
          <!-- Current Order Info -->
          <div v-if="table.current_order" class="text-xs text-gray-600 mt-2">
            <div>Order #{{ table.current_order.id }}</div>
            <div>${{ table.current_order.total_amount }}</div>
          </div>
        </div>
        
        <!-- Table Shape Visual -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
          <div 
            :class="[
              'w-16 h-16 rounded-full border-4 opacity-20',
              table.status === 'available' ? 'border-green-400' : 
              table.status === 'occupied' ? 'border-red-400' : 'border-yellow-400'
            ]"
          ></div>
        </div>
      </div>
    </div>
    
    <!-- Legend -->
    <div class="mt-8 flex justify-center space-x-6">
      <div class="flex items-center">
        <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
        <span class="text-sm">Available</span>
      </div>
      <div class="flex items-center">
        <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
        <span class="text-sm">Occupied</span>
      </div>
      <div class="flex items-center">
        <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
        <span class="text-sm">Reserved</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableLayout',
  data() {
    return {
      tables: []
    }
  },
  async mounted() {
    await this.loadTables();
  },
  methods: {
    async loadTables() {
      try {
        const response = await axios.get('/api/tables');
        this.tables = response.data;
      } catch (error) {
        console.error('Failed to load tables:', error);
        // Mock data for development
        this.tables = [
          { id: 1, name: 'T1', capacity: 2, status: 'available', current_order: null },
          { id: 2, name: 'T2', capacity: 4, status: 'occupied', current_order: { id: 1, total_amount: 45.50 } },
          { id: 3, name: 'T3', capacity: 6, status: 'available', current_order: null },
          { id: 4, name: 'T4', capacity: 2, status: 'reserved', current_order: null },
        ];
      }
    },
    selectTable(table) {
      this.$emit('table-selected', table);
    },
    getTableClass(status) {
      switch (status) {
        case 'available':
          return 'bg-green-50 border-green-300 hover:bg-green-100';
        case 'occupied':
          return 'bg-red-50 border-red-300 hover:bg-red-100';
        case 'reserved':
          return 'bg-yellow-50 border-yellow-300 hover:bg-yellow-100';
        default:
          return 'bg-gray-50 border-gray-300 hover:bg-gray-100';
      }
    },
    getStatusBadgeClass(status) {
      switch (status) {
        case 'available':
          return 'bg-green-600 text-white';
        case 'occupied':
          return 'bg-red-600 text-white';
        case 'reserved':
          return 'bg-yellow-600 text-white';
        default:
          return 'bg-gray-600 text-white';
      }
    },
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1);
    }
  }
}
</script>