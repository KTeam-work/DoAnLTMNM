<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ViewingAppointment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Danh sách lịch xem phòng của người thuê
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để xem lịch hẹn.');
        }


        $appointments = ViewingAppointment::with(['room.property'])
            ->where('tenant_id', Auth::id())
            ->latest()
            ->get();

        $totalAppointments = $appointments->count();

        $pendingCount = $appointments->where('status', 'pending')->count();

        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        $completedCount = $appointments->where('status', 'completed')->count();

        // Lịch sắp tới: chỉ lấy lịch chưa diễn ra và chưa bị hủy/từ chối
        $upcomingAppointments = $appointments
            ->filter(function ($appointment) {
                if (!in_array($appointment->status, ['pending', 'confirmed'], true)) {
                    return false;
                }

                $appointmentDateTime = \Carbon\Carbon::parse(
                    $appointment->appointment_date->format('Y-m-d') . ' ' .
                        $appointment->appointment_time
                );

                return $appointmentDateTime->isFuture();
            })
            ->sortBy(function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d') . ' ' .
                    $appointment->appointment_time;
            })
            ->values();

        return view('appointments.index', compact(
            'appointments',
            'totalAppointments',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'upcomingAppointments'
        ));
    }
    // Mở trang tạo lịch xem phòng
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đặt lịch xem phòng.');
        }

        $rooms = Room::with('property')
            ->where('status', 'available')
            ->whereHas('rentalPosts', function ($query) {
                $query->where('status', 'published')
                    ->where(function ($query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            })
            ->get();

        // Hỗ trợ cả ?id=5 và ?room_id=5
        $selectedRoomId = $request->query('room_id')
            ?? $request->query('id');

        $selectedRoom = $selectedRoomId
            ? $rooms->firstWhere('id', (int) $selectedRoomId)
            : null;

        return view('appointments.create', compact(
            'rooms',
            'selectedRoom'
        ));
    }

    // Tạo lịch xem phòng
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đặt lịch xem phòng.');
        }

        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'phone' => ['required', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $room = Room::where('status', 'available')
            ->whereHas('rentalPosts', function ($query) {
                $query->where('status', 'published')
                    ->where(function ($query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            })
            ->find($validated['room_id']);

        if (!$room) {
            return back()->withInput()
                ->withErrors(['room_id' => 'Phòng hiện không còn được đăng cho thuê.']);
        }

        ViewingAppointment::create([
            'room_id' => $room->id,
            'tenant_id' => Auth::id(),
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Đặt lịch xem phòng thành công. Vui lòng chờ chủ trọ xác nhận.');
    }

    // Hủy lịch xem phòng của chính người thuê
    public function destroy(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để thực hiện thao tác.');
        }

        $appointment = ViewingAppointment::where('tenant_id', Auth::id())
            ->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'Lịch hẹn này không thể hủy.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Đã hủy lịch xem phòng.');
    }
}
