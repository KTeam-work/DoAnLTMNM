<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController; 
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Owner\InvoiceController as OwnerInvoiceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\MaintenanceController as OwnerMaintenanceController;
use App\Http\Controllers\Owner\OwnerTenantsController as OwnerTenantsController;
use App\Http\Controllers\OwnerContractController as OwnerContractController;
use App\Http\Controllers\Owner\OwnerServiceController as OwnerServiceController;
use App\Http\Controllers\Owner\OwnerUtilitiesController as OwnerUtilitiesController;

Route::get('/', function () {
    return view('Layout.landlord');
})->name('tenant.home');

Route::get('/tenant/invoices/index', [InvoiceController::class, 'index'])->name('tenant.invoices.index');







Route::get('/tenant/invoices/{id}', [InvoiceController::class, 'show'])->name('tenant.invoices.show');

// Phần hợp đồng của người dùng
Route::get('/tenant/contracts/index', [ContractController::class,'index'])->name('tenant.contracts.index');

Route::get('/tenant/contracts/{id}', [ContractController::class,'show'])->name('tenant.contracts.show');


// Quản lý hợp đồng dành cho chủ trọ 
Route::get('/owner/contracts/',[OwnerContractController::class,'index'])->name('owner.contracts.index');
Route::get('/owner/contracts/create', [OwnerContractController::class, 'create'])->name('owner.contracts.create');
Route::get('/owner/contracts/{id}', [OwnerContractController::class, 'show'])->name('owner.contracts.show');

// Quanr lý người thuê dành cho chủ trọ 
Route::get('/owner/tenants', [OwnerTenantsController::class,'manage'])->name('owner.tenants.manage');
Route::get('/owner/tenants/create', [OwnerTenantsController::class,'create'])->name('owner.tenants.create');
Route::get('/owner/tenants/edit', [OwnerTenantsController::class,'edit'])->name('owner.tenants.edit');
Route::get('/owner/tenants/{id}', [OwnerTenantsController::class,'show'])->name('owner.tenants.show');

// Quan ly dien nuoc cua chu tro
Route::get('/owner/utilities', [OwnerUtilitiesController::class,'index'])->name('owner.utilities.index');
Route::get('/owner/utilities/create', [OwnerUtilitiesController::class,'create'])->name('owner.utilities.create');

// Quản lý dịch vụ
Route::get('/owner/services', [OwnerServiceController::class,'index'])->name('owner.services.index');
Route::get('/owner/services/{id}', [OwnerServiceController::class,'edit'])->name('owner.services.edit');

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