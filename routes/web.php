<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController; 
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Owner\InvoiceController as OwnerInvoiceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\MaintenanceController as OwnerMaintenanceController;

Route::get('/', function () {
    return view('Layout.tenant');
})->name('tenant.home');

Route::get('/tenant/invoices/index', [InvoiceController::class, 'index'])->name('tenant.invoices.index');

Route::get('/tenant/invoices/{id}', [InvoiceController::class, 'show'])->name('tenant.invoices.show');

// Routes cho phần Sửa chữa
Route::get('/tenant/maintenance', [MaintenanceController::class, 'index'])->name('tenant.maintenance.index');
Route::get('/tenant/maintenance/create', [MaintenanceController::class, 'create'])->name('tenant.maintenance.create');
Route::post('/tenant/maintenance/store', [MaintenanceController::class, 'store'])->name('tenant.maintenance.store');
Route::get('/tenant/maintenance/{id}', [MaintenanceController::class, 'show'])->name('tenant.maintenance.show');


// Thêm 2 dòng này vào nhóm route của Tenant:
Route::get('/tenant/reviews', [ReviewController::class, 'index'])->name('tenant.reviews.index');
Route::post('/tenant/reviews/store', [ReviewController::class, 'store'])->name('tenant.reviews.store');




// Thêm vào nhóm Tenant:
Route::get('/tenant/notifications', [NotificationController::class, 'index'])->name('tenant.notifications.index');
Route::post('/tenant/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])->name('tenant.notifications.mark_read');



//khu vực Route dành cho Chủ trọ 
Route::get('/owner/invoices', [OwnerInvoiceController::class, 'index'])->name('owner.invoices.index');
Route::get('/owner/payments', [OwnerPaymentController::class, 'index'])->name('owner.payments.index');
Route::get('/owner/maintenance', [OwnerMaintenanceController::class, 'index'])->name('owner.maintenance.index');