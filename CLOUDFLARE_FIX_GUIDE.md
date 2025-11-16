# 🛡️ Cloudflare 405 Error & Challenge Fix

## Problem

-   **405 Method Not Allowed** when submitting forms (transactions, inventory, etc.)
-   **Human verification challenge** appearing frequently
-   Frustrating user experience

## Root Causes

1. Cloudflare's Bot Fight Mode blocking legitimate requests
2. High security level treating POST requests as suspicious
3. Missing or incorrect CSRF tokens
4. Cloudflare WAF rules blocking form submissions

---

## Solution 1: Cloudflare Dashboard Settings (RECOMMENDED)

### Step 1: Disable Bot Fight Mode

1. Login to Cloudflare Dashboard
2. Select your domain: `nailville-salon-nalya.kenvies.com`
3. Go to **Security > Bots**
4. **Turn OFF** "Bot Fight Mode"
5. **Turn ON** "Super Bot Fight Mode" (if available)
6. Configure Super Bot Fight Mode:
    - Definitely automated: **Allow**
    - Likely automated: **Allow**
    - Verified bots: **Allow**

### Step 2: Adjust Security Level

1. Go to **Security > Settings**
2. Set **Security Level** to **Medium** (not High or I'm Under Attack)
3. Set **Challenge Passage** to **30 minutes**
4. **Disable** "Browser Integrity Check" (optional, if issues persist)

### Step 3: Create Firewall Rules

1. Go to **Security > WAF > Firewall rules**
2. Click **Create rule**

**Rule 1: Allow Your Domain**

```
Rule name: Allow Nailville Domain
Field: Hostname
Operator: equals
Value: nailville-salon-nalya.kenvies.com
Action: Allow
```

**Rule 2: Allow Form Submissions**

```
Rule name: Allow Form Submissions
Expression:
(http.host eq "nailville-salon-nalya.kenvies.com" and http.request.method in {"POST" "PUT" "DELETE"})
Action: Allow
```

**Rule 3: Allow Specific Endpoints**

```
Rule name: Allow Transaction Endpoints
Expression:
(http.request.uri.path contains "/transactions/store-records" or
 http.request.uri.path contains "/inventory/inventory-product-store" or
 http.request.uri.path contains "/settings/user-management/store-new-employer" or
 http.request.uri.path contains "/admin/users/store")
Action: Allow
```

### Step 4: Create Page Rules

1. Go to **Rules > Page Rules**
2. Click **Create Page Rule**

**Page Rule 1: Disable Security for Application**

```
URL: nailville-salon-nalya.kenvies.com/*
Settings:
- Security Level: Essentially Off
- Browser Integrity Check: Off
- Cache Level: Bypass (for dynamic content)
```

**Page Rule 2: Allow API Endpoints**

```
URL: nailville-salon-nalya.kenvies.com/*/store*
Settings:
- Security Level: Off
- Browser Integrity Check: Off
```

---

## Solution 2: Application-Level Fixes

### Fix 1: Ensure CSRF Tokens

**Check all forms have CSRF token:**

```blade
<form method="POST" action="{{ route('transactions.store') }}">
    @csrf  <!-- This is critical! -->
    <!-- form fields -->
</form>
```

**For AJAX requests:**

```javascript
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
```

### Fix 2: Add Cloudflare Headers to .htaccess

**File:** `public_html/.htaccess`

Add at the top:

```apache
<IfModule mod_setenvif.c>
    # Trust Cloudflare IPs
    SetEnvIf CF-Connecting-IP "^" IS_CLOUDFLARE=1
    SetEnvIf CF-Connecting-IP "^(.*)$" HTTP_CF_CONNECTING_IP=$1

    # Trust Cloudflare headers
    SetEnvIf CF-RAY "^" IS_CLOUDFLARE=1
</IfModule>

# Allow POST, PUT, DELETE methods
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Don't block POST/PUT/DELETE requests
    RewriteCond %{REQUEST_METHOD} ^(POST|PUT|DELETE)$
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]
</IfModule>
```

### Fix 3: Update Middleware

**File:** `app/Http/Middleware/TrustProxies.php`

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     */
    protected $proxies = '*'; // Trust all proxies (Cloudflare)

    /**
     * The headers that should be used to detect proxies.
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

---

## Solution 3: Cloudflare Development Mode

**Temporary fix for testing:**

1. Go to Cloudflare Dashboard
2. Click **Quick Actions**
3. Enable **Development Mode** (lasts 3 hours)
4. Test your application
5. If it works, the issue is Cloudflare settings

---

## Solution 4: Whitelist Your IP

**For admin testing:**

1. Go to **Security > WAF > Tools**
2. Click **IP Access Rules**
3. Add your IP address
4. Action: **Allow**
5. Note: "Whitelist"

---

## Verification Steps

### Test 1: Transaction Submission

1. Go to Transactions > Income
2. Click "Add Transaction"
3. Fill form and submit
4. **Expected**: Success message, no 405 error

### Test 2: Inventory Update

1. Go to Inventory > Manage Items
2. Edit an item
3. Save changes
4. **Expected**: Success message, no 405 error

### Test 3: User Management

1. Go to Settings > User Management
2. Add new employee
3. Submit form
4. **Expected**: Success message, no 405 error

---

## Monitoring

### Check Cloudflare Firewall Events

1. Go to **Security > Events**
2. Filter by:
    - Action: Block, Challenge
    - Host: your domain
3. Review blocked requests
4. Adjust rules accordingly

### Check Laravel Logs

```bash
cd ~/laravel
tail -f storage/logs/laravel.log
```

Look for:

-   CSRF token mismatch errors
-   405 Method Not Allowed
-   Authentication errors

---

## Common Issues & Solutions

### Issue: Still getting 405 errors

**Solution:**

1. Clear Cloudflare cache:
    - Go to Caching > Configuration
    - Click "Purge Everything"
2. Wait 5 minutes
3. Test again

### Issue: Challenge page still appearing

**Solution:**

1. Check Security Level is not "I'm Under Attack"
2. Disable Bot Fight Mode completely
3. Add page rule to bypass security

### Issue: AJAX requests failing

**Solution:**

```javascript
// Add to your AJAX calls
$.ajax({
    url: "/your-endpoint",
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        "X-Requested-With": "XMLHttpRequest",
    },
    data: yourData,
    success: function (response) {
        // handle success
    },
});
```

---

## Best Practices

### 1. Use Cloudflare Page Rules Wisely

-   Free plan: 3 page rules
-   Use them for critical paths (forms, API endpoints)

### 2. Monitor Security Events

-   Check weekly for false positives
-   Adjust rules based on legitimate traffic

### 3. Keep Security Balanced

-   Don't disable all security
-   Use "Medium" security level
-   Whitelist known good IPs

### 4. Test After Changes

-   Always test form submissions
-   Check different user roles
-   Test on different devices/networks

---

## Emergency Fix

**If nothing works, temporarily bypass Cloudflare:**

1. Go to Cloudflare Dashboard
2. Click **DNS**
3. Find your A record
4. Click the **orange cloud** (proxied)
5. It turns **gray** (DNS only)
6. Wait 5 minutes
7. Test application
8. If it works, issue is definitely Cloudflare
9. Re-enable proxy (orange cloud)
10. Apply fixes above

---

## Recommended Cloudflare Settings

```
Security Level: Medium
Challenge Passage: 30 minutes
Browser Integrity Check: Off (for application)
Bot Fight Mode: Off
Super Bot Fight Mode: On (Allow all)
WAF: Custom rules to allow your domain
Page Rules: Bypass security for form endpoints
```

---

## Support

**Cloudflare Community:**

-   https://community.cloudflare.com

**Cloudflare Docs:**

-   https://developers.cloudflare.com

---

**Status**: Ready to implement  
**Priority**: 🔴 Critical  
**Time**: 15-30 minutes
