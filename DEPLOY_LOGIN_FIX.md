# Login Performance Fix - Deployment Guide

## Problem
Users experiencing slow login and "page expired" errors after multiple attempts.

## Solution Applied
Multiple performance optimizations to speed up login and prevent session issues.

---

## Files Changed

### 1. Configuration Files
- `config/database.php` - Added connection timeout and optimization
- `config/session.php` - Increased lifetime and cleanup frequency
- `.env` - Updated session lifetime to 240 minutes
- `.env.production` - Added DB timeout and session settings

### 2. Application Files
- `app/Providers/FortifyServiceProvider.php` - Added user lookup caching
- `resources/views/auth/login.blade.php` - Added loading indicator
- `app/Console/Commands/CleanupSessions.php` - New cleanup command
- `app/Console/Kernel.php` - Scheduled automatic session cleanup

### 3. Database
- `database/migrations/2026_06_10_120000_optimize_sessions_table.php` - Session table optimization

---

## Deployment Steps for Production

### Step 1: Install Required PHP Extensions (If Missing)

```bash
# On Ubuntu/Debian
sudo apt update
sudo apt install -y php-mysql php-pdo php-xml php-dom php-mbstring php-curl

# Or run the provided script
./fix-php-extensions.sh

# Verify extensions are loaded
php -m | grep -E "(pdo_mysql|PDO|dom|xml)"
```

### Step 2: Upload Files to Production

Upload these changed files to your production server:
- All files in `config/` directory
- `app/Providers/FortifyServiceProvider.php`
- `app/Console/Commands/CleanupSessions.php`
- `app/Console/Kernel.php`
- `resources/views/auth/login.blade.php`
- `database/migrations/2026_06_10_120000_optimize_sessions_table.php`
- `.env.production` (rename to `.env` on server)

### Step 3: Run Migrations on Production

```bash
# SSH into your production server
cd /path/to/nailville

# Run migrations
php artisan migrate --force

# If migration fails, manually run this SQL:
# ALTER TABLE sessions DROP INDEX sessions_last_activity_index;
# ALTER TABLE sessions ADD INDEX sessions_last_activity_user_id_index (last_activity, user_id);
```

### Step 4: Clear Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Clean Up Old Sessions

```bash
# Run initial cleanup
php artisan sessions:cleanup

# This will remove old sessions and improve performance
```

### Step 6: Setup Cron Job (Important!)

Add this to your crontab to run scheduled tasks (including session cleanup):

```bash
# Open crontab
crontab -e

# Add this line (adjust path to your project)
* * * * * cd /path/to/nailville && php artisan schedule:run >> /dev/null 2>&1
```

This will automatically clean up old sessions every hour.

### Step 7: Restart Web Server

```bash
# For Apache
sudo systemctl restart apache2

# For Nginx with PHP-FPM
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

---

## Alternative: Manual SQL Deployment (If Migrations Fail)

If `php artisan migrate` doesn't work, run this SQL directly in your database:

```sql
-- Optimize sessions table
ALTER TABLE sessions DROP INDEX IF EXISTS sessions_last_activity_index;
ALTER TABLE sessions ADD INDEX sessions_last_activity_user_id_index (last_activity, user_id);

-- Optional: Clean up old sessions (adjust timestamp as needed)
DELETE FROM sessions WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR));
```

---

## Testing

1. **Clear your browser cookies** for the Nailville domain
2. **Try logging in** - should be fast now
3. **Check the login button** shows "Signing in..." spinner
4. **Wait on the login page** for 3-4 minutes, then try logging in (should work without "page expired")

---

## What Was Fixed

✅ **Session Lifetime**: 120 → 240 minutes (less expiration)  
✅ **Database Timeouts**: Added 5-10 second connection timeout  
✅ **User Lookup Caching**: 1-minute cache reduces DB queries  
✅ **Automatic Session Cleanup**: Hourly cleanup prevents bloat  
✅ **Better Session Indexes**: Faster queries on sessions table  
✅ **Visual Feedback**: Loading spinner prevents double-submission  
✅ **Session Garbage Collection**: 2x more frequent cleanup  

---

## Monitoring

After deployment, monitor these:

1. **Login speed** - Should be under 2 seconds
2. **Sessions table size** - Should stay manageable
3. **Error logs** - Check for any new errors

```bash
# Check sessions table size
SELECT COUNT(*) FROM sessions;

# Check oldest session
SELECT FROM_UNIXTIME(MIN(last_activity)) as oldest_session FROM sessions;

# Monitor logs
tail -f storage/logs/laravel.log
```

---

## Rollback (If Needed)

If issues occur, you can rollback the session optimization:

```sql
-- Rollback sessions table changes
ALTER TABLE sessions DROP INDEX sessions_last_activity_user_id_index;
ALTER TABLE sessions ADD INDEX sessions_last_activity_index (last_activity);
```

Then restore the old configuration files from your backup.

---

## Support

If you encounter issues:
1. Check PHP extensions are installed: `php -m`
2. Check database connection: `php artisan tinker` → `DB::connection()->getPdo();`
3. Check sessions table exists: `SHOW TABLES LIKE 'sessions';`
4. Check error logs: `storage/logs/laravel.log`

---

**Deployment Date**: June 10, 2026  
**Tested On**: PHP 8.5.4, MySQL 8.0, Laravel 10.x
