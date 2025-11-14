# Quick Reference Guide - System Users & Notifications

## 🔗 Routes

### System Users (Admin Only)

```
GET    /admin/users                      - List all system users
GET    /admin/users/list                 - API endpoint for DataTables
GET    /admin/users/create-system-user   - Create new user form
POST   /admin/users/store                - Store new user
GET    /admin/users/{id}/edit            - Edit user form
PUT    /admin/users/{id}                 - Update user
GET    /admin/users/{id}/toggle-status   - Activate/Deactivate user
DELETE /admin/users/{id}                 - Delete user
```

### Notifications

```
GET    /notifications                    - Notifications page
GET    /notifications/list               - API endpoint for dropdown
POST   /notifications/{id}/mark-read     - Mark single as read
POST   /notifications/mark-all-read      - Mark all as read
```

## 🎯 Testing Checklist

### System Users

-   [ ] Navigate to Settings > System Users
-   [ ] Verify DataTables loads with existing users
-   [ ] Click "Add System User" button
-   [ ] Create a new user (test validation)
-   [ ] Edit an existing user
-   [ ] Change user password
-   [ ] Toggle user status (Active/Inactive)
-   [ ] Try to delete/deactivate your own account (should fail)
-   [ ] Delete a different user
-   [ ] Verify dark mode styling
-   [ ] Test on mobile device

### Notifications

-   [ ] Click notification bell icon
-   [ ] Verify notifications load from database
-   [ ] Check unread count badge appears
-   [ ] Click a notification to mark as read
-   [ ] Verify unread count decreases
-   [ ] Check different priority styles (critical, high, medium, low)
-   [ ] Verify category icons display correctly
-   [ ] Test "View all notifications" link
-   [ ] Verify dark mode styling
-   [ ] Test on mobile device

### Sidebar

-   [ ] Navigate to Reports > Net Income
-   [ ] Verify Reports dropdown stays open
-   [ ] Verify parent item has active styling
-   [ ] Test all dropdown sections
-   [ ] Verify "System Users" text (not "System User Management")

## 💻 Code Examples

### Create a Notification

```php
use App\Models\Notification;

// Income notification
Notification::create([
    'type' => 'daily',
    'title' => 'Daily Income Summary',
    'message' => 'Today\'s total income: $2,450.00',
    'data' => json_encode(['amount' => 2450.00, 'transactions' => 15]),
    'priority' => 'medium',
    'category' => 'income',
]);

// Critical alert
Notification::create([
    'type' => 'alert',
    'title' => 'Low Stock Alert',
    'message' => 'Product XYZ is running low on stock!',
    'data' => json_encode(['product_id' => 123, 'current_stock' => 2]),
    'priority' => 'critical',
    'category' => 'alert',
]);

// Goal achieved
Notification::create([
    'type' => 'goal_achieved',
    'title' => 'Monthly Goal Achieved! 🎉',
    'message' => 'Congratulations! You\'ve reached your monthly income goal.',
    'data' => json_encode(['goal' => 50000, 'actual' => 52340]),
    'priority' => 'high',
    'category' => 'goal',
]);
```

### Check if User is Admin

```php
// In controller
if (auth()->user()->isAdmin()) {
    // Admin-only logic
}

// In Blade template
@if(auth()->user()->isAdmin())
    <button>Admin Action</button>
@endif
```

### Query Users

```php
// Get all active users
$activeUsers = User::active()->get();

// Get all admins
$admins = User::admins()->get();

// Get inactive users
$inactiveUsers = User::inactive()->get();

// Get non-admin users
$regularUsers = User::nonAdmins()->get();

// Combine scopes
$activeAdmins = User::active()->admins()->get();
```

### Mark Notifications as Read

```php
// Mark single notification
$notification = Notification::find($id);
$notification->markAsRead();

// Mark all unread
Notification::unread()->update([
    'is_read' => true,
    'read_at' => now()
]);

// Get unread count
$count = Notification::unread()->count();
```

## 🎨 UI Components

### Status Badges

```html
<!-- Active User -->
<span
    class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-full text-xs font-semibold"
>
    Active
</span>

<!-- Inactive User -->
<span
    class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400 rounded-full text-xs font-semibold"
>
    Inactive
</span>

<!-- Admin Role -->
<span
    class="px-2 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400 rounded-full text-xs font-semibold"
>
    Admin
</span>

<!-- Regular User -->
<span
    class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400 rounded-full text-xs font-semibold"
>
    User
</span>
```

### Notification Priority Classes

```javascript
// In Alpine.js component
getPriorityClass(priority) {
    const classes = {
        'critical': 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500',
        'high': 'bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500',
        'medium': 'bg-blue-50 dark:bg-blue-900/20',
        'low': 'bg-gray-50 dark:bg-gray-700/20'
    };
    return classes[priority] || classes['medium'];
}
```

### Category Icons

```javascript
getCategoryIcon(category) {
    const icons = {
        'income': '💰',
        'expense': '💸',
        'goal': '🎯',
        'alert': '⚠️',
        'insight': '💡',
        'system': '⚙️'
    };
    return icons[category] || '📣';
}
```

## 🐛 Troubleshooting

### Issue: "Unauthorized action" error

**Solution**: Ensure logged-in user has `admin = 1` in database

### Issue: Notifications not loading

**Solution**:

1. Check database has notifications: `SELECT * FROM notifications LIMIT 5;`
2. Verify route is accessible: `/notifications/list`
3. Check browser console for errors

### Issue: DataTables not displaying

**Solution**:

1. Ensure jQuery and DataTables are loaded
2. Check `/admin/users/list` returns JSON
3. Verify CSRF token is valid

### Issue: Dark mode colors not working

**Solution**:

1. Ensure Tailwind dark mode is enabled
2. Check `dark:` classes are present
3. Verify parent element has dark mode class

### Issue: Can't delete own account (expected behavior)

**Solution**: This is a security feature. Use another admin account to manage your account.

## 📱 Mobile Responsiveness

All components are mobile-responsive:

-   DataTables collapse to card view on small screens
-   Dropdowns adjust position automatically
-   Forms stack vertically on mobile
-   Touch-friendly button sizes (min 44x44px)
-   Proper spacing and padding

## 🔒 Security Notes

1. **Admin middleware** protects all system user routes
2. **CSRF tokens** required for all POST/PUT/DELETE requests
3. **Password hashing** using Laravel's Hash facade
4. **Self-protection** prevents users from deleting/deactivating themselves
5. **Input validation** on all forms
6. **SQL injection protection** via Eloquent ORM

## 📊 Database Queries

### Check User Status

```sql
SELECT id, name, email, admin, activity FROM users;
```

### Check Notifications

```sql
SELECT id, title, category, priority, is_read, created_at
FROM notifications
ORDER BY created_at DESC
LIMIT 10;
```

### Count Unread Notifications

```sql
SELECT COUNT(*) as unread_count
FROM notifications
WHERE is_read = 0;
```

### Find Admin Users

```sql
SELECT id, name, email, activity
FROM users
WHERE admin = 1;
```

---

**Last Updated**: November 14, 2025
**Version**: 1.0
