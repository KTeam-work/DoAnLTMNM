<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TRANG CHỦ TENANT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('Layout.tenant');
})->name('tenant');


/*
|--------------------------------------------------------------------------
| PHÒNG
|--------------------------------------------------------------------------
*/

// Danh sách phòng
Route::get('/rooms', function () {
    return view('rooms.index');
})->name('rooms.index');

// Chi tiết phòng
Route::get('/rooms/{id}', function ($id) {
    return view('rooms.show', compact('id'));
})->name('rooms.show');


/*
|--------------------------------------------------------------------------
| YÊU THÍCH
|--------------------------------------------------------------------------
*/

Route::get('/favorites', function () {
    return view('favorites.index');
})->name('favorites.index');


/*
|--------------------------------------------------------------------------
| LỊCH XEM PHÒNG
|--------------------------------------------------------------------------
*/

// Danh sách lịch xem phòng
Route::get('/appointments', function () {
    return view('appointments.index');
})->name('appointments.index');

// Trang đặt lịch xem phòng
Route::get('/appointments/create', function () {
    return view('appointments.create');
})->name('appointments.create');