<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderNoteController;
use App\Http\Controllers\BahanCetakController;
use App\Http\Controllers\ClientUploadController;
use App\Http\Controllers\MessageTemplateController;

Route::middleware('auth')->group(function () {
    // Route untuk semua user yang terautentikasi
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('users', UserController::class);

    Route::resource('customers', CustomerController::class);

    Route::resource('debts', DebtController::class);
    
    Route::resource('order', OrderController::class);
    Route::get('/order/{order_number}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/notes', [OrderNoteController::class, 'store'])->name('orders.notes.store');
    Route::post('/order/{order}/send-message', [OrderController::class, 'sendMessage'])->name('order.sendMessage');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['create', 'store']);
    Route::get('/payments/detail', [PaymentController::class, 'detail'])->name('payments.detail');
    Route::get('/payments/paid', [PaymentController::class, 'index'])->name('payments.index');

    Route::resource('bahan-cetak', BahanCetakController::class);

    Route::get('/files', [FilesController::class, 'index'])->name('files');
    Route::get('/files/json', [FilesController::class, 'getFilesJson'])->name('files.json');
    Route::post('/files/delete', [FilesController::class, 'deleteFile'])->name('files.delete');
    Route::delete('/files/delete-all', [FilesController::class, 'deleteAll'])->name('files.delete-all');
    Route::get('/files/download-all', [FilesController::class, 'downloadAll'])->name('files.download-all');
    Route::get('/files/download/{filename}', [FilesController::class, 'forceDownload'])->name('files.force-download');

    Route::resource('message-templates', MessageTemplateController::class);

    Route::get('/api', [ApiController::class, 'index'])->name('api.index');
    Route::post('/api/store', [ApiController::class, 'store'])->name('api.store');
    Route::post('/api/test-connection', [ApiController::class, 'testConnection'])->name('api.test-connection');

    // Hanya untuk admin/superadmin
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/debt', [ReportController::class, 'debtReport'])->name('debt');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/order', [ReportController::class, 'orderReport'])->name('order');
            Route::get('/order/export', [ReportController::class, 'exportReportOrder'])->name('order.export');
        });

        // Database Backup
        Route::prefix('admin/database')->name('backup.')->group(function() {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/export', [BackupController::class, 'export'])->name('export');
            Route::post('/import', [BackupController::class, 'import'])->name('import');
        });
    });
});

// Form upload file untuk client
Route::get('/client/upload', [ClientUploadController::class, 'showUploadForm'])->name('client.upload.form');
Route::post('/client/upload', [ClientUploadController::class, 'store'])->name('client.upload.store');


require __DIR__.'/auth.php';
