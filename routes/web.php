```php
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ContractController;

use App\Http\Controllers\Owner\InvoiceController as OwnerInvoiceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\MaintenanceController as OwnerMaintenanceController;
use App\Http\Controllers\Owner\ReviewController as OwnerReviewController;
use App\Http\Controllers\Owner\OwnerTenantsController;
use App\Http\Controllers\Owner\OwnerContractController;
use App\Http\Controllers\Owner\OwnerServiceController;
use App\Http\Controllers\Owner\OwnerUtilitiesController;
use App\Http\Controllers\Owner\OwnerRoomController as OwnerRoomRoomController;
use App\Http\Controllers\Owner\OwnerRentailController;
use App\Http\Controllers\Owner\OwnerApptionmentController;
use App\Http\Controllers\Owner\OwnerpropertiesController;


/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'owner') {
        return redirect()->route('landlord.dashboard');
    }

    if ($role === 'tenant') {
        return redirect()->route('tenant.dashboard');
    }

    Auth::logout();

    return redirect()
        ->route('login')
        ->withErrors([
            'email' => 'Tài khoản chưa được phân quyền hợp lệ.',
        ]);
});


/*
|--------------------------------------------------------------------------
| LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| TRANG SAU KHI ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dashboard');

    })->name('admin.dashboard');


    Route::get('/admin/users', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.users');

    })->name('admin.users');


    Route::get('/admin/rental-posts', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.rental-posts');

    })->name('admin.rental-posts');


    Route::get('/admin/statistics', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.statistics');

    })->name('admin.statistics');


    /*
    |--------------------------------------------------------------------------
    | CHỦ TRỌ
    |--------------------------------------------------------------------------
    */

    Route::get('/landlord/dashboard', function () {

        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        return view('Layout.landlord');

    })->name('landlord.dashboard');


    /*
    |--------------------------------------------------------------------------
    | NGƯỜI THUÊ
    |--------------------------------------------------------------------------
    */

    Route::get('/tenant/dashboard', function () {

        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        return view('Layout.tenant');

    })->name('tenant.dashboard');

});


/*
|--------------------------------------------------------------------------
| TENANT & PHÒNG
|--------------------------------------------------------------------------
*/

Route::get('/tenant/home', function () {
    return view('Layout.tenant');
})->name('tenant.home');

Route::redirect('/tenant', '/tenant/home')
    ->name('tenant');

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


/*
|--------------------------------------------------------------------------
| TENANT - HÓA ĐƠN
|--------------------------------------------------------------------------
*/

Route::get(
    '/tenant/invoices/index',
    [InvoiceController::class, 'index']
)->name('tenant.invoices.index');

Route::get(
    '/tenant/invoices/{id}',
    [InvoiceController::class, 'show']
)->name('tenant.invoices.show');


/*
|--------------------------------------------------------------------------
| TENANT - HỢP ĐỒNG
|--------------------------------------------------------------------------
*/

Route::get(
    '/tenant/contracts',
    [ContractController::class, 'index']
)->name('tenant.contracts.index');

Route::get(
    '/tenant/contracts/{id}',
    [ContractController::class, 'show']
)->name('tenant.contracts.show');


/*
|--------------------------------------------------------------------------
| TENANT - SỬA CHỮA
|--------------------------------------------------------------------------
*/

Route::get(
    '/tenant/maintenance',
    [MaintenanceController::class, 'index']
)->name('tenant.maintenance.index');

Route::get(
    '/tenant/maintenance/create',
    [MaintenanceController::class, 'create']
)->name('tenant.maintenance.create');

Route::post(
    '/tenant/maintenance/store',
    [MaintenanceController::class, 'store']
)->name('tenant.maintenance.store');

Route::get(
    '/tenant/maintenance/{id}',
    [MaintenanceController::class, 'show']
)->name('tenant.maintenance.show');


/*
|--------------------------------------------------------------------------
| TENANT - ĐÁNH GIÁ
|--------------------------------------------------------------------------
*/

Route::get(
    '/tenant/reviews',
    [ReviewController::class, 'index']
)->name('tenant.reviews.index');

Route::post(
    '/tenant/reviews/store',
    [ReviewController::class, 'store']
)->name('tenant.reviews.store');


/*
|--------------------------------------------------------------------------
| TENANT - THÔNG BÁO
|--------------------------------------------------------------------------
*/

Route::get(
    '/tenant/notifications',
    [NotificationController::class, 'index']
)->name('tenant.notifications.index');

Route::post(
    '/tenant/notifications/mark-read',
    [NotificationController::class, 'markAllAsRead']
)->name('tenant.notifications.mark_read');

Route::put(
    '/tenant/notifications/read-all',
    [NotificationController::class, 'markAllAsRead']
)->name('tenant.notifications.read_all');

Route::put(
    '/tenant/notifications/{id}/read',
    [NotificationController::class, 'markAsRead']
)->name('tenant.notifications.read');


/*
|--------------------------------------------------------------------------
| TENANT - THANH TOÁN
|--------------------------------------------------------------------------
*/

Route::post(
    '/tenant/payment',
    [\App\Http\Controllers\Tenant\PaymentController::class, 'store']
)->name('tenant.payment.store');

Route::post(
    '/tenant/payment/callback',
    [\App\Http\Controllers\Tenant\PaymentController::class, 'callback']
)->name('tenant.payment.callback');


/*
|--------------------------------------------------------------------------
| OWNER - CHỦ TRỌ
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/contracts',
    [OwnerContractController::class, 'index']
)->name('owner.contracts.index');

Route::get(
    '/owner/contracts/create',
    [OwnerContractController::class, 'create']
)->name('owner.contracts.create');

Route::post(
    '/owner/contracts',
    [OwnerContractController::class, 'store']
)->name('owner.contracts.store');

Route::get(
    '/owner/contracts/{contract}',
    [OwnerContractController::class, 'show']
)->name('owner.contracts.show');

Route::post(
    '/owner/contracts/{contract}/members',
    [OwnerContractController::class, 'manageMembers']
)->name('owner.contracts.members');

Route::put(
    '/owner/contracts/{contract}/initial-utilities',
    [OwnerContractController::class, 'setInitialUtilities']
)->name('owner.contracts.initial-utilities');

Route::put(
    '/owner/contracts/{contract}/terminate',
    [OwnerContractController::class, 'terminate']
)->name('owner.contracts.terminate');


/*
|--------------------------------------------------------------------------
| OWNER - NGƯỜI THUÊ
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/tenants',
    [OwnerTenantsController::class, 'index']
)->name('owner.tenants.manage');

Route::get(
    '/owner/tenants/create',
    [OwnerTenantsController::class, 'create']
)->name('owner.tenants.create');

Route::get(
    '/owner/tenants/edit',
    [OwnerTenantsController::class, 'edit']
)->name('owner.tenants.edit');

Route::get(
    '/owner/tenants/{id}',
    [OwnerTenantsController::class, 'show']
)->name('owner.tenants.show');


/*
|--------------------------------------------------------------------------
| OWNER - TIỆN ÍCH
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/utilities',
    [OwnerUtilitiesController::class, 'index']
)->name('owner.utilities.index');

Route::get(
    '/owner/utilities/create',
    [OwnerUtilitiesController::class, 'create']
)->name('owner.utilities.create');

Route::post(
    '/owner/utilities',
    [OwnerUtilitiesController::class, 'store']
)->name('owner.utilities.store');

Route::put(
    '/owner/utilities/{utilityReading?}',
    [OwnerUtilitiesController::class, 'update']
)->name('owner.utilities.update');


/*
|--------------------------------------------------------------------------
| OWNER - DỊCH VỤ
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/services',
    [OwnerServiceController::class, 'index']
)->name('owner.services.index');

Route::get(
    '/owner/services/create',
    [OwnerServiceController::class, 'create']
)->name('owner.services.create');

Route::get(
    '/owner/services/{id}',
    [OwnerServiceController::class, 'edit']
)->name('owner.services.edit');

Route::post(
    '/owner/services',
    [OwnerServiceController::class, 'store']
)->name('owner.services.store');

Route::put(
    '/owner/services/{service?}',
    [OwnerServiceController::class, 'update']
)->name('owner.services.update');

Route::delete(
    '/owner/services/{service?}',
    [OwnerServiceController::class, 'destroy']
)->name('owner.services.destroy');


/*
|--------------------------------------------------------------------------
| OWNER - HÓA ĐƠN / THANH TOÁN
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/invoices',
    [OwnerInvoiceController::class, 'index']
)->name('owner.
```
