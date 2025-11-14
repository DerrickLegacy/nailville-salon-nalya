# 🧪 Quick Test - Inactive User Login Block

## Test Scenario 1: Inactive User Cannot Login

### Step 1: Deactivate a Test User

```sql
-- Via SQL
UPDATE users SET activity = 'Inactive' WHERE email = 'test@example.com';
```

**OR via System Users page:**

1. Go to Settings > System Users
2. Find the test user
3. Click "Deactivate" button
4. Confirm action

### Step 2: Try to Login

1. Go to login page
2. Enter the inactive user's email and password
3. Click "Sign in"

### Expected Result ✅

-   ❌ Login fails
-   🔴 Error message displays: **"Your account is inactive. Please contact admin."**
-   📄 User stays on login page
-   🎨 Error shows in red box above form

---

## Test Scenario 2: Active User Can Login

### Step 1: Activate the User

```sql
-- Via SQL
UPDATE users SET activity = 'Active' WHERE email = 'test@example.com';
```

**OR via System Users page:**

1. Go to Settings > System Users
2. Find the user
3. Click "Activate" button
4. Confirm action

### Step 2: Try to Login

1. Go to login page
2. Enter the user's email and password
3. Click "Sign in"

### Expected Result ✅

-   ✅ Login succeeds
-   🏠 Redirected to dashboard
-   👤 User is logged in

---

## Test Scenario 3: Wrong Password (Active User)

### Step 1: Try Wrong Password

1. Go to login page
2. Enter active user's email
3. Enter **wrong password**
4. Click "Sign in"

### Expected Result ✅

-   ❌ Login fails
-   🔴 Error message: **"These credentials do not match our records."**
-   📄 User stays on login page

---

## Test Scenario 4: Wrong Password (Inactive User)

### Step 1: Try Wrong Password

1. Go to login page
2. Enter inactive user's email
3. Enter **wrong password**
4. Click "Sign in"

### Expected Result ✅

-   ❌ Login fails
-   🔴 Error message: **"These credentials do not match our records."**
-   📄 User stays on login page
-   🔒 Does NOT reveal account is inactive (security)

---

## Quick SQL Commands

### Check User Status

```sql
SELECT id, name, email, activity FROM users WHERE email = 'test@example.com';
```

### Deactivate User

```sql
UPDATE users SET activity = 'Inactive' WHERE email = 'test@example.com';
```

### Activate User

```sql
UPDATE users SET activity = 'Active' WHERE email = 'test@example.com';
```

### List All Inactive Users

```sql
SELECT id, name, email, activity FROM users WHERE activity = 'Inactive';
```

### Count Active vs Inactive

```sql
SELECT activity, COUNT(*) as count FROM users GROUP BY activity;
```

---

## Visual Indicators

### Login Page Error (Inactive Account)

```
┌─────────────────────────────────────────┐
│  ⚠️  Your account is inactive.          │
│      Please contact admin.              │
└─────────────────────────────────────────┘

Email: [test@example.com]
Password: [••••••••]

[Forgot Password?]        [Sign in]
```

### System Users Page

```
Name          Email              Role    Status
────────────────────────────────────────────────
John Doe      john@example.com   Admin   🟢 Active
Jane Smith    jane@example.com   User    🔴 Inactive
```

---

## Admin Actions

### To Block a User

1. Settings > System Users
2. Find user
3. Click "Deactivate"
4. User cannot login anymore

### To Unblock a User

1. Settings > System Users
2. Find user
3. Click "Activate"
4. User can login again

---

## Troubleshooting

### User Can't Login - Check Status

```bash
php artisan tinker
>>> User::where('email', 'test@example.com')->first()->activity
=> "Inactive"  # This is why they can't login
```

### Reactivate User

```bash
php artisan tinker
>>> $user = User::where('email', 'test@example.com')->first();
>>> $user->activity = 'Active';
>>> $user->save();
=> true
```

### Clear Cache (if changes not working)

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Success Criteria

✅ Inactive users see clear error message  
✅ Active users can login normally  
✅ Wrong password shows generic error  
✅ Error displays above form  
✅ Dark mode works  
✅ Admin can toggle status

---

**Ready to test!** 🚀
