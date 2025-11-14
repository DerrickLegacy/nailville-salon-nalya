# Quick Start Guide

Get up and running with Nailville Salon Management System in 5 minutes!

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

## Installation (5 Steps)

### 1. Clone & Install

```bash
git clone <repository-url> nailville-salon
cd nailville-salon
composer install
npm install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=nailville_salon
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Setup Database

```bash
php artisan migrate
php artisan db:seed  # Optional: adds sample data
```

### 4. Build Assets

```bash
npm run build
```

### 5. Start Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Login

After seeding:
- **Email**: admin@nailville.com
- **Password**: password

## Common Commands

```bash
# Development
npm run dev              # Watch assets
php artisan serve        # Start server

# Testing
php artisan test         # Run tests
php artisan test --coverage  # With coverage

# Database
php artisan migrate:fresh    # Reset database
php artisan db:seed          # Seed data

# Cache
php artisan cache:clear      # Clear cache
php artisan config:clear     # Clear config
php artisan view:clear       # Clear views

# Production
npm run build                # Build assets
php artisan config:cache     # Cache config
php artisan route:cache      # Cache routes
php artisan view:cache       # Cache views
```

## Key Features

### Transactions
- **Income**: `/transactions/income`
- **Expense**: `/transactions/expense`
- Create, edit, delete transactions
- Filter by date, payment method
- Export to PDF

### Reports
- **Income vs Expense**: `/reports/income-expense`
- **Net Income**: `/reports/net-income`
- **Profit Analysis**: `/reports/profit`

### Inventory
- **Manage**: `/inventory/manage`
- Track stock levels
- Set alerts

### Settings
- **Account**: `/settings/account`
- **Users**: `/settings/users`
- **App Config**: `/settings/app-configurations`

## Troubleshooting

### Issue: Database connection error
```bash
# Check .env credentials
# Create database if not exists
mysql -u root -p
CREATE DATABASE nailville_salon;
```

### Issue: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Assets not loading
```bash
npm run build
php artisan view:clear
```

### Issue: 500 Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan optimize:clear
```

## Next Steps

1. **Read Full Documentation**: [README.md](README.md)
2. **Configure Settings**: Visit `/settings/app-configurations`
3. **Add Employees**: Visit `/settings/users`
4. **Create First Transaction**: Visit `/transactions/income`
5. **View Reports**: Visit `/reports/income-expense`

## Need Help?

- **Documentation**: [README.md](README.md)
- **Contributing**: [CONTRIBUTING.md](CONTRIBUTING.md)
- **Deployment**: [DEPLOYMENT.md](DEPLOYMENT.md)
- **Issues**: Open a GitHub issue

---

**Happy Managing! 🎉**
