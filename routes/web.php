<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

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