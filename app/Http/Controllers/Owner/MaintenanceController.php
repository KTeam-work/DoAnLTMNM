<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Sau này bạn sẽ query DB lấy danh sách yêu cầu sửa chữa ở đây
        // $maintenances = Maintenance::with('room', 'tenant')->orderBy('created_at', 'desc')->get();
        
        return view('owner.maintenance.index');
    }
}