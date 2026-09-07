<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('Layout.tenant');
})->name('tenant.home');

Route::get('/tenant/invoices/index', [InvoiceController::class, 'index']);

Route::get('/tenant/invoices/{id}', [InvoiceController::class, 'show'])->name('tenant.invoices.show');