# Nailville Salon Management System

A comprehensive salon management system built with Laravel 11, Livewire 3, and Tailwind CSS 4. This application helps salon businesses track transactions, manage employees, monitor inventory, and generate insightful reports.

## 🌟 Features

### Transaction Management
- **Income Tracking**: Record and manage all service-based income
- **Expense Tracking**: Track all business expenses with categorization
- **Real-time Analytics**: View totals, summaries, and trends
- **Advanced Filtering**: Filter by date range, payment method, and transaction type
- **Export to PDF**: Generate professional transaction reports
- **Receipt Generation**: Digital receipts with QR codes

### Employee Management
- Track employee performance
- Assign transactions to specific employees
- Monitor individual employee contributions

### Inventory Management
- Track beauty products and supplies
- Stock alerts for low inventory
- Manage product categories

### Reports & Analytics
- Income vs Expense reports
- Net income calculations
- Profit analysis
- Interactive charts and visualizations
- Date range filtering

### User Management
- Role-based access control
- User authentication with Laravel Jetstream
- Two-factor authentication support
- API token management

## 🚀 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Alpine.js 3.14
- **Styling**: Tailwind CSS 4
- **Database**: MySQL/PostgreSQL
- **Charts**: Chart.js 4.4, Morris.js
- **Tables**: DataTables with server-side processing
- **Date Picker**: Flatpickr
- **Authentication**: Laravel Jetstream with Fortify

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm/pnpm
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Apache/Nginx)

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd nailville-salon
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
# or
pnpm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nailville_salon
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database (Optional)

```bash
php artisan db:seed
```

### 7. Build Assets

```bash
npm run build
# For development with hot reload:
npm run dev
```

### 8. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Specific test file
php artisan test tests/Feature/TransactionTest.php
```

### Run Tests with Coverage

```bash
php artisan test --coverage
```

## 📁 Project Structure

```
nailville-salon/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── TransactionController.php
│   │       ├── EmployeeController.php
│   │       ├── InventoryController.php
│   │       └── ReportController.php
│   ├── Models/
│   │   ├── Transaction.php
│   │   ├── Employee.php
│   │   ├── Inventory.php
│   │   └── Service.php
│   └── Livewire/
│       └── TransactionsTable.php
├── resources/
│   ├── views/
│   │   ├── pages/
│   │   │   ├── transactions/
│   │   │   ├── inventory/
│   │   │   ├── reports/
│   │   │   └── settings/
│   │   ├── components/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── tests/
│   ├── Feature/
│   └── Unit/
└── routes/
    └── web.php
```

## 🔑 Key Features Explained

### Transaction Date Handling

The system uses the `date` field for transaction dates (not `created_at`). This allows:
- Backdating transactions
- Accurate historical reporting
- Separation of record creation time from transaction time

### Responsive Design

The UI is fully responsive with:
- Mobile-first approach
- Tailwind CSS breakpoints
- Touch-friendly interfaces
- Optimized for tablets and phones

### Server-Side DataTables

All transaction tables use server-side processing for:
- Fast loading with large datasets
- Efficient pagination
- Real-time search and filtering
- Reduced memory usage

## 🎨 UI Improvements

Recent UI enhancements include:
- Modern card-based layouts
- Smooth animations and transitions
- Dark mode support
- Improved form validation feedback
- Better mobile navigation
- Enhanced accessibility

## 🔒 Security Features

- CSRF protection on all forms
- SQL injection prevention
- XSS protection
- Password hashing with bcrypt
- Rate limiting on authentication
- Secure session management

## 📊 Database Schema

### Transactions Table
- `id`: Primary key
- `transaction_id`: Unique transaction identifier
- `employee_id`: Foreign key to employees
- `recorded_by`: Foreign key to users
- `customer_name`: Customer name (nullable)
- `amount`: Transaction amount (decimal)
- `transaction_type`: Income or Expense
- `payment_method`: Cash, MobileMoney, Card, Bank, Other
- `service_description`: Service or expense category
- `receipt_id`: Receipt number (nullable)
- `notes`: Additional notes (nullable)
- `date`: Transaction date
- `created_at`, `updated_at`: Timestamps

## 🚀 Deployment

### Production Build

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables

Ensure these are set in production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### Web Server Configuration

#### Nginx Example

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/nailville-salon/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

## 📝 License

This project is licensed under the MIT License.

## 👥 Support

For support, email support@nailvillesalon.com or open an issue in the repository.

## 🙏 Acknowledgments

- Laravel Framework
- Livewire
- Tailwind CSS
- Chart.js
- DataTables
- All open-source contributors

## 📈 Roadmap

- [ ] Mobile app (React Native)
- [ ] SMS notifications
- [ ] Online booking system
- [ ] Customer loyalty program
- [ ] Multi-location support
- [ ] Advanced analytics dashboard
- [ ] Integration with accounting software

---

**Built with ❤️ for Nailville Salon**
