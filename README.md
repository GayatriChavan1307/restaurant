# Restaurant Management System

A comprehensive Vue.js + Laravel restaurant management system with real-time features, role-based access control, and dynamic inventory management.

## Features

### Multi-Role Access System
- **Owner/Admin Dashboard**: Analytics, staff management, reports, inventory oversight
- **Reception Dashboard**: Real-time monitoring, billing, customer management
- **Waiter Interface**: Table management, order taking, menu browsing
- **Kitchen Dashboard**: Order queue management, preparation tracking, real-time updates

### Real-Time Features
- Live order status updates across all interfaces
- Real-time table status changes
- Instant notifications for new orders and updates
- Kitchen order queue with preparation time tracking
- Live inventory alerts for low stock items

### Core Functionality
- **Table Management**: Visual table layout, assignment, and status tracking
- **Order Management**: Complete order lifecycle from creation to completion
- **Menu Management**: Categories, items, pricing, and availability
- **Inventory Tracking**: Stock levels, usage tracking, supplier management
- **Billing System**: Invoice generation and payment processing
- **Reporting**: Sales analytics, staff performance, inventory reports

## Technology Stack

### Frontend
- **Vue.js 3** with Composition API
- **Vue Router 4** for SPA navigation
- **Tailwind CSS** for modern UI styling
- **Axios** for API communication
- **Laravel Echo** + **Pusher** for real-time features
- **Moment.js** for date/time handling

### Backend
- **Laravel 12** with modern PHP 8.2+ features
- **Laravel Sanctum** for API authentication
- **Laravel Reverb** for WebSocket broadcasting
- **MySQL** database with optimized relationships
- **Event-driven architecture** for real-time updates

## Quick Start

### Prerequisites
- PHP 8.2+
- Node.js 18+
- MySQL 8.0+
- Composer

### Installation

1. **Clone and Setup**
```bash
git clone <repository-url>
cd restaurant-management
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

4. **Build Frontend**
```bash
npm run build
# or for development
npm run dev
```

5. **Start Services**
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: WebSocket Server
php artisan reverb:start

# Terminal 3: Queue Worker (for background jobs)
php artisan queue:work
```

## User Roles & Access

### Default Login Credentials

**Owner/Admin**
- Email: admin@restaurant.com
- Password: password
- Access: Full system control, analytics, staff management

**Reception**
- Email: reception@restaurant.com
- Password: password
- Access: Orders monitoring, billing, customer management

**Waiter**
- Email: waiter@restaurant.com
- Password: password
- Access: Table assignment, order taking, menu management

**Kitchen**
- Email: kitchen@restaurant.com
- Password: password
- Access: Order preparation, status updates, kitchen notes

## API Endpoints

### Authentication
- `POST /api/login` - User authentication
- `POST /api/logout` - User logout
- `GET /api/user` - Current user info

### Waiter Routes
- `GET /api/waiter/tables` - Get all tables with status
- `GET /api/waiter/orders` - Get waiter's active orders
- `POST /api/waiter/tables/{table}/assign` - Assign table to customers
- `POST /api/waiter/orders/{order}/items` - Add items to order
- `POST /api/waiter/orders/{order}/send-to-kitchen` - Send order to kitchen

### Kitchen Routes
- `GET /api/kitchen/orders` - Get kitchen order queue
- `POST /api/kitchen/orders/{order}/start-preparing` - Start order preparation
- `POST /api/kitchen/orders/{order}/mark-ready` - Mark order as ready
- `POST /api/kitchen/orders/{order}/note` - Add kitchen notes

### Reception Routes
- `GET /api/reception/tables` - Get all tables status
- `GET /api/reception/orders` - Get all orders
- `GET /api/reception/stats` - Get dashboard statistics
- `GET /api/reception/orders/{order}/bill` - Generate bill

### Inventory Routes
- `GET /api/inventory/items` - Get inventory items
- `POST /api/inventory/items` - Create new item
- `POST /api/inventory/items/{item}/stock` - Update stock levels
- `GET /api/inventory/items/{item}/history` - Stock transaction history

## Real-Time Events

The system broadcasts the following events for real-time updates:

- `OrderCreated` - New order placed
- `OrderUpdated` - Order status/content changed
- `TableStatusChanged` - Table assignment/status change
- `InventoryUpdated` - Stock level changes

## Database Schema

### Core Tables
- `users` - Staff members with role-based access
- `restaurant_tables` - Physical tables with capacity and status
- `orders` - Customer orders with status tracking
- `order_items` - Individual items within orders
- `menu_items` - Available food/beverage items
- `categories` - Menu organization
- `inventory_items` - Stock tracking
- `stock_transactions` - Inventory movement history

## Customization

### Adding New Roles
1. Update `User` model role methods
2. Create role-specific middleware
3. Add routes in `routes/api.php`
4. Create corresponding Vue.js components
5. Update role checking in frontend

### Extending Features
- **Payment Integration**: Add Stripe/PayPal for online payments
- **QR Code Menus**: Generate table-specific QR codes
- **Mobile App**: Create React Native/Flutter companion
- **Advanced Analytics**: Add detailed reporting and forecasting
- **Multi-location**: Extend for restaurant chains

## Development

### Frontend Development
```bash
npm run dev  # Start Vite development server
npm run build  # Build for production
```

### Backend Development
```bash
php artisan serve  # Start Laravel development server
php artisan queue:work  # Process background jobs
php artisan reverb:start  # Start WebSocket server
```

### Testing
```bash
php artisan test  # Run PHP tests
npm run test  # Run JavaScript tests (if configured)
```

## Production Deployment

### Server Requirements
- PHP 8.2+ with required extensions
- MySQL 8.0+ or PostgreSQL 13+
- Redis (recommended for caching/sessions)
- SSL certificate for secure WebSocket connections

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
BROADCAST_CONNECTION=reverb
REVERB_SCHEME=https  # For production with SSL
REVERB_HOST=your-domain.com
```

### Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Support

For support, email support@restaurant-system.com or join our Slack channel.

---

**Built with ❤️ for restaurants worldwide**
