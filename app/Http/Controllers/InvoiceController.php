<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Trả về file giao diện nằm ở resources/views/tenant/invoices/index.blade.php
        return view('tenant.invoices.index');
    }

    public function show($id)
    {
        // Sau này có Database bạn sẽ truy vấn: $invoice = Invoice::find($id);
        // Tạm thời cứ trả về view giao diện trước
        return view('tenant.invoices.show');
    }
}