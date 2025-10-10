# Import Export Business Website

A comprehensive PHP Laravel website for import/export businesses, built based on the reference site [sidexports.com](https://sidexports.com/). This application provides both frontend and backend functionality for managing an import/export business with modern UI/UX and responsive design.

## 🚀 Features

### 🌐 Frontend Features
- **Modern Homepage** with hero section, featured products, and company statistics
- **Product Catalog** with advanced filtering, search, and categorization
- **Product Details** with specifications, images, and inquiry forms
- **About Us** page with company story, mission, vision, and team
- **Contact Page** with contact form, map integration, and FAQ section
- **Responsive Design** optimized for all devices
- **SEO Optimized** with proper meta tags and structured data

### 🔧 Backend Features
- **Admin Dashboard** with comprehensive analytics and statistics
- **Product Management** - Create, edit, delete, and manage products
- **Category Management** - Organize products into categories and subcategories
- **Inquiry Management** - Handle customer inquiries with status tracking
- **Supplier Management** - Manage supplier information and relationships
- **User Management** - Role-based access control (Admin, Manager, Sales, Customer)
- **Company Profile** - Manage company information and settings

### 📊 Business Features
- **Inquiry System** - Customers can submit product inquiries with detailed requirements
- **Quote Management** - Admin can respond to inquiries with quotes and follow-ups
- **Product Specifications** - Detailed product information with certifications
- **Global Reach** - Multi-currency support and international shipping information
- **Quality Certifications** - Display product certifications and compliance information

## 🛠️ Technical Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Sanctum
- **UI Components**: Font Awesome icons, AOS animations
- **Responsive Framework**: Bootstrap 5 with custom CSS

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or SQLite
- Web server (Apache/Nginx)

## 🚀 Installation

1. **Clone or use the existing repository**
   ```bash
   cd /workspace
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
   # Edit .env file with your database and application settings
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 Default Users

The system comes with pre-seeded users for testing:

### Admin User
- **Email**: admin@importexport.com
- **Password**: password
- **Role**: Admin (Full access)

### Manager User
- **Email**: manager@importexport.com
- **Password**: password
- **Role**: Manager (Management access)

### Sales User
- **Email**: sales@importexport.com
- **Password**: password
- **Role**: Sales (Sales operations)

### Customer Users
- **Email**: john@example.com / maria@example.com
- **Password**: password
- **Role**: Customer (Frontend access)

## 📊 Sample Data

The application includes comprehensive sample data:

### Products
- Premium Red Onions
- Dehydrated Onion Powder
- Basmati Rice Premium
- Turmeric Powder
- Black Pepper Whole
- Cashew Nuts W320

### Categories
- Agricultural Products (with subcategories)
- Spices & Herbs
- Food Products
- Industrial Goods
- Textiles
- Chemicals

### Company Information
- Complete company profile with contact details
- Business hours and social media links
- Certifications and export countries
- Mission, vision, and values

### Testimonials
- Customer testimonials from various countries
- Ratings and company information
- Featured testimonials for homepage

## 🔧 Configuration

### Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=import_export_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Mail Configuration
Configure email settings for contact forms and notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Application Settings
```env
APP_NAME="ImportExport Business"
APP_URL=http://localhost:8000
APP_TIMEZONE=UTC
```

## 📱 Key Pages and Routes

### Frontend Routes
- `/` - Homepage with featured products and company overview
- `/about` - About us page with company information
- `/products` - Product catalog with filtering and search
- `/products/{product}` - Individual product details
- `/contact` - Contact page with form and information

### Admin Routes (Protected)
- `/admin` - Admin dashboard with analytics
- `/admin/products` - Product management
- `/admin/inquiries` - Inquiry management
- `/admin/categories` - Category management
- `/admin/suppliers` - Supplier management

## 🎨 Customization

### Styling
The application uses CSS custom properties for easy theming:
```css
:root {
    --primary-color: #2c5530;
    --secondary-color: #4a7c59;
    --accent-color: #f39c12;
}
```

### Company Branding
Update company information in the `company_profiles` table or through the admin panel.

### Product Categories
Add new categories and subcategories through the database or admin interface.

## 🔒 Security Features

- **Role-based Access Control** - Different user roles with specific permissions
- **CSRF Protection** - Laravel's built-in CSRF protection
- **Input Validation** - Comprehensive form validation
- **SQL Injection Protection** - Laravel Eloquent ORM protection
- **XSS Protection** - Output escaping and sanitization

## 📈 SEO Features

- **Meta Tags** - Dynamic meta titles, descriptions, and keywords
- **Structured Data** - Schema markup for better search visibility
- **Clean URLs** - SEO-friendly URL structure
- **Sitemap Ready** - Easy to generate XML sitemaps
- **Mobile Optimization** - Responsive design for mobile SEO

## 🌍 Internationalization Ready

- **Multi-currency Support** - Products can have different currencies
- **Country-based Filtering** - Filter products by origin country
- **Global Shipping Information** - International shipping details
- **Multiple Language Support** - Ready for Laravel localization

## 🚀 Deployment

### Production Requirements
- PHP 8.2+ with required extensions
- MySQL 8.0+ or PostgreSQL
- Web server (Apache/Nginx) with URL rewriting
- SSL certificate for HTTPS
- Sufficient storage for product images

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_production_host
DB_DATABASE=your_production_db

# Cache and Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
# Configure your production mail settings
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation and FAQ section

## 🎯 Roadmap

Future enhancements planned:
- [ ] Multi-language support
- [ ] Advanced reporting and analytics
- [ ] Email marketing integration
- [ ] API for mobile app integration
- [ ] Advanced inventory management
- [ ] Customer portal with order tracking
- [ ] Integration with shipping providers
- [ ] Advanced search with Elasticsearch

---

**Built with ❤️ using Laravel 12 and Bootstrap 5**

*This application is designed to help import/export businesses establish a professional online presence and manage their operations efficiently.*