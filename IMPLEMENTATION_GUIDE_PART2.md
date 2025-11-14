# Comprehensive Improvements Implementation Guide - Part 2

## 3. DASHBOARD MISSING INSIGHTS

### New Visualizations to Add:

#### A. Customer Retention Rate Chart
Shows repeat vs new customers

#### B. Average Transaction Value Trend
Track if customers are spending more/less over time

#### C. Peak Hours Heatmap
Show busiest times of day/week

#### D. Service Category Performance
Which services generate most revenue

#### E. Payment Method Distribution
Cash vs Mobile Money vs Card usage

#### F. Expense Category Breakdown
Where money is being spent

### Implementation:

Add to `app/Http/Controllers/DashboardController.php`:

```php
public function index()
{
    // ... existing code ...
    
    // NEW: Average transaction value trend (last 30 days)
    $avgTransactionTrend = Transaction::where('transaction_type', 'Income')
        ->where('date', '>=', Carbon::now()->subDays(30))
        ->selectRaw('DATE(date) as day, AVG(amount) as avg_amount')
        ->groupBy('day')
        ->orderBy('day')
        ->get();
    
    // NEW: Service category performance
    $servicePerformance = Transaction::where('transaction_type', 'Income')
        ->whereMonth('date', Carbon::now()->month)
        ->selectRaw('service_description, COUNT(*) as count, SUM(amount) as total')
        ->groupBy('service_description')
        ->orderByDesc('total')
        ->limit(10)
        ->get();
    
    // NEW: Payment method distribution
    $paymentMethods = Transaction::where('transaction_type', 'Income')
        ->whereMonth('date', Carbon::now()->month)
        ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
        ->groupBy('payment_method')
        ->get();
    
    // NEW: Expense breakdown
    $expenseBreakdown = Transaction::where('transaction_type', 'Expense')
        ->whereMonth('date', Carbon::now()->month)
        ->selectRaw('service_description as category, SUM(amount) as total')
        ->groupBy('category')
        ->orderByDesc('total')
        ->get();
    
    // NEW: Peak hours analysis
    $peakHours = Transaction::where('transaction_type', 'Income')
        ->whereMonth('date', Carbon::now()->month)
        ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();
    
    return view('pages.dashboard.dashboard', compact(
        // ... existing variables ...
        'avgTransactionTrend',
        'servicePerformance',
        'paymentMethods',
        'expenseBreakdown',
        'peakHours'
    ));
}
```

### Add to Dashboard View:

Add these sections to `resources/views/pages/dashboard/dashboard.blade.php`:

```blade
{{-- Average Transaction Value Trend --}}
<div class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Average Transaction Value Trend (Last 30 Days)
    </h2>
    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
        <canvas id="avgTransactionChart" height="80"></canvas>
    </div>
</div>

{{-- Service Performance --}}
<div class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Top Performing Services This Month
    </h2>
    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
        <canvas id="servicePerformanceChart" height="80"></canvas>
    </div>
</div>

{{-- Payment Methods Distribution --}}
<div class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Payment Methods Distribution
    </h2>
    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
        <canvas id="paymentMethodsChart" height="80"></canvas>
    </div>
</div>

{{-- Expense Breakdown --}}
<div class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Expense Breakdown This Month
    </h2>
    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
        <canvas id="expenseBreakdownChart" height="80"></canvas>
    </div>
</div>

{{-- Peak Hours Heatmap --}}
<div class="space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Busiest Hours of the Day
    </h2>
    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
        <canvas id="peakHoursChart" height="80"></canvas>
    </div>
</div>

<script>
// Average Transaction Value Trend
const avgTrendData = @json($avgTransactionTrend);
new Chart(document.getElementById('avgTransactionChart'), {
    type: 'line',
    data: {
        labels: avgTrendData.map(d => d.day),
        datasets: [{
            label: 'Average Transaction Value',
            data: avgTrendData.map(d => d.avg_amount),
            borderColor: '#8470FF',
            backgroundColor: 'rgba(132, 112, 255, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    }
});

// Service Performance
const serviceData = @json($servicePerformance);
new Chart(document.getElementById('servicePerformanceChart'), {
    type: 'bar',
    data: {
        labels: serviceData.map(s => s.service_description),
        datasets: [{
            label: 'Revenue',
            data: serviceData.map(s => s.total),
            backgroundColor: '#10b981'
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y'
    }
});

// Payment Methods
const paymentData = @json($paymentMethods);
new Chart(document.getElementById('paymentMethodsChart'), {
    type: 'doughnut',
    data: {
        labels: paymentData.map(p => p.payment_method),
        datasets: [{
            data: paymentData.map(p => p.total),
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
        }]
    }
});

// Expense Breakdown
const expenseData = @json($expenseBreakdown);
new Chart(document.getElementById('expenseBreakdownChart'), {
    type: 'pie',
    data: {
        labels: expenseData.map(e => e.category),
        datasets: [{
            data: expenseData.map(e => e.total),
            backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899']
        }]
    }
});

// Peak Hours
const peakData = @json($peakHours);
new Chart(document.getElementById('peakHoursChart'), {
    type: 'bar',
    data: {
        labels: peakData.map(h => h.hour + ':00'),
        datasets: [{
            label: 'Transactions',
            data: peakData.map(h => h.count),
            backgroundColor: '#8470FF'
        }]
    }
});
</script>
```

---

## 4. PROFILE IMAGE UPLOAD FIX

### Issue: Images not persisting after upload

### Solution:

1. **Update User Model** - Add profile_photo_path to fillable

File: `app/Models/User.php`

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'profile_photo_path', // Add this
];
```

2. **Create Profile Controller** (if not exists)

File: `app/Http/Controllers/ProfileController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Update user
        $user->update([
            'profile_photo_path' => $path,
        ]);

        return back()->with('success', 'Profile photo updated successfully!');
    }
}
```

3. **Update .env** - Ensure filesystem is configured

```env
FILESYSTEM_DISK=public
```

4. **Create symbolic link** (run once)

```bash
php artisan storage:link
```

5. **Update Profile Form**

File: `resources/views/profile/update-profile-information-form.blade.php`

```blade
<form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="flex items-center space-x-4">
        <img src="{{ auth()->user()->profile_photo_url }}" 
             alt="Profile Photo" 
             class="w-20 h-20 rounded-full object-cover">
        
        <input type="file" name="photo" accept="image/*" 
               class="block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-violet-50 file:text-violet-700
                      hover:file:bg-violet-100">
    </div>
    
    <button type="submit" class="mt-4 btn btn-primary">
        Update Photo
    </button>
</form>
```

6. **Add Route**

File: `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    Route::put('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])
        ->name('profile.photo.update');
});
```

---

## 5. TWO-FACTOR AUTHENTICATION FIX

### Issue: 2FA not working

### Solution:

1. **Ensure Fortify is configured**

File: `config/fortify.php`

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    // Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

2. **Install required package** (if not installed)

```bash
composer require bacon/bacon-qr-code
```

3. **Update Two-Factor Form**

File: `resources/views/profile/two-factor-authentication-form.blade.php`

Ensure it has proper Livewire bindings and QR code display.

4. **Test 2FA Setup**:
   - Enable 2FA in profile
   - Scan QR code with authenticator app (Google Authenticator, Authy)
   - Enter 6-digit code to confirm
   - Save recovery codes

---

Continue to IMPLEMENTATION_GUIDE_PART3.md for sidebar and UI improvements...
