<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Restaurant Management System
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Sign in to your account
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="login">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            />
          </div>
        </div>

        <div v-if="error" class="text-red-600 text-sm text-center">
          {{ error }}
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <span v-if="loading">Signing in...</span>
            <span v-else>Sign in</span>
          </button>
        </div>
      </form>

      <!-- Demo Accounts -->
      <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Demo Accounts</h3>
        <div class="grid grid-cols-2 gap-2 text-xs">
          <button @click="fillDemoCredentials('owner')" class="bg-blue-600 text-white px-2 py-1 rounded">
            Owner
          </button>
          <button @click="fillDemoCredentials('reception')" class="bg-purple-600 text-white px-2 py-1 rounded">
            Reception
          </button>
          <button @click="fillDemoCredentials('waiter')" class="bg-green-600 text-white px-2 py-1 rounded">
            Waiter
          </button>
          <button @click="fillDemoCredentials('kitchen')" class="bg-orange-600 text-white px-2 py-1 rounded">
            Kitchen
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      loading: false,
      error: ''
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.error = '';
      
      try {
        const response = await axios.post('/api/login', this.form);
        const user = response.data.user;
        
        // Store token (if using Sanctum)
        if (response.data.token) {
          localStorage.setItem('auth_token', response.data.token);
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
        }
        
        // Redirect based on user role
        switch (user.role) {
          case 'owner':
          case 'superadmin':
            this.$router.push('/owner');
            break;
          case 'reception':
            this.$router.push('/reception');
            break;
          case 'waiter':
            this.$router.push('/waiter');
            break;
          case 'kitchen':
            this.$router.push('/kitchen');
            break;
          default:
            this.$router.push('/');
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed';
      } finally {
        this.loading = false;
      }
    },
    fillDemoCredentials(role) {
      const credentials = {
        owner: { email: 'admin@restaurant.com', password: 'password' },
        reception: { email: 'reception@restaurant.com', password: 'password' },
        waiter: { email: 'waiter@restaurant.com', password: 'password' },
        kitchen: { email: 'kitchen@restaurant.com', password: 'password' }
      };
      
      if (credentials[role]) {
        this.form.email = credentials[role].email;
        this.form.password = credentials[role].password;
      }
    }
  }
}
</script>