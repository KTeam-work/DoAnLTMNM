<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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

        $pendingCount = $appointments->whereIn('status', ['pending', 'rescheduled'])->count();

        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        $completedCount = $appointments->where('status', 'completed')->count();

        // Lịch sắp tới: chỉ lấy lịch chưa diễn ra và chưa bị hủy/từ chối
        $upcomingAppointments = $appointments
            ->filter(function ($appointment) {
                if (!in_array($appointment->status, ['pending', 'confirmed', 'rescheduled'], true)) {
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

        $appointment = ViewingAppointment::create([
            'room_id' => $room->id,
            'tenant_id' => Auth::id(),
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        $formattedDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        $timeStr = substr((string) $appointment->appointment_time, 0, 5);
        $roomName = $room->name ?? 'phòng trọ';
        $user = Auth::user();

        // 1. Gửi thông báo cho người thuê xác nhận đã gửi lịch hẹn thành công
        Notification::notify(
            $user->id,
            'Đặt lịch xem phòng thành công',
            "Bạn đã gửi yêu cầu hẹn xem phòng \"{$roomName}\" vào lúc {$timeStr} ngày {$formattedDate}. Vui lòng chờ chủ trọ xác nhận.",
            'appointment',
            'appointment',
            $appointment->id
        );

        // 2. Gửi thông báo cho chủ trọ có khách đặt lịch xem phòng
        $owner = $room->property?->owner;
        if ($owner) {
            Notification::notify(
                $owner->id,
                'Có yêu cầu đặt lịch xem phòng mới',
                "Khách thuê {$user->name} đã đặt lịch xem phòng \"{$roomName}\" vào lúc {$timeStr} ngày {$formattedDate}. Vui lòng kiểm tra và xác nhận.",
                'appointment',
                'appointment',
                $appointment->id
            );
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Đặt lịch xem phòng thành công. Vui lòng chờ chủ trọ xác nhận.');
    }

    // Hủy hoặc từ chối lịch xem phòng của chính người thuê
    public function destroy(Request $request, int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để thực hiện thao tác.');
        }

        $appointment = ViewingAppointment::with(['room.property.owner', 'tenant'])
            ->where('tenant_id', Auth::id())
            ->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed', 'rescheduled'], true)) {
            return back()->with('error', 'Lịch hẹn này không thể hủy.');
        }

        $isRescheduled = $appointment->status === 'rescheduled';
        $cancelReason = trim($request->input('cancel_reason', ''));

        $updateData = [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ];
        if ($cancelReason !== '') {
            $updateData['owner_note'] = 'Khách từ chối: ' . $cancelReason;
        }

        $appointment->update($updateData);

        $formattedDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        $timeStr = substr((string) $appointment->appointment_time, 0, 5);
        $roomName = $appointment->room?->name ?? 'phòng trọ';
        $tenantName = $appointment->tenant?->name ?? 'Khách thuê';

        // 1. Gửi thông báo cho chủ trọ
        $owner = $appointment->room?->property?->owner;
        if ($owner) {
            $reasonDesc = $cancelReason !== '' ? " Lý do: \"{$cancelReason}\"." : '';
            $ownerTitle = $isRescheduled
                ? 'Khách thuê từ chối giờ hẹn đề xuất'
                : 'Khách thuê đã hủy lịch xem phòng';
            $ownerMsg = $isRescheduled
                ? "Khách thuê {$tenantName} đã từ chối giờ đề xuất mới cho phòng \"{$roomName}\" ({$timeStr} ngày {$formattedDate}).{$reasonDesc}"
                : "Khách thuê {$tenantName} đã hủy lịch xem phòng \"{$roomName}\" (lịch hẹn: {$timeStr} ngày {$formattedDate}).{$reasonDesc}";

            Notification::notify(
                $owner->id,
                $ownerTitle,
                $ownerMsg,
                'appointment',
                'appointment',
                $appointment->id
            );
        }

        // 2. Gửi thông báo xác nhận cho người thuê
        $tenantReasonDesc = $cancelReason !== '' ? " Lý do: \"{$cancelReason}\"." : '';
        $tenantTitle = $isRescheduled
            ? 'Bạn đã từ chối giờ hẹn đề xuất'
            : 'Bạn đã hủy lịch xem phòng';
        $tenantMsg = $isRescheduled
            ? "Bạn đã từ chối giờ đề xuất mới cho phòng \"{$roomName}\" ({$timeStr} ngày {$formattedDate}).{$tenantReasonDesc}"
            : "Bạn đã hủy lịch xem phòng \"{$roomName}\" thành công.{$tenantReasonDesc}";

        Notification::notify(
            Auth::id(),
            $tenantTitle,
            $tenantMsg,
            'appointment',
            'appointment',
            $appointment->id
        );

        $successMsg = $isRescheduled
            ? 'Đã từ chối giờ đề xuất mới thành công.'
            : 'Đã hủy lịch xem phòng thành công.';

        return back()->with('success', $successMsg);
    }

    // Người thuê đồng ý giờ mới mà chủ trọ đã đề xuất
    public function acceptReschedule(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để thực hiện thao tác.');
        }

        $appointment = ViewingAppointment::with(['room.property.owner'])
            ->where('tenant_id', Auth::id())
            ->findOrFail($id);

        if ($appointment->status !== 'rescheduled') {
            return back()->with('error', 'Lịch hẹn này không ở trạng thái đề xuất giờ mới.');
        }

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Gửi thông báo cho chủ trọ biết khách đã đồng ý
        $owner = $appointment->room?->property?->owner;
        if ($owner) {
            $formattedDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
            $timeStr = substr((string)$appointment->appointment_time, 0, 5);
            $roomName = $appointment->room?->name ?? 'phòng trọ';

            Notification::notify(
                $owner->id,
                'Khách thuê đã đồng ý giờ xem phòng mới',
                "Khách thuê {$appointment->tenant?->name} đã đồng ý lịch hẹn xem phòng {$roomName} vào lúc {$timeStr} ngày {$formattedDate}.",
                'appointment',
                'appointment',
                $appointment->id
            );
        }

        return back()->with('success', 'Bạn đã đồng ý lịch hẹn mới. Lịch xem phòng đã được xác nhận thành công!');
    }
}
