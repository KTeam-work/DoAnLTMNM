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
        // Khi có Database, bạn sẽ dùng Eloquent để lưu dữ liệu ở đây
        
        // Giả lập lưu thành công và đẩy người dùng về trang danh sách kèm thông báo
        return redirect()->route('tenant.maintenance.index')
                         ->with('success', 'Đã gửi yêu cầu sửa chữa thành công. Ban quản lý sẽ xử lý sớm nhất!');
    }

    public function show($id)
    {
        // $id là tham số để sau này truy vấn Database lấy đúng Ticket
        // Tạm thời trả về view giả lập
        return view('tenant.maintenance.show');
    }
}