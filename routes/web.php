<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController; // <-- Đưa lên trên cùng ở đây

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