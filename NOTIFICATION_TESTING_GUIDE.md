# 🔔 Notification Testing Guide

## Quick Start

### Method 1: Using the Shell Script (Easiest)

```bash
# Generate notifications every 30 seconds (default)
./generate-notifications.sh

# Generate notifications every 10 seconds
./generate-notifications.sh 10

# Generate notifications every 60 seconds
./generate-notifications.sh 60
```

### Method 2: Using Artisan Command Directly

```bash
# Default: every 30 seconds
php artisan notifications:generate-test

# Custom interval: every 15 seconds
php artisan notifications:generate-test --interval=15

# Every 5 seconds (for rapid testing)
php artisan notifications:generate-test --interval=5
```

## What It Does

The command generates realistic test notifications with:

### Notification Types

1. **Income Reports** 💰

    - Daily income summaries
    - Revenue updates
    - Transaction counts

2. **Stock Alerts** ⚠️

    - Low stock warnings
    - Reorder notifications
    - Critical inventory alerts

3. **Goal Achievements** 🎯

    - Daily/Weekly/Monthly goals
    - Target completions
    - Success celebrations

4. **Performance Insights** 💡

    - Sales trends
    - Customer analytics
    - Performance comparisons

5. **Expense Alerts** 💸

    - High expense warnings
    - Budget notifications
    - Spending summaries

6. **System Updates** ⚙️
    - Feature announcements
    - Maintenance notices
    - System improvements

## Testing Workflow

### 1. Start the Generator

```bash
./generate-notifications.sh
```

You'll see output like:

```
🔔 Starting test notification generator...
📊 Generating notifications every 30 seconds
⏹️  Press Ctrl+C to stop

[14:30:15] #1 - income: Daily Income Report
   └─ Today's total income is $2450.00 from 23 transactions

[14:30:45] #2 - alert: Low Stock Alert
   └─ Product "Nail Polish" is running low on stock! Only 3 units remaining.

[14:31:15] #3 - goal: Goal Achieved! 🎉
   └─ Congratulations! You've reached your weekly goal of $15000
```

### 2. Test the Notification Dropdown

While the generator is running:

1. **Open your application** in a browser
2. **Click the notification bell** icon in the header
3. **Watch notifications appear** in real-time
4. **Refresh the page** to see new notifications
5. **Test the unread count** badge

### 3. Test Notification Features

#### Mark as Read

-   Click on any notification in the dropdown
-   Verify it marks as read
-   Check unread count decreases

#### View All Notifications

-   Click "View all notifications" link
-   Verify all notifications display
-   Test "Mark All as Read" button

#### Priority Styling

-   **Critical** notifications have red border
-   **High** priority has orange border
-   **Medium** has blue background
-   **Low** has gray background

#### Category Icons

-   💰 Income
-   💸 Expense
-   🎯 Goal
-   ⚠️ Alert
-   💡 Insight
-   ⚙️ System

### 4. Stop the Generator

Press `Ctrl+C` to stop generating notifications

## Advanced Usage

### Generate Specific Number of Notifications

```bash
# Generate 10 notifications quickly (every 2 seconds)
php artisan notifications:generate-test --interval=2
# Wait 20 seconds, then press Ctrl+C
```

### Background Generation

```bash
# Run in background
nohup ./generate-notifications.sh 30 > notifications.log 2>&1 &

# Check the process
ps aux | grep notifications:generate-test

# Stop background process
pkill -f "notifications:generate-test"
```

### View Generated Notifications in Database

```bash
# Check latest 10 notifications
php artisan tinker
>>> Notification::latest()->take(10)->get(['id', 'title', 'category', 'priority', 'created_at']);

# Count unread notifications
>>> Notification::unread()->count();

# Count by category
>>> Notification::select('category', DB::raw('count(*) as total'))->groupBy('category')->get();
```

## Testing Checklist

### Dropdown Component

-   [ ] Notifications load when clicking bell icon
-   [ ] Unread count badge shows correct number
-   [ ] Category icons display correctly
-   [ ] Priority colors work (critical=red, high=orange, etc.)
-   [ ] Time formatting works ("Just now", "5m ago", etc.)
-   [ ] Loading spinner shows while fetching
-   [ ] Empty state shows when no notifications
-   [ ] "View all" link works

### Mark as Read

-   [ ] Clicking notification marks it as read
-   [ ] Unread count decreases
-   [ ] Blue dot disappears
-   [ ] Opacity changes on read notifications

### Notifications Page

-   [ ] All notifications display
-   [ ] Pagination works
-   [ ] "Mark All as Read" button works
-   [ ] Individual mark as read works
-   [ ] Priority badges show correctly
-   [ ] Category badges show correctly

### Dark Mode

-   [ ] Dropdown looks good in dark mode
-   [ ] Text is readable
-   [ ] Icons are visible
-   [ ] Badges have proper contrast
-   [ ] Borders are visible

### Mobile Responsive

-   [ ] Dropdown works on mobile
-   [ ] Touch-friendly buttons
-   [ ] Proper spacing
-   [ ] Readable text

## Sample Notifications Generated

### Income Notification

```json
{
    "type": "daily",
    "title": "Daily Income Report",
    "message": "Today's total income is $2450.00 from 23 transactions",
    "priority": "medium",
    "category": "income",
    "data": {
        "amount": 2450,
        "transactions": 23,
        "generated_at": "2025-11-14T14:30:15Z",
        "test_mode": true
    }
}
```

### Alert Notification

```json
{
    "type": "alert",
    "title": "Low Stock Alert",
    "message": "Product 'Nail Polish' is running low! Only 3 units remaining.",
    "priority": "critical",
    "category": "alert",
    "data": {
        "product": "Nail Polish",
        "stock_level": 3,
        "reorder_level": 15,
        "generated_at": "2025-11-14T14:30:45Z",
        "test_mode": true
    }
}
```

### Goal Notification

```json
{
    "type": "goal_achieved",
    "title": "Goal Achieved! 🎉",
    "message": "Congratulations! You've reached your weekly goal of $15000",
    "priority": "high",
    "category": "goal",
    "data": {
        "target": 15000,
        "achieved": 15340,
        "percentage": 102,
        "generated_at": "2025-11-14T14:31:15Z",
        "test_mode": true
    }
}
```

## Cleanup

### Delete Test Notifications

```bash
# Delete all test notifications
php artisan tinker
>>> Notification::whereJsonContains('data->test_mode', true)->delete();

# Or delete all notifications
>>> Notification::truncate();
```

### SQL Cleanup

```sql
-- Delete test notifications
DELETE FROM notifications WHERE JSON_EXTRACT(data, '$.test_mode') = true;

-- Delete all notifications
TRUNCATE TABLE notifications;

-- Delete old notifications (older than 7 days)
DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);
```

## Troubleshooting

### Command Not Found

```bash
# Make sure you're in the project root
cd /opt/lampp/htdocs/nailville-salon-nalya

# Check if command exists
php artisan list | grep notifications
```

### Notifications Not Appearing

1. Check database connection
2. Verify notifications table exists
3. Check browser console for errors
4. Ensure route `/notifications/list` works

### Permission Denied

```bash
# Make script executable
chmod +x generate-notifications.sh
```

### Process Won't Stop

```bash
# Force kill
pkill -9 -f "notifications:generate-test"
```

## Tips

1. **Start with longer intervals** (30-60 seconds) to avoid overwhelming the database
2. **Use shorter intervals** (5-10 seconds) for rapid testing
3. **Monitor the database** to ensure notifications are being created
4. **Test in different browsers** to ensure compatibility
5. **Test dark mode** while notifications are generating
6. **Test on mobile** devices for responsive design

## Example Testing Session

```bash
# Terminal 1: Start notification generator
./generate-notifications.sh 15

# Terminal 2: Monitor database
watch -n 5 'mysql -u root -p -e "SELECT COUNT(*) as total, category, is_read FROM nailville.notifications GROUP BY category, is_read"'

# Browser: Test the application
# 1. Open http://localhost/nailville-salon-nalya
# 2. Click notification bell
# 3. Watch notifications appear
# 4. Test mark as read
# 5. Test view all
# 6. Toggle dark mode
```

## Performance Notes

-   Each notification is ~500 bytes in database
-   1000 notifications ≈ 500 KB
-   Safe to generate hundreds of test notifications
-   Clean up periodically to maintain performance

---

**Created**: November 14, 2025  
**Purpose**: Testing notification system  
**Status**: Ready for use
