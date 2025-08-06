# 🔐 Authentication Guide

## Overview

The Restaurant Management System uses Laravel Sanctum for API authentication with role-based access control. This guide covers all authentication aspects including login, logout, role management, and security features.

## 🚀 Quick Start

### 1. Access the Application

**Development Server:**
```bash
# Start Laravel server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev
```

**Access URL:** `http://localhost:8000`

### 2. Default Login Credentials

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Super Admin** | admin@restaurant.com | password | Full system access |
| **Reception** | reception@restaurant.com | password | Orders, billing, menu management |
| **Waiter 1** | waiter1@restaurant.com | password | Table management, order taking |
| **Waiter 2** | waiter2@restaurant.com | password | Table management, order taking |
| **Kitchen Staff** | kitchen@restaurant.com | password | Order preparation, kitchen management |

## 🔑 Authentication Flow

### Frontend Authentication

1. **Login Process:**
   ```javascript
   // Login request
   const response = await axios.post('/api/login', {
       email: 'waiter1@restaurant.com',
       password: 'password'
   });
   
   // Store token
   localStorage.setItem('auth_token', response.data.token);
   axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
   ```

2. **Token Management:**
   ```javascript
   // Add token to all requests
   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
   
   // Remove token on logout
   localStorage.removeItem('auth_token');
   delete axios.defaults.headers.common['Authorization'];
   ```

### Backend Authentication

1. **API Routes Protection:**
   ```php
   // Protected routes
   Route::middleware(['auth:sanctum'])->group(function () {
       // All authenticated routes here
   });
   ```

2. **Role-based Access:**
   ```php
   // Role-specific routes
   Route::middleware(['auth:sanctum', 'role:waiter'])->group(function () {
       // Waiter-only routes
   });
   ```

## 👥 User Roles & Permissions

### Super Admin
- **Full System Access**
- User management (create, edit, delete staff)
- System settings configuration
- Analytics and reporting
- Inventory management
- Menu management

### Reception
- **Order Management**
  - View all orders and table status
  - Generate bills and process payments
  - Manage menu items and categories
- **Notifications**
  - Receive real-time order updates
  - Handle order cancellations
- **Billing**
  - Generate detailed bills with tax
  - Process payments
  - Print receipts

### Waiter
- **Table Management**
  - Assign tables to customers
  - View table status and capacity
  - Manage table reservations
- **Order Taking**
  - Create new orders
  - Add/remove items from orders
  - Send orders to kitchen
  - Modify existing orders
- **Customer Service**
  - Track order status
  - Handle customer requests

### Kitchen Staff
- **Order Processing**
  - View incoming orders
  - Update order preparation status
  - Mark orders as ready
  - Add kitchen notes
- **Kitchen Management**
  - Track preparation times
  - Manage order queue
  - Report issues

## 🔧 API Authentication Endpoints

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "waiter1@restaurant.com",
    "password": "password"
}
```

**Response:**
```json
{
    "user": {
        "id": 3,
        "name": "John Waiter",
        "email": "waiter1@restaurant.com",
        "role": "waiter",
        "email_verified_at": "2025-08-04T11:37:00.000000Z"
    },
    "token": "1|abc123def456...",
    "message": "Login successful"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Logged out successfully"
}
```

### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

**Response:**
```json
{
    "id": 3,
    "name": "John Waiter",
    "email": "waiter1@restaurant.com",
    "role": "waiter",
    "email_verified_at": "2025-08-04T11:37:00.000000Z"
}
```

## 🛡️ Security Features

### 1. Token-based Authentication
- Laravel Sanctum for secure API tokens
- Automatic token expiration
- CSRF protection

### 2. Role-based Access Control
```php
// Check user role
if ($user->isWaiter()) {
    // Waiter-specific logic
}

if ($user->isReception()) {
    // Reception-specific logic
}

if ($user->isSuperAdmin()) {
    // Admin-specific logic
}
```

### 3. Middleware Protection
```php
// Role middleware
Route::middleware(['auth:sanctum', 'role:waiter,reception'])->group(function () {
    // Routes accessible by waiters and reception
});
```

### 4. Input Validation
```php
// Login validation
$request->validate([
    'email' => 'required|email',
    'password' => 'required|string|min:8'
]);
```

## 🔄 Session Management

### Frontend Session Handling
```javascript
// Check if user is authenticated
const isAuthenticated = () => {
    return localStorage.getItem('auth_token') !== null;
};

// Get current user
const getCurrentUser = () => {
    return JSON.parse(localStorage.getItem('user'));
};

// Auto-logout on token expiration
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response.status === 401) {
            logout();
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
```

### Backend Session Handling
```php
// Check authentication in controllers
public function someAction(Request $request)
{
    $user = auth()->user();
    
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    
    // Continue with authenticated user
}
```

## 🚨 Error Handling

### Common Authentication Errors

1. **Invalid Credentials (401)**
   ```json
   {
       "message": "Invalid credentials"
   }
   ```

2. **Unauthorized Access (401)**
   ```json
   {
       "message": "Unauthorized"
   }
   ```

3. **Insufficient Permissions (403)**
   ```json
   {
       "message": "Forbidden"
   }
   ```

4. **Token Expired (401)**
   ```json
   {
       "message": "Token expired"
   }
   ```

## 🔧 Troubleshooting

### Common Issues

1. **Token Not Working**
   ```bash
   # Clear application cache
   php artisan cache:clear
   php artisan config:clear
   ```

2. **Role Not Recognized**
   ```bash
   # Check user role in database
   php artisan tinker
   >>> App\Models\User::find(1)->role
   ```

3. **Login Fails**
   ```bash
   # Reset user password
   php artisan tinker
   >>> $user = App\Models\User::where('email', 'waiter1@restaurant.com')->first();
   >>> $user->update(['password' => Hash::make('password')]);
   ```

### Debug Authentication

```bash
# Check current user in Tinker
php artisan tinker
>>> auth()->user()

# Check user roles
>>> App\Models\User::all()->pluck('role', 'email')

# Test role methods
>>> $user = App\Models\User::find(1);
>>> $user->isWaiter()
>>> $user->isReception()
>>> $user->isSuperAdmin()
```

## 📱 Mobile/Desktop Access

### API Testing with Postman
1. **Login Request:**
   ```
   POST http://localhost:8000/api/login
   Body: {"email": "waiter1@restaurant.com", "password": "password"}
   ```

2. **Use Token:**
   ```
   Authorization: Bearer {token_from_login}
   ```

### cURL Examples
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "waiter1@restaurant.com", "password": "password"}'

# Get user info
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer {token}"
```

## 🔐 Security Best Practices

1. **Password Security**
   - Use strong passwords in production
   - Implement password reset functionality
   - Enable two-factor authentication for admin accounts

2. **Token Security**
   - Tokens expire automatically
   - Implement token refresh mechanism
   - Log suspicious authentication attempts

3. **Role Security**
   - Always validate roles on both frontend and backend
   - Use middleware for route protection
   - Implement audit logging for sensitive actions

4. **API Security**
   - Use HTTPS in production
   - Implement rate limiting
   - Validate all input data
   - Sanitize output data

## 📋 Quick Reference

### User Management Commands
```bash
# Create new user
php artisan tinker
>>> App\Models\User::create([
    'name' => 'New Waiter',
    'email' => 'newwaiter@restaurant.com',
    'password' => Hash::make('password'),
    'role' => 'waiter'
]);

# Update user role
>>> $user = App\Models\User::find(1);
>>> $user->update(['role' => 'reception']);

# Delete user
>>> App\Models\User::find(1)->delete();
```

### Role Checking Methods
```php
// In controllers
$user = auth()->user();

if ($user->isWaiter()) {
    // Waiter logic
}

if ($user->isReception()) {
    // Reception logic
}

if ($user->isSuperAdmin()) {
    // Admin logic
}
```

---

**🔐 Authentication is now properly configured and secure!**