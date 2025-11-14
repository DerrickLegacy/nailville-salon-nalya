# Deployment Guide

This guide covers deploying the Nailville Salon Management System to production.

## Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Code reviewed and approved
- [ ] Database backup created
- [ ] Environment variables configured
- [ ] SSL certificate ready
- [ ] Domain configured
- [ ] Server requirements met

## Server Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Nginx or Apache
- **Memory**: 512MB RAM minimum (1GB+ recommended)
- **Storage**: 10GB minimum
- **Node.js**: 18+ (for building assets)

### PHP Extensions Required
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- cURL
- GD or Imagick
```

## Deployment Methods

### Method 1: Manual Deployment

#### 1. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
  php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y
```

#### 2. Clone Repository

```bash
cd /var/www
sudo git clone <repository-url> nailville-salon
cd nailville-salon
sudo chown -R www-data:www-data /var/www/nailville-salon
```

#### 3. Install Dependencies

```bash
# PHP dependencies
composer install --no-dev --optimize-autoloader

# Node dependencies
npm ci --production

# Build assets
npm run build
```

#### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file
nano .env
```

Configure these variables:

```env
APP_NAME="Nailville Salon"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nailville_salon
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### 5. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE nailville_salon;
CREATE USER 'salon_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON nailville_salon.* TO 'salon_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

#### 6. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### 7. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/nailville-salon
sudo chmod -R 755 /var/www/nailville-salon
sudo chmod -R 775 /var/www/nailville-salon/storage
sudo chmod -R 775 /var/www/nailville-salon/bootstrap/cache
```

#### 8. Configure Nginx

Create `/etc/nginx/sites-available/nailville-salon`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/nailville-salon/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    index index.php;

    charset utf-8;

    # Logging
    access_log /var/log/nginx/nailville-salon-access.log;
    error_log /var/log/nginx/nailville-salon-error.log;

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/nailville-salon /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 9. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### 10. Setup Cron Jobs

```bash
sudo crontab -e -u www-data
```

Add:

```cron
* * * * * cd /var/www/nailville-salon && php artisan schedule:run >> /dev/null 2>&1
```

#### 11. Setup Queue Worker (Optional)

Create `/etc/systemd/system/nailville-worker.service`:

```ini
[Unit]
Description=Nailville Salon Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/nailville-salon
ExecStart=/usr/bin/php /var/www/nailville-salon/artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Enable and start:

```bash
sudo systemctl enable nailville-worker
sudo systemctl start nailville-worker
```

### Method 2: Docker Deployment

#### 1. Create Dockerfile

```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci --production && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

#### 2. Create docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: nailville-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - nailville-network

  nginx:
    image: nginx:alpine
    container_name: nailville-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - nailville-network

  db:
    image: mysql:8.0
    container_name: nailville-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: nailville_salon
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: salon_user
      MYSQL_PASSWORD: salon_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - nailville-network

networks:
  nailville-network:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

#### 3. Deploy with Docker

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
```

## Post-Deployment

### 1. Verify Installation

```bash
# Check application status
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check queue workers
php artisan queue:monitor

# View logs
tail -f storage/logs/laravel.log
```

### 2. Setup Monitoring

Install monitoring tools:

```bash
# Install Laravel Telescope (development only)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Install Laravel Horizon (for queues)
composer require laravel/horizon
php artisan horizon:install
```

### 3. Backup Strategy

Create backup script `/usr/local/bin/backup-nailville.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/backups/nailville"
DATE=$(date +%Y%m%d_%H%M%S)
APP_DIR="/var/www/nailville-salon"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u salon_user -p'salon_password' nailville_salon > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz $APP_DIR/storage $APP_DIR/.env

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and schedule:

```bash
sudo chmod +x /usr/local/bin/backup-nailville.sh
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-nailville.sh
```

### 4. Security Hardening

```bash
# Disable directory listing
# Add to Nginx config: autoindex off;

# Hide PHP version
# Edit /etc/php/8.2/fpm/php.ini
# Set: expose_php = Off

# Setup fail2ban
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban

# Setup firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Troubleshooting

### Common Issues

**Issue**: 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log

# Check permissions
sudo chown -R www-data:www-data storage bootstrap/cache
```

**Issue**: Database connection failed
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check credentials in .env
```

**Issue**: Assets not loading
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear
```

## Rollback Procedure

```bash
# 1. Backup current state
mysqldump -u salon_user -p nailville_salon > rollback_backup.sql

# 2. Restore previous version
git checkout <previous-commit>

# 3. Restore database
mysql -u salon_user -p nailville_salon < previous_backup.sql

# 4. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 5. Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## Maintenance Mode

```bash
# Enable maintenance mode
php artisan down --message="Scheduled maintenance" --retry=60

# Perform updates
git pull
composer install --no-dev
php artisan migrate --force
npm run build

# Disable maintenance mode
php artisan up
```

## Performance Optimization

```bash
# Enable OPcache
# Edit /etc/php/8.2/fpm/php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2

# Enable Redis cache
composer require predis/predis
# Update .env: CACHE_DRIVER=redis

# Optimize images
npm install -g imagemin-cli
find public/images -name "*.jpg" -exec imagemin {} --out-dir=public/images \;
```

---

**Need Help?** Contact support@nailvillesalon.com
