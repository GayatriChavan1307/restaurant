<template>
  <div class="owner-dashboard bg-gray-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-indigo-600 text-white p-4">
      <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Owner Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span class="bg-indigo-700 px-3 py-1 rounded text-sm">
            {{ todayRevenue | currency }} Today
          </span>
          <span>{{ $user?.name }}</span>
          <button @click="logout" class="bg-indigo-700 px-3 py-1 rounded">Logout</button>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
      <!-- Tab Navigation -->
      <div class="mb-6">
        <nav class="flex space-x-4">
          <button
            @click="activeTab = 'analytics'"
            :class="['px-4 py-2 rounded', activeTab === 'analytics' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700']"
          >
            Analytics
          </button>
          <button
            @click="activeTab = 'management'"
            :class="['px-4 py-2 rounded', activeTab === 'management' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700']"
          >
            Management
          </button>
          <button
            @click="activeTab = 'inventory'"
            :class="['px-4 py-2 rounded', activeTab === 'inventory' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700']"
          >
            Inventory
          </button>
          <button
            @click="activeTab = 'staff'"
            :class="['px-4 py-2 rounded', activeTab === 'staff' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700']"
          >
            Staff
          </button>
          <button
            @click="activeTab = 'reports'"
            :class="['px-4 py-2 rounded', activeTab === 'reports' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700']"
          >
            Reports
          </button>
        </nav>
      </div>

      <!-- Analytics Tab -->
      <div v-if="activeTab === 'analytics'" class="space-y-6">
        <!-- Key Performance Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-400">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                <p class="text-2xl font-bold text-green-600">{{ todayRevenue | currency }}</p>
                <p class="text-xs text-gray-500">{{ revenueChange }}% from yesterday</p>
              </div>
              <div class="text-3xl text-green-500">💰</div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-400">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Orders Today</p>
                <p class="text-2xl font-bold text-blue-600">{{ todayOrders }}</p>
                <p class="text-xs text-gray-500">{{ ordersChange }}% from yesterday</p>
              </div>
              <div class="text-3xl text-blue-500">📋</div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-400">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Active Tables</p>
                <p class="text-2xl font-bold text-yellow-600">{{ activeTables }}/{{ totalTables }}</p>
                <p class="text-xs text-gray-500">{{ Math.round((activeTables/totalTables)*100) }}% occupied</p>
              </div>
              <div class="text-3xl text-yellow-500">🪑</div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-400">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Avg Order Value</p>
                <p class="text-2xl font-bold text-purple-600">{{ averageOrderValue | currency }}</p>
                <p class="text-xs text-gray-500">{{ aovChange }}% from yesterday</p>
              </div>
              <div class="text-3xl text-purple-500">📊</div>
            </div>
          </div>
        </div>

        <!-- Charts and Trends -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-4">Weekly Revenue Trend</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
              <div
                v-for="(day, index) in weeklyRevenue"
                :key="index"
                class="bg-indigo-500 rounded-t flex-1 flex items-end justify-center text-white text-xs pb-2"
                :style="{ height: `${(day.revenue / Math.max(...weeklyRevenue.map(d => d.revenue))) * 100}%` }"
              >
                {{ day.revenue | currency }}
              </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-600">
              <span v-for="day in weeklyRevenue" :key="day.day">{{ day.day }}</span>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-4">Popular Menu Items</h3>
            <div class="space-y-3">
              <div
                v-for="item in popularItems"
                :key="item.id"
                class="flex justify-between items-center"
              >
                <div>
                  <p class="font-medium">{{ item.name }}</p>
                  <p class="text-sm text-gray-600">{{ item.orders_count }} orders</p>
                </div>
                <div class="text-right">
                  <p class="font-bold">{{ item.total_revenue | currency }}</p>
                  <div class="w-24 bg-gray-200 rounded-full h-2">
                    <div
                      class="bg-indigo-600 h-2 rounded-full"
                      :style="{ width: `${(item.orders_count / popularItems[0].orders_count) * 100}%` }"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Real-time Activity -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Real-time Restaurant Activity</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
              <div class="text-2xl font-bold text-green-600">{{ kitchenStats.ready }}</div>
              <p class="text-sm text-gray-600">Orders Ready</p>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-yellow-600">{{ kitchenStats.preparing }}</div>
              <p class="text-sm text-gray-600">Orders Preparing</p>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-blue-600">{{ kitchenStats.avgPrepTime }}</div>
              <p class="text-sm text-gray-600">Avg Prep Time (min)</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Management Tab -->
      <div v-if="activeTab === 'management'" class="space-y-6">
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">Menu Management</h4>
            <div class="space-y-2">
              <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Add New Item
              </button>
              <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Edit Categories
              </button>
              <button class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700">
                Update Prices
              </button>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">Table Management</h4>
            <div class="space-y-2">
              <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Add Table
              </button>
              <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Edit Layout
              </button>
              <button class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                Remove Table
              </button>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold mb-4">System Settings</h4>
            <div class="space-y-2">
              <button class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Business Hours
              </button>
              <button class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700">
                Tax Settings
              </button>
              <button class="w-full bg-gray-600 text-white py-2 rounded hover:bg-gray-700">
                Backup Data
              </button>
            </div>
          </div>
        </div>

        <!-- Current Orders Overview -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Current Operations Overview</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h4 class="font-medium mb-3">Active Orders by Status</h4>
              <div class="space-y-2">
                <div class="flex justify-between items-center p-2 bg-yellow-50 rounded">
                  <span>Pending</span>
                  <span class="bg-yellow-600 text-white px-2 py-1 rounded text-sm">{{ ordersByStatus.pending }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                  <span>Preparing</span>
                  <span class="bg-blue-600 text-white px-2 py-1 rounded text-sm">{{ ordersByStatus.preparing }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-green-50 rounded">
                  <span>Ready</span>
                  <span class="bg-green-600 text-white px-2 py-1 rounded text-sm">{{ ordersByStatus.ready }}</span>
                </div>
              </div>
            </div>
            <div>
              <h4 class="font-medium mb-3">Staff Performance Today</h4>
              <div class="space-y-2">
                <div
                  v-for="waiter in topWaiters"
                  :key="waiter.id"
                  class="flex justify-between items-center p-2 bg-gray-50 rounded"
                >
                  <span>{{ waiter.name }}</span>
                  <div class="text-right">
                    <div class="text-sm font-bold">{{ waiter.orders_count }} orders</div>
                    <div class="text-xs text-gray-600">{{ waiter.revenue | currency }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Tab -->
      <div v-if="activeTab === 'inventory'">
        <inventory-management @inventory-updated="handleInventoryUpdate" />
      </div>

      <!-- Staff Tab -->
      <div v-if="activeTab === 'staff'" class="space-y-6">
        <!-- Add Staff Member -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Add New Staff Member</h3>
          <form @submit.prevent="addStaffMember" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Name</label>
              <input
                v-model="newStaff.name"
                type="text"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Email</label>
              <input
                v-model="newStaff.email"
                type="email"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Role</label>
              <select v-model="newStaff.role" required class="w-full border rounded px-3 py-2">
                <option value="">Select Role</option>
                <option value="reception">Reception</option>
                <option value="waiter">Waiter</option>
                <option value="kitchen">Kitchen Staff</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Password</label>
              <input
                v-model="newStaff.password"
                type="password"
                required
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div class="md:col-span-2">
              <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
              >
                Add Staff Member
              </button>
            </div>
          </form>
        </div>

        <!-- Staff List -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Staff Members</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="staff in staffMembers" :key="staff.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ staff.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ staff.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span :class="getRoleBadgeClass(staff.role)" class="px-2 py-1 rounded text-xs font-medium">
                      {{ formatRole(staff.role) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span :class="staff.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded text-xs font-medium">
                      {{ staff.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button @click="editStaff(staff)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button @click="toggleStaffStatus(staff)" class="text-blue-600 hover:text-blue-900">
                      {{ staff.is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Reports Tab -->
      <div v-if="activeTab === 'reports'" class="space-y-6">
        <!-- Report Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">Generate Reports</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Report Type</label>
              <select v-model="reportFilters.type" class="w-full border rounded px-3 py-2">
                <option value="sales">Sales Report</option>
                <option value="inventory">Inventory Report</option>
                <option value="staff">Staff Performance</option>
                <option value="customer">Customer Analytics</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Date From</label>
              <input
                v-model="reportFilters.dateFrom"
                type="date"
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Date To</label>
              <input
                v-model="reportFilters.dateTo"
                type="date"
                class="w-full border rounded px-3 py-2"
              />
            </div>
            <div class="flex items-end">
              <button
                @click="generateReport"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700"
              >
                Generate Report
              </button>
            </div>
          </div>
        </div>

        <!-- Report Results -->
        <div v-if="reportData" class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-bold mb-4">{{ reportData.title }}</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    v-for="header in reportData.headers"
                    :key="header"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(row, index) in reportData.data" :key="index">
                  <td
                    v-for="(cell, cellIndex) in row"
                    :key="cellIndex"
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                  >
                    {{ cell }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 flex space-x-2">
            <button
              @click="exportReport('pdf')"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            >
              Export PDF
            </button>
            <button
              @click="exportReport('excel')"
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
              Export Excel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OwnerDashboard',
  data() {
    return {
      activeTab: 'analytics',
      // Analytics data
      todayRevenue: 0,
      revenueChange: 0,
      todayOrders: 0,
      ordersChange: 0,
      activeTables: 0,
      totalTables: 0,
      averageOrderValue: 0,
      aovChange: 0,
      weeklyRevenue: [],
      popularItems: [],
      kitchenStats: {
        ready: 0,
        preparing: 0,
        avgPrepTime: 0
      },
      ordersByStatus: {
        pending: 0,
        preparing: 0,
        ready: 0
      },
      topWaiters: [],
      // Staff management
      staffMembers: [],
      newStaff: {
        name: '',
        email: '',
        role: '',
        password: ''
      },
      // Reports
      reportFilters: {
        type: 'sales',
        dateFrom: '',
        dateTo: ''
      },
      reportData: null
    }
  },
  async mounted() {
    await this.loadDashboardData();
    this.setupRealTimeListeners();
    this.setDefaultReportDates();
  },
  methods: {
    async loadDashboardData() {
      try {
        const [analyticsRes, staffRes] = await Promise.all([
          axios.get('/api/owner/analytics'),
          axios.get('/api/owner/staff')
        ]);
        
        const analytics = analyticsRes.data;
        this.todayRevenue = analytics.todayRevenue;
        this.revenueChange = analytics.revenueChange;
        this.todayOrders = analytics.todayOrders;
        this.ordersChange = analytics.ordersChange;
        this.activeTables = analytics.activeTables;
        this.totalTables = analytics.totalTables;
        this.averageOrderValue = analytics.averageOrderValue;
        this.aovChange = analytics.aovChange;
        this.weeklyRevenue = analytics.weeklyRevenue;
        this.popularItems = analytics.popularItems;
        this.kitchenStats = analytics.kitchenStats;
        this.ordersByStatus = analytics.ordersByStatus;
        this.topWaiters = analytics.topWaiters;
        
        this.staffMembers = staffRes.data;
      } catch (error) {
        console.error('Failed to load dashboard data:', error);
      }
    },
    setupRealTimeListeners() {
      if (this.$echo) {
        this.$echo.channel('restaurant-updates')
          .listen('OrderCreated', () => {
            this.loadDashboardData();
          })
          .listen('OrderUpdated', () => {
            this.loadDashboardData();
          });
      }
    },
    async addStaffMember() {
      try {
        const response = await axios.post('/api/owner/staff', this.newStaff);
        this.staffMembers.push(response.data);
        this.newStaff = { name: '', email: '', role: '', password: '' };
        this.$toast.success('Staff member added successfully');
      } catch (error) {
        this.$toast.error('Failed to add staff member');
      }
    },
    async toggleStaffStatus(staff) {
      try {
        await axios.patch(`/api/owner/staff/${staff.id}/toggle`);
        staff.is_active = !staff.is_active;
        this.$toast.success('Staff status updated');
      } catch (error) {
        this.$toast.error('Failed to update staff status');
      }
    },
    editStaff(staff) {
      // Implementation for editing staff
      console.log('Edit staff:', staff);
    },
    setDefaultReportDates() {
      const today = new Date();
      const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
      this.reportFilters.dateFrom = lastWeek.toISOString().split('T')[0];
      this.reportFilters.dateTo = today.toISOString().split('T')[0];
    },
    async generateReport() {
      try {
        const response = await axios.post('/api/owner/reports/generate', this.reportFilters);
        this.reportData = response.data;
      } catch (error) {
        this.$toast.error('Failed to generate report');
      }
    },
    async exportReport(format) {
      try {
        const response = await axios.post('/api/owner/reports/export', {
          ...this.reportFilters,
          format
        });
        // Download the file
        window.open(response.data.download_url, '_blank');
      } catch (error) {
        this.$toast.error('Failed to export report');
      }
    },
    handleInventoryUpdate() {
      // Handle inventory updates
      this.loadDashboardData();
    },
    getRoleBadgeClass(role) {
      switch (role) {
        case 'reception': return 'bg-purple-100 text-purple-800';
        case 'waiter': return 'bg-blue-100 text-blue-800';
        case 'kitchen': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },
    formatRole(role) {
      return role.charAt(0).toUpperCase() + role.slice(1);
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
.owner-dashboard {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>