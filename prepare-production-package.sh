#!/bin/bash

echo "=========================================="
echo "Preparing Production Deployment Package"
echo "=========================================="
echo ""

# Create deployment directory
DEPLOY_DIR="nailville-login-fix-$(date +%Y%m%d)"
mkdir -p "$DEPLOY_DIR"

echo "Creating deployment package in: $DEPLOY_DIR"
echo ""

# Create directory structure
mkdir -p "$DEPLOY_DIR/config"
mkdir -p "$DEPLOY_DIR/app/Providers"
mkdir -p "$DEPLOY_DIR/app/Console/Commands"
mkdir -p "$DEPLOY_DIR/app/Console"
mkdir -p "$DEPLOY_DIR/resources/views/auth"
mkdir -p "$DEPLOY_DIR/database/migrations"
mkdir -p "$DEPLOY_DIR/sql"

# Copy configuration files
cp config/database.php "$DEPLOY_DIR/config/"
cp config/session.php "$DEPLOY_DIR/config/"

# Copy application files
cp app/Providers/FortifyServiceProvider.php "$DEPLOY_DIR/app/Providers/"
cp app/Console/Commands/CleanupSessions.php "$DEPLOY_DIR/app/Console/Commands/"
cp app/Console/Kernel.php "$DEPLOY_DIR/app/Console/"
cp resources/views/auth/login.blade.php "$DEPLOY_DIR/resources/views/auth/"

# Copy migration
cp database/migrations/2026_06_10_120000_optimize_sessions_table.php "$DEPLOY_DIR/database/migrations/"

# Copy environment file
cp .env.production "$DEPLOY_DIR/.env.production"

# Create SQL script for manual execution
cat > "$DEPLOY_DIR/sql/optimize_sessions.sql" << 'EOF'
-- Optimize Sessions Table
-- Run this if php artisan migrate fails

-- Drop old index
ALTER TABLE sessions DROP INDEX IF EXISTS sessions_last_activity_index;

-- Add new composite index
ALTER TABLE sessions ADD INDEX sessions_last_activity_user_id_index (last_activity, user_id);

-- Clean up old sessions (older than 24 hours)
DELETE FROM sessions WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR));

-- Verify
SHOW INDEX FROM sessions;
SELECT COUNT(*) as total_sessions FROM sessions;
EOF

# Copy deployment guide
cp DEPLOY_LOGIN_FIX.md "$DEPLOY_DIR/"

# Create deployment instructions
cat > "$DEPLOY_DIR/README_DEPLOY.txt" << 'EOF'
QUICK DEPLOYMENT INSTRUCTIONS
==============================

1. BACKUP YOUR PRODUCTION DATABASE FIRST!
   mysqldump -u username -p database_name > backup.sql

2. Upload all files in this package to your production server:
   - config/ files → /path/to/nailville/config/
   - app/ files → /path/to/nailville/app/
   - resources/ files → /path/to/nailville/resources/
   - database/migrations/ → /path/to/nailville/database/migrations/
   - .env.production → /path/to/nailville/.env (merge settings)

3. SSH into your server and run:
   cd /path/to/nailville
   php artisan migrate --force
   php artisan config:cache
   php artisan sessions:cleanup

4. If migration fails, run the SQL file manually:
   mysql -u username -p database_name < sql/optimize_sessions.sql

5. Setup cron job:
   crontab -e
   Add: * * * * * cd /path/to/nailville && php artisan schedule:run >> /dev/null 2>&1

6. Restart web server:
   sudo systemctl restart apache2
   # or
   sudo systemctl restart nginx && sudo systemctl restart php8.3-fpm

7. Test login at: https://nailville-salon-nalya.kenvies.com

See DEPLOY_LOGIN_FIX.md for detailed instructions.
EOF

# Create a compressed archive
echo "Creating compressed archive..."
tar -czf "${DEPLOY_DIR}.tar.gz" "$DEPLOY_DIR"

echo ""
echo "=========================================="
echo "✅ Deployment package ready!"
echo "=========================================="
echo ""
echo "Package location: ${DEPLOY_DIR}.tar.gz"
echo "Extracted files: ${DEPLOY_DIR}/"
echo ""
echo "Next steps:"
echo "1. Download ${DEPLOY_DIR}.tar.gz"
echo "2. Upload to your production server"
echo "3. Extract: tar -xzf ${DEPLOY_DIR}.tar.gz"
echo "4. Follow instructions in README_DEPLOY.txt"
echo ""
echo "Or upload files directly via FTP from: ${DEPLOY_DIR}/"
echo ""
