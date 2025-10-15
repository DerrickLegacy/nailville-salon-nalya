<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\InventoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-dashboard-income', [DataFeedController::class, 'getDashboardIncome'])->name('json_dashboard-income');
    Route::get('/json-dashboard-expense', [DataFeedController::class, 'getDashboardExpense'])->name('json_dashboard-expense');
    Route::get('/json-dashboard-net', [DataFeedController::class, 'getDashboardNet'])->name('json_dashboard-net');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');
    Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');

    // Employer
    Route::get('/employees/status-chart', [EmployeeController::class, 'statusChart'])->name('employees.status.chart');

    // Stock controller
    Route::get('/stock-alerts', [StockAlertController::class, 'index'])->name('stock.list');
    Route::get('/item-details/{id}', [StockAlertController::class, 'getItemdetails'])->name('stock.item.details');

    // Charts
    Route::get('/chart-data', [ChartController::class, 'chartData'])->name('chart.data');
    Route::get('/chart-top-employers', [ChartController::class, 'topEmployers'])->name('chart.top.employers');
    Route::get('/chart-income-vs-expenses', [ChartController::class, 'monthlyTransactionsChart'])->name('chart.income.expenses');
    Route::get('/chart-record-count', [ChartController::class, 'dailyTransactionsChart'])->name('chart.record.count');

    // Configurations Settings
    Route::prefix('configurations')->group(function () {
        Route::get('/business-goals-settings', [SettingController::class, 'configSettings'])->name('configurations.settings');
        Route::get('/business-goals-settings-list', [SettingController::class, 'fetchSettings'])->name('configurations.fetch');
        Route::post('/store-business-goals-item', [SettingController::class, 'storeConfigurations'])->name('configurations.store');
        Route::put('/update/business-goals/{id}', [SettingController::class, 'updateConfigurations'])->name('configurations.update');
        Route::delete('/delete/business-goal/{id}', [SettingController::class, 'destroyConfigurations'])->name('configurations.destroy.goal');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/income', [ReportController::class, 'income'])->name('reports.income');
        Route::get('/expense', [ReportController::class, 'expense'])->name('reports.expense');
        Route::get('/goals', [ReportController::class, 'goals'])->name('reports.goals');
        Route::get('/profit', [ReportController::class, 'profit'])->name('reports.profit');
        Route::get('/data/ajax-fetch', [ReportController::class, 'ajax_data'])->name('reports.data');
        Route::get('/employer-performance', [ReportController::class, 'EmployerContribution'])->name('reports.employer.performance');
    });

    // Inventory
    Route::prefix('inventory')->group(function () {
        Route::get('/inventory-manage-items', [InventoryController::class, 'manageInventory'])->name('inventory.manage');
        Route::get('/inventory-list-items', [InventoryController::class, 'list'])->name('inventory.list');
        Route::get('/inventory-price-changes', [InventoryController::class, 'inventory'])->name('inventory.price.changes');
        Route::POST('/inventory-product-store', [InventoryController::class, 'store'])->name('inventory.product.store');
        Route::put('/inventory-product/{id}', [InventoryController::class, 'update'])
            ->name('inventory.product.update');
        Route::delete('/inventory-product/{id}', [InventoryController::class, 'destroy'])
            ->name('inventory.product.destroy');
    });

    //    transactions controller
    Route::get('/finance/transactions', [TransactionController::class, 'index01'])->name('transactions');
    Route::get('/transactions/get-records', [TransactionController::class, 'getRecords'])->name('transactions.getRecords');

    Route::get('/transactions/income', [TransactionController::class, 'index'])
        ->name('transactions.income')
        ->defaults('transaction_type', 'Income');

    Route::get('/transactions/expense', [TransactionController::class, 'index'])
        ->name('transactions.expense')
        ->defaults('transaction_type', 'Expense');

    Route::post('/transactions/store-records', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/delete-record/{id}', [TransactionController::class, 'delete'])
        ->name('transactions.delete');
    Route::get('/transactions/records-details/{id}', [TransactionController::class, 'show'])
        ->name('transactions.details');
    Route::get('/transactions/edit-record/{id}', [TransactionController::class, 'edit'])
        ->name('transactions.edit');
    Route::put('/transactions/update-record/{id}', [TransactionController::class, 'update'])
        ->name('transactions.update');

    // settings
    Route::get('/settings/user-management', [SettingController::class, 'index'])
        ->name('settings.management');
    Route::get('/settings/user-management/list', [SettingController::class, 'getList'])
        ->name('settings.list');
    Route::get('/settings/user-management/create-employee', [SettingController::class, 'createEmployee'])
        ->name('settings.create.employee');
    Route::get('/settings/user-management/employee-details/{id}', [SettingController::class, 'viewEditEmployerDetails'])
        ->name('settings.employee.details');
    Route::get('/settings/user-management/edit-employer/{id}', [SettingController::class, 'viewEditEmployerDetails'])
        ->name('settings.edit.employer')->defaults('pageType', 'edit');

    Route::get('/settings/user-management/delete-employer/{id}', [SettingController::class, 'deleteEmployee'])
        ->name('settings.delete.employer');
    Route::put('/settings/user-management/update-employer/{id}', [SettingController::class, 'updateEmployee'])
        ->name('settings.update.employer');
    Route::get('/settings/user-management/create-new-employer/', [SettingController::class, 'createNewEmployee'])
        ->name('settings.create.employer');
    Route::post('/settings/user-management/store-new-employer/', [SettingController::class, 'storeNewEmployee'])
        ->name('settings.store.employer');
    Route::post('/user/profile')
        ->name('settings.my.account');
});
