<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ViewingAppointment;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $ownerId = Auth::id();

        // Chỉ cho phép chủ trọ truy cập dashboard
        abort_unless(Auth::user()->role === 'owner', 403);

        // Danh sách ID nhà trọ thuộc chủ trọ đang đăng nhập
        $propertyIds = Auth::user()
            ->properties()
            ->select('id');

        // Tổng số phòng thuộc chủ trọ
        $totalRooms = Room::whereIn('property_id', $propertyIds)
            ->count();

        // Số phòng đã thuê
        $rentedRooms = Room::whereIn('property_id', $propertyIds)
            ->where('status', 'rented')
            ->count();

        // Số phòng còn trống
        $availableRooms = Room::whereIn('property_id', $propertyIds)
            ->where('status', 'available')
            ->count();

        // Doanh thu tháng hiện tại:
        // Chỉ tính giao dịch thành công, thuộc hợp đồng của chủ trọ.
        $monthlyRevenue = Payment::where('status', 'success')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->whereHas('invoice.contract', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->sum('amount');

        // Danh sách phòng và hợp đồng đang hoạt động
        $rooms = Room::with([
            'property',
            'contracts' => function ($query) {
                $query->where('status', 'active')
                    ->with('tenant')
                    ->latest('id');
            },
        ])
            ->whereIn('property_id', $propertyIds)
            ->latest('id')
            ->take(10)
            ->get();

        // Lịch hẹn xem phòng gần đây của chủ trọ
        $appointments = ViewingAppointment::with([
            'tenant',
            'room.property',
        ])
            ->whereHas('room.property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->latest('id')
            ->take(5)
            ->get();

       return view('owner.dashboard', compact(
            'totalRooms',
            'rentedRooms',
            'availableRooms',
            'monthlyRevenue',
            'rooms',
            'appointments'
        ));
    }
}
