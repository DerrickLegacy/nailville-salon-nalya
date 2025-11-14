# 🚀 Quick Test - Notifications

## Start Generating Notifications

### Option 1: Simple (30 seconds interval)

```bash
./generate-notifications.sh
```

### Option 2: Fast Testing (10 seconds interval)

```bash
./generate-notifications.sh 10
```

### Option 3: Very Fast (5 seconds interval)

```bash
php artisan notifications:generate-test --interval=5
```

## What You'll See

```
🔔 Starting test notification generator...
📊 Generating notifications every 30 seconds
⏹️  Press Ctrl+C to stop

[14:30:15] #1 - income: Daily Income Report
   └─ Today's total income is $2450.00 from 23 transactions

[14:30:45] #2 - alert: Low Stock Alert
   └─ Product "Nail Polish" is running low on stock! Only 3 units remaining.
```

## Test in Browser

1. **Open your app**: `http://localhost/nailville-salon-nalya`
2. **Click the bell icon** 🔔 in the header
3. **Watch notifications appear** in real-time
4. **Click a notification** to mark as read
5. **Click "View all"** to see full list

## Stop Generating

Press `Ctrl+C` in the terminal

## Clean Up Test Data

```bash
php artisan tinker
>>> Notification::whereJsonContains('data->test_mode', true)->delete();
>>> exit
```

## Quick Check Database

```bash
php artisan tinker
>>> Notification::count()
>>> Notification::unread()->count()
>>> exit
```

---

**That's it!** Start the generator and test your notifications! 🎉
