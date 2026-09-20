<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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