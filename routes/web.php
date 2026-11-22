
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\ForgotPasswordController;


use App\Http\Controllers\Owner\InvoiceController as OwnerInvoiceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\MaintenanceController as OwnerMaintenanceController;
use App\Http\Controllers\Owner\ReviewController as OwnerReviewController;
use App\Http\Controllers\Owner\OwnerTenantsController;
use App\Http\Controllers\Owner\OwnerContractController;
use App\Http\Controllers\Owner\OwnerServiceController;
use App\Http\Controllers\Owner\OwnerUtilitiesController;

use App\Http\Controllers\Owner\OwnerRentailController;
use App\Http\Controllers\Owner\OwnerApptionmentController;
use App\Http\Controllers\Owner\OwnerpropertiesController;
use App\Http\Controllers\Tenant\RoomController;
use App\Http\Controllers\Tenant\FavoriteController;
use App\Http\Controllers\Tenant\AppointmentController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerRoomController;
use App\Http\Controllers\Owner\OwnerRentalPostController;
use App\Http\Controllers\Owner\OwnerAmenityController;




/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
*/

Route::view('/map-test', 'tenant.map-test');

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

    Route::patch('/admin/users/{user}/status', function (
        \Illuminate\Http\Request $request,
        \App\Models\User $user
    ) {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình.');
        }

        /** @var \App\Services\ResendService $resendService */
        $resendService = app(\App\Services\ResendService::class);

        if ($user->status === 'active') {
            // KHÓA TÀI KHOẢN: Tự động sinh token mở khóa và gửi Gmail cho người dùng
            $unlockToken = \Illuminate\Support\Str::random(48);
            $user->update([
                'status' => 'inactive',
                'unlock_token' => $unlockToken,
                'unlock_requested_at' => null,
                'unlock_reason' => null,
            ]);

            try {
                if (!empty($user->email)) {
                    $unlockUrl = route('account.unlock.show', ['token' => $unlockToken]);
                    $resendService->sendAccountLockedEmail($user->email, $user->name, $unlockUrl);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email khóa tài khoản: ' . $e->getMessage());
            }

            return back()->with('success', "Đã khóa tài khoản {$user->name} thành công.");
        } else {
            // MỞ KHÓA TÀI KHOẢN: Khôi phục trạng thái active và gửi Gmail kích hoạt
            $user->update([
                'status' => 'active',
                'unlock_token' => null,
                'unlock_requested_at' => null,
                'unlock_reason' => null,
            ]);

            try {
                if (!empty($user->email)) {
                    $resendService->sendAccountUnlockedEmail($user->email, $user->name);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email mở khóa tài khoản: ' . $e->getMessage());
            }

            return back()->with('success', "Đã mở khóa tài khoản {$user->name} thành công.");
        }
    })->name('admin.users.update-status');

    // Route mở khóa tài khoản từ liên kết trong Gmail
    Route::get('/account/unlock/{token}', [\App\Http\Controllers\AccountUnlockController::class, 'show'])->name('account.unlock.show');
    Route::post('/account/unlock/{token}', [\App\Http\Controllers\AccountUnlockController::class, 'submit'])->name('account.unlock.submit');


    Route::get('/admin/rental-posts', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.rental-posts');
    })->name('admin.rental-posts');

    
Route::patch('/admin/rental-posts/{post}/status', function (
    \Illuminate\Http\Request $request,
    \App\Models\RentalPost $post
) {
    abort_unless($request->user()->role === 'admin', 403);

    $validated = $request->validate([
        'status' => ['required', \Illuminate\Validation\Rule::in([
            'draft', 'pending', 'published', 'rejected', 'hidden',
        ])],
        'rejection_reason' => ['nullable', 'string', 'max:500'],
    ]);

    if ($validated['status'] === 'rejected' &&
        trim($validated['rejection_reason'] ?? '') === '') {
        return back()
            ->withErrors(['rejection_reason' => 'Vui lòng nhập lý do từ chối.'])
            ->withInput();
    }

    $oldStatus = $post->status;
    $post->status = $validated['status'];

    if ($post->status === 'published') {
        $post->published_at ??= now();
        $post->rejection_reason = null;
    } elseif ($post->status === 'rejected') {
        $post->rejection_reason = trim($validated['rejection_reason']);
    } else {
        $post->rejection_reason = null;
    }

    $post->save();

    // Bắn thông báo cho chủ trọ khi trạng thái tin đăng thay đổi
    $ownerId = $post->room?->property?->owner_id;
    if ($ownerId && $oldStatus !== $post->status) {
        if ($post->status === 'rejected') {
            \App\Models\Notification::notify(
                userId: $ownerId,
                title: 'Tin đăng bị từ chối phê duyệt',
                message: 'Tin đăng "' . $post->title . '" đã bị từ chối. Lý do: ' . $post->rejection_reason,
                type: 'rental_post',
                referenceType: 'rental_post',
                referenceId: $post->id
            );
        } elseif ($post->status === 'published') {
            \App\Models\Notification::notify(
                userId: $ownerId,
                title: 'Tin đăng đã được duyệt',
                message: 'Tin đăng "' . $post->title . '" đã được phê duyệt và hiển thị công khai.',
                type: 'rental_post',
                referenceType: 'rental_post',
                referenceId: $post->id
            );
        }
    }

    return back()->with('success', 'Đã cập nhật trạng thái tin đăng.');
})->name('admin.rental-posts.update-status');


    Route::get('/admin/statistics', function () {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.statistics');
    })->name('admin.statistics');

    Route::get('/admin/reports', [\App\Http\Controllers\Admin\PostReportController::class, 'index'])
        ->name('admin.reports.index');
    Route::put('/admin/reports/{id}', [\App\Http\Controllers\Admin\PostReportController::class, 'update'])
        ->name('admin.reports.update');


    /*
    |--------------------------------------------------------------------------
    | CHỦ TRỌ
    |--------------------------------------------------------------------------
    */
    Route::get('/landlord/dashboard', [
        OwnerDashboardController::class,
        'index'
    ])
        ->middleware('auth')
        ->name('landlord.dashboard');


    /*
    |--------------------------------------------------------------------------
    | NGƯỜI THUÊ
    |--------------------------------------------------------------------------
    */

    Route::get('/tenant/dashboard', [
        TenantDashboardController::class,
        'index'
    ])->name('tenant.dashboard');
});


/*
|--------------------------------------------------------------------------
| TENANT & PHÒNG
|--------------------------------------------------------------------------
*/

Route::get('/tenant/home', function () {
    return redirect()->route('tenant.dashboard');
})->middleware('auth')->name('tenant.home');

Route::redirect('/tenant', '/tenant/home')
    ->name('tenant');

Route::get('/rooms', [RoomController::class, 'index'])
    ->name('rooms.index');

Route::get('/rooms/{id}', [RoomController::class, 'show'])
    ->name('rooms.show');

Route::post('/rental-posts/{post}/report', [\App\Http\Controllers\Tenant\PostReportController::class, 'store'])
    ->name('tenant.reports.store');

Route::get('/tenant/reports', [\App\Http\Controllers\Tenant\PostReportController::class, 'index'])
    ->middleware('auth')
    ->name('tenant.reports.index');


Route::get('/favorites', [FavoriteController::class, 'index'])
    ->name('favorites.index');

Route::post('/favorites', [FavoriteController::class, 'store'])
    ->name('favorites.store');

Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])
    ->name('favorites.destroy');

Route::get('/appointments', [AppointmentController::class, 'index'])
    ->name('appointments.index');

Route::get('/appointments/create', [AppointmentController::class, 'create'])
    ->name('appointments.create');

Route::post('/appointments', [AppointmentController::class, 'store'])
    ->name('appointments.store');

Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])
    ->name('appointments.destroy');
Route::put('/appointments/{id}/accept-reschedule', [AppointmentController::class, 'acceptReschedule'])
    ->name('appointments.accept-reschedule');


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

Route::get(
    '/tenant/invoices/{id}/print',
    [InvoiceController::class, 'print']
)->name('tenant.invoices.print');


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
    '/tenant/invoices/{id}/pay-qr',
    [\App\Http\Controllers\Tenant\PaymentController::class, 'createOrGetQrPayment']
)->name('tenant.invoices.pay_qr');

Route::get(
    '/tenant/invoices/{id}/payment-status',
    [\App\Http\Controllers\Tenant\PaymentController::class, 'checkPaymentStatus']
)->name('tenant.invoices.payment_status');

Route::post(
    '/tenant/invoices/{id}/simulate-sepay',
    [\App\Http\Controllers\Tenant\PaymentController::class, 'simulateSepayWebhook']
)->name('tenant.invoices.simulate_sepay');

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

Route::post(
    '/owner/contracts/{contract}/activate',
    [OwnerContractController::class, 'activate']
)->name('owner.contracts.activate');

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
    '/owner/tenants/{id}/edit',
    [OwnerTenantsController::class, 'edit']
)->name('owner.tenants.edit');

Route::put('/owner/tenants/{id}', [OwnerTenantsController::class, 'update'])
    ->name('owner.tenants.update')
    ->whereNumber('id');

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
    '/owner/utilities/previous-reading',
    [OwnerUtilitiesController::class, 'getPreviousReading']
)->name('owner.utilities.previous-reading');

Route::post(
    '/owner/utilities/scan-meter',
    [OwnerUtilitiesController::class, 'scanMeter']
)->name('owner.utilities.scan-meter');

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

Route::delete(
    '/owner/utilities/{utilityReading}',
    [OwnerUtilitiesController::class, 'destroy']
)->name('owner.utilities.destroy');


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
| OWNER - TIỆN ÍCH PHÒNG TRỌ (AMENITIES)
|--------------------------------------------------------------------------
*/
Route::get('/owner/amenities', [OwnerAmenityController::class, 'index'])->name('owner.amenities.index');
Route::post('/owner/amenities', [OwnerAmenityController::class, 'store'])->name('owner.amenities.store');
Route::put('/owner/amenities/{amenity}', [OwnerAmenityController::class, 'update'])->name('owner.amenities.update');
Route::delete('/owner/amenities/{amenity}', [OwnerAmenityController::class, 'destroy'])->name('owner.amenities.destroy');


/*
|--------------------------------------------------------------------------
| OWNER - HÓA ĐƠN / THANH TOÁN
|--------------------------------------------------------------------------
*/
Route::get(
    '/owner/invoices',
    [OwnerInvoiceController::class, 'index']
)->name('owner.invoices.index');

Route::get(
    '/owner/invoices/{invoice}',
    [OwnerInvoiceController::class, 'show']
)->name('owner.invoices.show');

Route::get(
    '/owner/invoices/{invoice}/print',
    [OwnerInvoiceController::class, 'print']
)->name('owner.invoices.print');

Route::post(
    '/owner/invoices',
    [OwnerInvoiceController::class, 'store']
)->name('owner.invoices.store');

Route::post(
    '/owner/invoices/process-due-now',
    [OwnerInvoiceController::class, 'processDueNow']
)->name('owner.invoices.process-due-now');


/*
|--------------------------------------------------------------------------
| OWNER - THANH TOÁN
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/payments',
    [OwnerPaymentController::class, 'index']
)->name('owner.payments.index');

Route::put(
    '/owner/payments/{payment}/confirm',
    [OwnerPaymentController::class, 'confirm']
)->name('owner.payments.confirm');

Route::put(
    '/owner/payments/{payment}/reject',
    [OwnerPaymentController::class, 'reject']
)->name('owner.payments.reject');

Route::post(
    '/owner/invoices/{invoice}/record-cash',
    [OwnerPaymentController::class, 'recordCashPayment']
)->name('owner.invoices.record-cash');


/*
|--------------------------------------------------------------------------
| OWNER - BẢO TRÌ
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/maintenance',
    [OwnerMaintenanceController::class, 'index']
)->name('owner.maintenance.index');

Route::get(
    '/owner/maintenance/{maintenance}',
    [OwnerMaintenanceController::class, 'show']
)->name('owner.maintenance.show');

Route::put(
    '/owner/maintenance/{maintenance}/status',
    [OwnerMaintenanceController::class, 'updateStatus']
)->name('owner.maintenance.status');


/*
|--------------------------------------------------------------------------
| OWNER - ĐÁNH GIÁ
|--------------------------------------------------------------------------
*/

Route::get(
    '/owner/reviews',
    [OwnerReviewController::class, 'index']
)->name('owner.reviews.index');

Route::put(
    '/owner/reviews/{review}/visibility',
    [OwnerReviewController::class, 'toggleVisibility']
)->name('owner.reviews.visibility');


/*
|--------------------------------------------------------------------------
| OWNER - PHÒNG TRỌ
|--------------------------------------------------------------------------
*/

Route::resource(
    'owner/rooms',
    OwnerRoomController::class
)->names('owner.rooms');



Route::resource(
    'owner/appointments',
    OwnerApptionmentController::class
)->names('owner.appointments');

Route::put(
    '/owner/appointments/{appointment}/confirm',
    [OwnerApptionmentController::class, 'confirm']
)->name('owner.appointments.confirm');

Route::put(
    '/owner/appointments/{appointment}/reject',
    [OwnerApptionmentController::class, 'reject']
)->name('owner.appointments.reject');

Route::put(
    '/owner/appointments/{appointment}/reschedule',
    [OwnerApptionmentController::class, 'reschedule']
)->name('owner.appointments.reschedule');



Route::resource(
    'owner/properties',
    OwnerpropertiesController::class
)->names('owner.properties');

Route::post(
    'owner/rental-posts/{rental_post}/resubmit',
    [OwnerRentalPostController::class, 'resubmit']
)->name('owner.rental-posts.resubmit')->middleware('auth');

Route::resource(
    'owner/rental-posts',
    OwnerRentalPostController::class
)->names('owner.rental-posts');

/*
|--------------------------------------------------------------------------
| CHỦ TRỌ - THÔNG BÁO
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/owner/notifications', [NotificationController::class, 'ownerIndex'])
        ->name('owner.notifications.index');
    Route::post('/owner/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])
        ->name('owner.notifications.mark_read');
    Route::match(['post', 'put'], '/owner/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('owner.notifications.read');
});

// Chat box AI Trợ Ơi (Hỗ trợ cả khách chưa đăng nhập, người thuê, chủ trọ và admin)
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->middleware(['throttle:30,1'])->name('chatbot.send');

// QR 
Route::post('/sepay/webhook', [PaymentController::class, 'webhook']);


// Quên mật khẩu
Route::get('/forgot-password', [
    ForgotPasswordController::class,
    'showForgotForm'
])->middleware('guest')->name('password.request');

// Gửi link đặt lại mật khẩu
Route::post('/forgot-password', [
    ForgotPasswordController::class,
    'sendResetLink'
])->middleware('guest')->name('password.email');

// Hiển thị form đặt lại mật khẩu
Route::get('/reset-password/{token}', [
    ForgotPasswordController::class,
    'showResetForm'
])->middleware('guest')->name('password.reset');

// Cập nhật mật khẩu mới
Route::post('/reset-password', [
    ForgotPasswordController::class,
    'resetPassword'
])->middleware('guest')->name('password.update');

// Fallback phục vụ file/ảnh từ storage/app/public nếu môi trường Windows chưa tạo được symlink public/storage
Route::get('/storage/{path}', function (string $path) {
    $storagePublic = realpath(storage_path('app/public'));
    $targetFile = realpath(storage_path('app/public/' . $path));

    if (!$storagePublic || !$targetFile || !str_starts_with($targetFile, $storagePublic) || !file_exists($targetFile)) {
        abort(404);
    }

    $mime = mime_content_type($targetFile) ?: 'image/jpeg';
    return response(file_get_contents($targetFile), 200, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')
  ->withoutMiddleware([
      \Illuminate\Session\Middleware\StartSession::class,
      \Illuminate\View\Middleware\ShareErrorsFromSession::class,
      \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
  ])
  ->name('storage.local_fallback');
