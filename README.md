# Restaurant Management System

A comprehensive restaurant management system built with Laravel 11 and Vue.js, designed to streamline restaurant operations including table management, order taking, kitchen communication, billing, and menu management.

## 🚀 Features

### 👤 User Roles & Permissions

#### 🔹 Waiter
- **Dashboard**: Visual table layout with real-time status
- **Table Assignment**: Click tables to assign customers with capacity tracking
- **Order Management**: 
  - Take orders with categorized menu items
  - Save and print orders to kitchen
  - Modify existing orders (add/remove items)
  - Cancel orders with kitchen notifications
- **Real-time Updates**: Live order status and table availability

#### 🔹 Reception
- **Dashboard**: Live table status and ongoing orders overview
- **Menu Management**: Add, update, or remove menu items and categories
- **Notifications**: Real-time notifications for order cancellations and table activities
- **Billing**: Generate and print final bills with tax calculations
- **Order Monitoring**: Track all active orders and their status

#### 🔹 Super Admin
- **Full Access**: Complete system access and management
- **User Management**: Create and manage waiters, receptionists, and kitchen staff
- **Analytics**: Comprehensive reports and business insights
- **System Settings**: Configure restaurant settings, table layout, and printer settings
- **Inventory Management**: Track stock levels and supplier information

### 📦 Core Modules

#### Authentication
- Multi-role login system (Waiter, Reception, Super Admin, Kitchen)
- Laravel Sanctum for API authentication
- Role-based access control

#### Table Management
- Visual table layout with drag-and-drop positioning
- Real-time table status (Free, Occupied, Reserved, Maintenance)
- Capacity tracking and customer count management
- Table assignment and reservation system

#### Menu Management
- CRUD operations for menu categories and items
- Item details: name, description, price, image, availability
- Category-based organization
- Price history tracking

#### Order Management
- Complete order lifecycle: Assignment → Order → Kitchen → Service → Payment
- Real-time order status updates
- Kitchen printing system (simulated)
- Order modification and cancellation capabilities
- Tax calculation and billing

#### Kitchen Integration
- Order printing to kitchen (simulated)
- Real-time order status updates
- Kitchen staff notifications
- Order preparation tracking

#### Billing System
- Automatic tax calculation
- Detailed bill generation
- Payment tracking
- Receipt printing

#### Notifications System
- Real-time notifications using Laravel Reverb
- Role-specific notifications
- Order status updates
- System alerts

#### Inventory Management
- Stock level tracking
- Supplier management
- Reorder level alerts
- Cost tracking and reporting

## 🛠️ Technical Stack

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 with Tailwind CSS
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Sanctum
- **Real-time**: Laravel Reverb with WebSockets
- **Build Tool**: Vite

## 📋 Prerequisites

- PHP 8.2+
- Node.js 18+
- Composer
- SQLite (for development)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd restaurant-management-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Start the frontend build process (in another terminal)**
   ```bash
   npm run dev
   ```

## 👥 Default Users

The system comes with pre-seeded users for testing:

### Super Admin
- **Email**: admin@restaurant.com
- **Password**: password

### Reception
- **Email**: reception@restaurant.com
- **Password**: password

### Waiters
- **Email**: waiter1@restaurant.com
- **Password**: password
- **Email**: waiter2@restaurant.com
- **Password**: password

### Kitchen Staff
- **Email**: kitchen@restaurant.com
- **Password**: password

## 📊 Sample Data

The system includes comprehensive sample data:

### Menu Items
- **Starters**: Bruschetta, Mozzarella Sticks, Garlic Bread
- **Soups**: Tomato Basil Soup, Chicken Noodle Soup
- **Salads**: Caesar Salad, Greek Salad
- **Main Course**: Grilled Chicken Breast, Beef Tenderloin, Vegetable Stir Fry
- **Pasta**: Spaghetti Carbonara, Fettuccine Alfredo
- **Seafood**: Grilled Salmon, Shrimp Scampi
- **Desserts**: Tiramisu, Chocolate Lava Cake, New York Cheesecake
- **Beverages**: Fresh Orange Juice, Lemonade, Iced Tea
- **Coffee & Tea**: Espresso, Cappuccino, Green Tea
- **Alcoholic Drinks**: House Red Wine, House White Wine, Draft Beer

### Tables
- 10 regular tables (2-8 person capacity)
- 4 bar seats
- Visual layout with coordinates

### Inventory
- Fresh produce, meat, seafood, dairy, pantry items
- Supplier information
- Stock levels and reorder points

## 🔧 API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user

### Waiter Routes
- `GET /api/waiter/tables` - Get all tables
- `POST /api/waiter/tables/{table}/assign` - Assign table
- `POST /api/waiter/orders/{order}/items` - Add items to order
- `POST /api/waiter/orders/{order}/send-to-kitchen` - Send order to kitchen

### Reception Routes
- `GET /api/reception/tables` - Get table status
- `GET /api/reception/orders` - Get active orders
- `GET /api/reception/notifications` - Get notifications
- `POST /api/reception/orders/{order}/paid` - Mark order as paid

### Super Admin Routes
- `GET /api/owner/analytics` - Get business analytics
- `GET /api/owner/staff` - Get staff list
- `POST /api/owner/staff` - Create staff member
- `POST /api/owner/reports/generate` - Generate reports

### Kitchen Routes
- `GET /api/kitchen/orders` - Get kitchen orders
- `POST /api/kitchen/orders/{order}/start-preparing` - Start preparing order
- `POST /api/kitchen/orders/{order}/mark-ready` - Mark order as ready

## 🎨 Frontend Components

### Dashboard Components
- `WaiterDashboard.vue` - Waiter interface with table management
- `ReceptionDashboard.vue` - Reception interface with order monitoring
- `OwnerDashboard.vue` - Super admin analytics and management
- `KitchenDashboard.vue` - Kitchen order management

### Shared Components
- `TableLayout.vue` - Visual table layout component
- `OrderManagement.vue` - Order creation and management
- `InventoryManagement.vue` - Inventory tracking interface
- `Login.vue` - Authentication component

## 🔄 Real-time Features

The system uses Laravel Reverb for real-time updates:

- **Order Updates**: Live order status changes
- **Table Status**: Real-time table availability
- **Notifications**: Instant notifications for all roles
- **Kitchen Communication**: Live order printing and status updates

## 📈 Business Intelligence

### Analytics Dashboard
- Daily revenue tracking
- Order volume analysis
- Table utilization rates
- Staff performance metrics
- Popular menu items

### Reporting
- Sales reports by date range
- Inventory reports
- Staff performance reports
- Customer analytics

## 🔒 Security Features

- Role-based access control
- API authentication with Sanctum
- CSRF protection
- Input validation and sanitization
- Secure password hashing

## 🚀 Deployment

### Production Requirements
- PHP 8.2+
- MySQL 8.0+ or PostgreSQL
- Redis (for caching and sessions)
- Web server (Apache/Nginx)
- SSL certificate

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
BROADCAST_CONNECTION=pusher
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions, please open an issue in the repository.

---

**Built with ❤️ using Laravel 11 and Vue.js 3**
