# 🧪 Testing Checklist

## Pre-Testing Setup

### 1. Ensure Admin User Exists

```sql
-- Check if you have an admin user
SELECT id, name, email, admin, activity FROM users WHERE admin = 1;

-- If no admin exists, update your user to admin
UPDATE users SET admin = 1, activity = 'Active' WHERE email = 'your-email@example.com';
```

### 2. Add Sample Notifications

```sql
-- Insert sample notifications for testing
INSERT INTO notifications (type, title, message, priority, category, is_read, created_at) VALUES
('daily', 'Daily Income Report', 'Today\'s total income is $2,450.00', 'medium', 'income', 0, NOW()),
('alert', 'Low Stock Alert', 'Product XYZ is running low on stock!', 'critical', 'alert', 0, NOW()),
('goal_achieved', 'Monthly Goal Achieved! 🎉', 'Congratulations! You reached your monthly goal.', 'high', 'goal', 0, NOW()),
('insight', 'Weekly Performance', 'Your sales increased by 15% this week.', 'medium', 'insight', 1, NOW()),
('system', 'System Update', 'New features have been added to the system.', 'low', 'system', 1, NOW());
```

## 🔐 System Users Testing

### Access Control

-   [ ] Login as admin user
-   [ ] Navigate to **Settings > System Users** in sidebar
-   [ ] Verify page loads successfully
-   [ ] Verify "System Users" text (not "System User Management")

### List Users (DataTables)

-   [ ] Table displays all users
-   [ ] Columns show: Name, Email, Role, Status, Created, Actions
-   [ ] Role badges display correctly (Admin=purple, User=blue)
-   [ ] Status badges display correctly (Active=green, Inactive=red)
-   [ ] Pagination works (10, 25, 50, 100 records)
-   [ ] Search functionality works
-   [ ] Sorting works on all columns
-   [ ] Dark mode styling looks good

### Create User

-   [ ] Click "Add System User" button
-   [ ] Form displays correctly
-   [ ] Fill in all fields:
    -   Name: Test User
    -   Email: test@example.com
    -   Password: Test123!@#
    -   Confirm Password: Test123!@#
    -   Role: Regular User
-   [ ] Submit form
-   [ ] Success message displays
-   [ ] Redirects to users list
-   [ ] New user appears in table
-   [ ] Test validation errors (empty fields, mismatched passwords)

### Edit User

-   [ ] Click "Edit" button on a user
-   [ ] Form pre-fills with user data
-   [ ] Change name
-   [ ] Change email
-   [ ] Change role (User ↔ Admin)
-   [ ] Leave password blank (should keep existing)
-   [ ] Submit form
-   [ ] Success message displays
-   [ ] Changes reflect in users list

### Change Password

-   [ ] Click "Edit" on a user
-   [ ] Enter new password
-   [ ] Confirm new password
-   [ ] Submit form
-   [ ] Success message displays
-   [ ] Try logging in with new password (optional)

### Toggle Status

-   [ ] Click "Deactivate" on an active user
-   [ ] Confirm in SweetAlert2 dialog
-   [ ] User status changes to "Inactive" (red badge)
-   [ ] Click "Activate" on inactive user
-   [ ] Confirm in dialog
-   [ ] User status changes to "Active" (green badge)
-   [ ] Try to deactivate your own account (should fail with error)

### Delete User

-   [ ] Click "Delete" on a user (not yourself)
-   [ ] Confirm in SweetAlert2 dialog
-   [ ] User is removed from list
-   [ ] Success message displays
-   [ ] Try to delete your own account (should not show delete button)

### Mobile Responsive

-   [ ] Open on mobile device or resize browser
-   [ ] Table collapses to card view
-   [ ] All buttons are touch-friendly
-   [ ] Forms stack vertically
-   [ ] Navigation works smoothly

## 🔔 Notifications Testing

### Dropdown Display

-   [ ] Click notification bell icon in header
-   [ ] Dropdown opens smoothly
-   [ ] Notifications load from database
-   [ ] Unread count badge shows (red dot)
-   [ ] Loading spinner shows while fetching

### Notification Items

-   [ ] Each notification displays:
    -   Category icon (💰 💸 🎯 ⚠️ 💡 ⚙️)
    -   Title (bold)
    -   Message (truncated if long)
    -   Time ago (Just now, 5m ago, 2h ago, etc.)
    -   Unread indicator (blue dot)
-   [ ] Priority styling works:
    -   Critical: Red border-left
    -   High: Orange border-left
    -   Medium: Blue background
    -   Low: Gray background

### Mark as Read

-   [ ] Click on an unread notification
-   [ ] Notification opacity changes
-   [ ] Unread count decreases
-   [ ] Blue dot disappears
-   [ ] Redirects to notifications page

### Empty State

-   [ ] Delete all notifications from database
-   [ ] Open dropdown
-   [ ] "No notifications yet" message displays
-   [ ] Icon shows

### View All Link

-   [ ] Click "View all notifications" at bottom
-   [ ] Redirects to `/notifications` page
-   [ ] Page displays all notifications

### Dark Mode

-   [ ] Toggle dark mode
-   [ ] Dropdown background is dark
-   [ ] Text colors are readable
-   [ ] Borders are visible
-   [ ] Icons are visible
-   [ ] Badges have proper contrast

## 📱 Sidebar Testing

### Active State Management

-   [ ] Navigate to **Dashboard > Main**
-   [ ] Dashboard dropdown stays open
-   [ ] Dashboard parent has violet background
-   [ ] Navigate to **Transactions > Income**
-   [ ] Transactions dropdown stays open
-   [ ] Transactions parent has violet background
-   [ ] Navigate to **Reports > Net Income**
-   [ ] Reports dropdown stays open
-   [ ] Reports parent has violet background
-   [ ] Navigate to **Inventory > Manage Items**
-   [ ] Inventory dropdown stays open
-   [ ] Inventory parent has violet background
-   [ ] Navigate to **Settings > System Users**
-   [ ] Settings dropdown stays open
-   [ ] Settings parent has violet background

### Dropdown Functionality

-   [ ] Click Dashboard dropdown
-   [ ] Opens/closes smoothly
-   [ ] Arrow icon rotates
-   [ ] Test all other dropdowns
-   [ ] Multiple dropdowns can be open
-   [ ] Clicking parent toggles dropdown

### Mobile Sidebar

-   [ ] Open on mobile device
-   [ ] Sidebar is hidden by default
-   [ ] Hamburger menu opens sidebar
-   [ ] Sidebar overlays content
-   [ ] Click outside closes sidebar
-   [ ] Dropdowns work in mobile view

## 🎨 Dark Mode Testing

### System Users Page

-   [ ] Toggle dark mode
-   [ ] Background is dark gray
-   [ ] Text is light/white
-   [ ] Table headers are visible
-   [ ] Table rows have proper contrast
-   [ ] Badges are readable
-   [ ] Buttons have proper colors
-   [ ] Forms have dark backgrounds
-   [ ] Input fields are visible

### Notifications

-   [ ] Dropdown has dark background
-   [ ] Text is readable
-   [ ] Icons are visible
-   [ ] Badges have contrast
-   [ ] Borders are visible
-   [ ] Hover states work

### Sidebar

-   [ ] Background is dark
-   [ ] Text is light
-   [ ] Icons are visible
-   [ ] Active states show properly
-   [ ] Hover states work

## 🔍 Browser Testing

Test in multiple browsers:

-   [ ] Chrome/Edge
-   [ ] Firefox
-   [ ] Safari (if available)
-   [ ] Mobile browsers

## 🐛 Error Scenarios

### System Users

-   [ ] Try accessing `/admin/users` as non-admin (should get 403)
-   [ ] Submit create form with invalid email
-   [ ] Submit create form with short password
-   [ ] Submit create form with mismatched passwords
-   [ ] Try to edit non-existent user (should 404)
-   [ ] Try to delete non-existent user (should 404)

### Notifications

-   [ ] Open dropdown with no internet (should show error)
-   [ ] Try to mark non-existent notification as read
-   [ ] Open dropdown multiple times quickly

## ✅ Success Criteria

All checkboxes above should be checked ✓

### Expected Results:

1. **System Users**: Full CRUD works, admin-only access, proper validation
2. **Notifications**: Load from database, mark as read, proper styling
3. **Sidebar**: Dropdowns stay open when child active, proper styling
4. **Dark Mode**: All components look good in dark mode
5. **Responsive**: Works on mobile, tablet, and desktop
6. **Security**: Non-admins can't access system users

## 📝 Notes

Record any issues found:

```
Issue: [Description]
Steps to Reproduce: [Steps]
Expected: [What should happen]
Actual: [What actually happened]
```

---

**Testing Date**: ******\_\_\_******  
**Tester**: ******\_\_\_******  
**Status**: ⬜ Pass / ⬜ Fail  
**Notes**: ******\_\_\_******
