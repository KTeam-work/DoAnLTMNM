<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('tenant.maintenance.index');
    }

    public function create()
    {
        return view('tenant.maintenance.create');
    }

    // Thêm hàm này:
    public function store(Request $request)
    {
        return redirect()->route('tenant.maintenance.index')
                         ->with('success', 'Đã gửi yêu cầu sửa chữa thành công. Ban quản lý sẽ xử lý sớm nhất!');
    }

    public function show($id)
    {
        
        return view('tenant.maintenance.show');
    }
}