<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Tương lai sẽ query DB ở đây: 
        // $payments = Payment::with('invoice', 'room')->orderBy('created_at', 'desc')->get();
        
        return view('owner.payments.index');
    }
}