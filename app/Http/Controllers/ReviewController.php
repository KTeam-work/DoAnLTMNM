<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('tenant.reviews.index');
    }

    public function store(Request $request)
    {
        // Nhận dữ liệu form (rating, comment) và giả lập lưu thành công
        return redirect()->route('tenant.reviews.index')
                         ->with('success', 'Cảm ơn bạn! Đánh giá đã được hệ thống ghi nhận.');
    }
}