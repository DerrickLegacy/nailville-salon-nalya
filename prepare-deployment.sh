#!/bin/bash

echo "🚀 Preparing Laravel Application for Hostinger Deployment"
echo "=========================================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if we're in Laravel root
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: artisan file not found. Please run this script from Laravel root directory.${NC}"
    exit 1
fi

echo -e "${YELLOW}📦 Step 1: Installing production dependencies...${NC}"
composer install --optimize-autoloader --no-dev
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dependencies installed${NC}"
else
    echo -e "${RED}❌ Failed to install dependencies${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}🔧 Step 2: Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
echo -e "${GREEN}✅ Application optimized${NC}"

echo ""
echo -e "${YELLOW}📝 Step 3: Creating deployment package...${NC}"

# Create deployment directory
mkdir -p deployment
mkdir -p deployment/laravel
mkdir -p deployment/public_html

# Copy Laravel files (excluding public folder)
echo "Copying Laravel files..."
rsync -av --progress \
    --exclude='node_modules' \
    --exclude='public' \
    --exclude='.git' \
    --exclude='tests' \
    --exclude='storage/logs/*' \
    --exclude='deployment' \
    --exclude='.env' \
    . deployment/laravel/

# Copy public folder contents
echo "Copying public folder contents..."
rsync -av --progress public/ deployment/public_html/

# Copy the modified index.php
if [ -f "index-hostinger.php" ]; then
    cp index-hostinger.php deployment/public_html/index.php
    echo -e "${GREEN}✅ Modified index.php copied${NC}"
fi

# Create .env.example for production
cat > deployment/laravel/.env.example << 'EOF'
APP_NAME="Nailville Salon"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF

echo -e "${GREEN}✅ .env.example created${NC}"

# Create README for deployment
cat > deployment/README.txt << 'EOF'
HOSTINGER DEPLOYMENT INSTRUCTIONS
==================================

1. UPLOAD FILES:
   - Upload 'laravel' folder to: /home/username/laravel/
   - Upload 'public_html' contents to: /home/username/public_html/

2. SET PERMISSIONS (via SSH or File Manager):
   chmod -R 775 /home/username/laravel/storage
   chmod -R 775 /home/username/laravel/bootstrap/cache

3. CONFIGURE .ENV:
   - Copy .env.example to .env
   - Update database credentials
   - Set APP_URL to your domain

4. RUN COMMANDS (via SSH):
   cd ~/laravel
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan storage:link

5. IMPORT DATABASE:
   - Use phpMyAdmin to import nailville_schema.sql

6. CREATE ADMIN USER:
   cd ~/laravel
   php artisan tinker
   
   $user = new App\Models\User();
   $user->name = 'Admin';
   $user->email = 'admin@yourdomain.com';
   $user->password = Hash::make('YourPassword');
   $user->admin = 1;
   $user->activity = 'Active';
   $user->save();
   exit

7. TEST:
   Visit https://yourdomain.com

For detailed instructions, see HOSTINGER_DEPLOYMENT_GUIDE.md
EOF

echo -e "${GREEN}✅ Deployment README created${NC}"

# Create archive
echo ""
echo -e "${YELLOW}📦 Step 4: Creating ZIP archive...${NC}"
cd deployment
zip -r ../nailville-deployment.zip . -x "*.DS_Store"
cd ..

echo ""
echo -e "${GREEN}=========================================================="
echo "✅ Deployment package ready!"
echo "=========================================================="
echo ""
echo "📁 Files created:"
echo "   - deployment/laravel/          (Upload to /home/username/laravel/)"
echo "   - deployment/public_html/      (Upload to /home/username/public_html/)"
echo "   - nailville-deployment.zip     (Complete package)"
echo ""
echo "📖 Next steps:"
echo "   1. Extract nailville-deployment.zip"
echo "   2. Upload folders to Hostinger"
echo "   3. Follow instructions in deployment/README.txt"
echo "   4. See HOSTINGER_DEPLOYMENT_GUIDE.md for details"
echo ""
echo -e "${YELLOW}⚠️  Don't forget to:"
echo "   - Configure .env file"
echo "   - Import database"
echo "   - Set permissions"
echo "   - Create admin user"
echo -e "${NC}"
