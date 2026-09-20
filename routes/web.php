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
use App\Http\Controllers\Owner\ReviewController as OwnerReviewController;
use App\Http\Controllers\Owner\OwnerTenantsController as OwnerTenantsController;
use App\Http\Controllers\Owner\OwnerContractController;
use App\Http\Controllers\Owner\OwnerServiceController as OwnerServiceController;
use App\Http\Controllers\Owner\OwnerUtilitiesController as OwnerUtilitiesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\OwnerRoomController as OwnerRoomRoomController;
use App\Http\Controllers\Owner\OwnerRentailController as OwnerRentailController;
use App\Http\Controllers\Owner\OwnerApptionmentController as OwnerApptionmentController;   
use App\Http\Controllers\Owner\OwnerpropertiesController;

/*
|--------------------------------------------------------------------------
| AUTH & ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('layout.landlord');
})->name('home');

// Post


Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/users', function () {
    return view('admin.users');
});

Route::get('/admin/rental-posts', function () {
    return view('admin.rental-posts');
});

Route::get('/admin/statistics', function () {
    return view('admin.statistics');
});

/*
|--------------------------------------------------------------------------
| TENANT & PHÒNG
|--------------------------------------------------------------------------
*/
Route::get('/tenant/home', function () {
    return view('Layout.tenant');
})->name('tenant.home');

Route::redirect('/tenant', '/tenant/home')->name('tenant');

Route::get('/rooms', function () {
    return view('rooms.index');
})->name('rooms.index');

Route::get('/rooms/{id}', function ($id) {
    return view('rooms.show', compact('id'));
})->name('rooms.show');

Route::get('/favorites', function () {
    return view('favorites.index');
})->name('favorites.index');

Route::get('/appointments', function () {
    return view('appointments.index');
})->name('appointments.index');

Route::get('/appointments/create', function () {
    return view('appointments.create');
})->name('appointments.create');

Route::get('/tenant/invoices/index', [InvoiceController::class, 'index'])->name('tenant.invoices.index');
Route::get('/tenant/invoices/{id}', [InvoiceController::class, 'show'])->name('tenant.invoices.show');

// Hợp đồng Tenant
Route::get('/tenant/contracts', [ContractController::class,'index'])->name('tenant.contracts.index');
Route::get('/tenant/contracts/{id}', [ContractController::class,'show'])->name('tenant.contracts.show');

// Sửa chữa Tenant
Route::get('/tenant/maintenance', [MaintenanceController::class, 'index'])->name('tenant.maintenance.index');
Route::get('/tenant/maintenance/create', [MaintenanceController::class, 'create'])->name('tenant.maintenance.create');
Route::post('/tenant/maintenance/store', [MaintenanceController::class, 'store'])->name('tenant.maintenance.store');
Route::get('/tenant/maintenance/{id}', [MaintenanceController::class, 'show'])->name('tenant.maintenance.show');

// Đánh giá Tenant
Route::get('/tenant/reviews', [ReviewController::class, 'index'])->name('tenant.reviews.index');
Route::post('/tenant/reviews/store', [ReviewController::class, 'store'])->name('tenant.reviews.store');

// Thông báo Tenant
Route::get('/tenant/notifications', [NotificationController::class, 'index'])->name('tenant.notifications.index');
Route::post('/tenant/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])->name('tenant.notifications.mark_read');
Route::put('/tenant/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('tenant.notifications.read_all');
Route::put('/tenant/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('tenant.notifications.read');

// Thanh toán Tenant
Route::post('/tenant/payment', [\App\Http\Controllers\Tenant\PaymentController::class, 'store'])->name('tenant.payment.store');
Route::post('/tenant/payment/callback', [\App\Http\Controllers\Tenant\PaymentController::class, 'callback'])->name('tenant.payment.callback');

/*
|--------------------------------------------------------------------------
| OWNER (CHỦ TRỌ)
|--------------------------------------------------------------------------
*/
Route::get('/owner/contracts', [OwnerContractController::class, 'index'])->name('owner.contracts.index');
Route::get('/owner/contracts/create', [OwnerContractController::class, 'create'])->name('owner.contracts.create');
Route::post('/owner/contracts', [OwnerContractController::class, 'store'])->name('owner.contracts.store');
Route::get('/owner/contracts/{contract}', [OwnerContractController::class, 'show'])->name('owner.contracts.show');
Route::post('/owner/contracts/{contract}/members', [OwnerContractController::class, 'manageMembers'])->name('owner.contracts.members');
Route::put('/owner/contracts/{contract}/initial-utilities', [OwnerContractController::class, 'setInitialUtilities'])->name('owner.contracts.initial-utilities');
Route::put('/owner/contracts/{contract}/terminate', [OwnerContractController::class, 'terminate'])->name('owner.contracts.terminate');

Route::get('/owner/tenants', [OwnerTenantsController::class, 'index'])->name('owner.tenants.manage');
Route::get('/owner/tenants/create', [OwnerTenantsController::class, 'create'])->name('owner.tenants.create');
Route::get('/owner/tenants/edit', [OwnerTenantsController::class, 'edit'])->name('owner.tenants.edit');
Route::get('/owner/tenants/{id}', [OwnerTenantsController::class, 'show'])->name('owner.tenants.show');

Route::get('/owner/utilities', [OwnerUtilitiesController::class, 'index'])->name('owner.utilities.index');
Route::get('/owner/utilities/create', [OwnerUtilitiesController::class, 'create'])->name('owner.utilities.create');
Route::post('/owner/utilities', [OwnerUtilitiesController::class, 'store'])->name('owner.utilities.store');
Route::put('/owner/utilities/{utilityReading?}', [OwnerUtilitiesController::class, 'update'])->name('owner.utilities.update');

Route::get('/owner/services', [OwnerServiceController::class, 'index'])->name('owner.services.index');
Route::get('/owner/services/create', [OwnerServiceController::class, 'create'])->name('owner.services.create');
Route::get('/owner/services/{id}', [OwnerServiceController::class, 'edit'])->name('owner.services.edit');
Route::post('/owner/services', [OwnerServiceController::class, 'store'])->name('owner.services.store');
Route::put('/owner/services/{service?}', [OwnerServiceController::class, 'update'])->name('owner.services.update');
Route::delete('/owner/services/{service?}', [OwnerServiceController::class, 'destroy'])->name('owner.services.destroy');

Route::get('/owner/invoices', [OwnerInvoiceController::class, 'index'])->name('owner.invoices.index');
Route::post('/owner/invoices', [OwnerInvoiceController::class, 'store'])->name('owner.invoices.store');
Route::get('/owner/invoices/{id}', [OwnerInvoiceController::class, 'show'])->name('owner.invoices.show');
Route::get('/owner/payments', [OwnerPaymentController::class, 'index'])->name('owner.payments.index');
Route::put('/owner/payments/{id}/confirm', [OwnerPaymentController::class, 'confirm'])->name('owner.payments.confirm');
Route::put('/owner/payments/{id}/reject', [OwnerPaymentController::class, 'reject'])->name('owner.payments.reject');
Route::get('/owner/maintenance', [OwnerMaintenanceController::class, 'index'])->name('owner.maintenance.index');
Route::get('/owner/maintenance/{id}', [OwnerMaintenanceController::class, 'show'])->name('owner.maintenance.show');
Route::put('/owner/maintenance/{id}/status', [OwnerMaintenanceController::class, 'updateStatus'])->name('owner.maintenance.status');
Route::get('/owner/reviews', [OwnerReviewController::class, 'index'])->name('owner.reviews.index');
Route::put('/owner/reviews/{id}/visibility', [OwnerReviewController::class, 'toggleVisibility'])->name('owner.reviews.visibility');


Route::get('/owner/rooms', [OwnerRoomRoomController::class,'index'])->name('owner.rooms.index');
Route::get('/owner/rooms/create', [OwnerRoomRoomController::class, 'create'])->name('owner.rooms.create');
Route::post('/owner/rooms', [OwnerRoomRoomController::class, 'store'])->name('owner.rooms.store');

Route::get('/owner/rental-posts', [OwnerRentailController::class,'index'])->name('owner.rental-posts.index');

Route::get('/owner/appointments', [OwnerApptionmentController::class,'index'])->name('owner.appointments.index');

Route::get('/owner/properties', [OwnerpropertiesController::class,'index'])->name('owner.properties.index');

Route::get('/owner/home', function () {
    
    return view('Layout.landlord'); 
})->name('landlord.home'); 
