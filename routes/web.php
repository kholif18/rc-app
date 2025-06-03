<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderNoteController;

Route::middleware('auth')->group(function () {
    // Route untuk semua user yang terautentikasi
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('debts', DebtController::class);
    
    Route::resource('order', OrderController::class);
    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/notes', [OrderNoteController::class, 'store'])->name('orders.notes.store');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['create', 'store']);
    Route::get('/payments/detail', [PaymentController::class, 'detail'])->name('payments.detail');
    Route::get('/payments/paid', [PaymentController::class, 'index'])->name('payments.index');

    // Hanya untuk admin/superadmin
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });

        // Database Backup
        Route::prefix('admin/database')->name('backup.')->group(function() {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/export', [BackupController::class, 'export'])->name('export');
            Route::post('/import', [BackupController::class, 'import'])->name('import');
        });
    });
});

require __DIR__.'/auth.php';
