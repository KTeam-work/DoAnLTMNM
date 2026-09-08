<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller; // Bắt buộc phải use Controller gốc
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Sau này ở đây sẽ dùng Eloquent query:
        // $invoices = Invoice::with('room', 'tenant')->orderBy('created_at', 'desc')->get();
        // $totalRevenue = ...
        
        return view('owner.invoices.index');
    }
}