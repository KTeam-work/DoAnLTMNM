<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ViewingAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OwnerApptionmentController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = Auth::id();

        $baseQuery = ViewingAppointment::query()
            ->whereHas('room.property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            });

        $statusFilter = $request->input('status', 'all');

        $query = (clone $baseQuery)
            ->with(['room.property', 'tenant'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');

        $validStatuses = [
            'pending',
            'confirmed',
            'rescheduled',
            'completed',
            'cancelled',
        ];

        if (in_array($statusFilter, $validStatuses, true)) {
            $query->where('status', $statusFilter);
        } else {
            $statusFilter = 'all';
        }

        $appointments = $query->get();

        $counts = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)
                ->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)
                ->where('status', 'confirmed')->count(),
            'rescheduled' => (clone $baseQuery)
                ->where('status', 'rescheduled')->count(),
            'completed' => (clone $baseQuery)
                ->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)
                ->where('status', 'cancelled')->count(),
        ];

        // Chuẩn bị dữ liệu riêng cho FullCalendar tại controller.
        // Tránh nhúng callback PHP phức tạp trực tiếp trong @json.
        $calendarEvents = $appointments->map(function ($appointment) {
            $date = $appointment->appointment_date
                ? Carbon::parse($appointment->appointment_date)->format('Y-m-d')
                : null;

            $time = $appointment->appointment_time
                ? substr((string) $appointment->appointment_time, 0, 8)
                : '09:00:00';

            $tenant = $appointment->tenant;
            $room = $appointment->room;

            $statusLabels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'rescheduled' => 'Đã đề xuất giờ mới',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
            ];

            return [
                'id' => (string) $appointment->id,
                'title' => ($tenant->name ?? 'Khách thuê')
                    . ' - ' . ($room->name ?? 'Phòng'),
                'start' => $date ? $date . 'T' . $time : null,
                'allDay' => false,

                'extendedProps' => [
                    'tenant' => $tenant->name ?? 'Khách thuê',
                    'room' => $room->name ?? 'Phòng',
                    'address' => optional($room->property)->address ?? '—',
                    'phone' => $appointment->phone ?? '—',
                    'message' => $appointment->message ?? 'Không có lời nhắn',
                    'date' => $date,
                    'time' => $time,
                    'status' => $appointment->status ?? 'pending',
                    'statusLabel' => $statusLabels[$appointment->status ?? 'pending']
                        ?? 'Không xác định',
                ],
            ];
        })->filter(function ($event) {
            return !empty($event['start']);
        })->values();

       return view('owner.appointments.index', compact(
                'appointments',
                'statusFilter',
                'counts',
                'calendarEvents'
            ));
    }

    public function confirm(Request $request, int $id)
    {
        return $this->updateStatus(
            $request,
            $id,
            'confirmed',
            'Đã xác nhận lịch xem phòng.'
        );
    }

    public function reject(Request $request, int $id)
    {
        return $this->updateStatus(
            $request,
            $id,
            'cancelled',
            'Đã từ chối lịch xem phòng.'
        );
    }

    public function reschedule(Request $request, int $id)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'owner_note' => 'required|string|max:500',
        ], [
            'appointment_date.required' => 'Vui lòng chọn ngày mới.',
            'appointment_date.after_or_equal' => 'Ngày hẹn mới không thể là ngày trong quá khứ.',
            'appointment_time.required' => 'Vui lòng chọn giờ mới.',
            'owner_note.required' => 'Vui lòng nhập lời nhắn/lý do đổi lịch cho khách.',
        ]);

        $ownerId = Auth::id();

        $appointment = ViewingAppointment::query()
            ->with(['room', 'tenant'])
            ->whereHas('room.property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'rescheduled'])) {
            return redirect()
                ->route('owner.appointments.index')
                ->withErrors(['status' => 'Lịch hẹn này không thể đổi giờ.']);
        }

        $appointment->update([
            'appointment_date' => $request->input('appointment_date'),
            'appointment_time' => $request->input('appointment_time'),
            'owner_note' => $request->input('owner_note'),
            'status' => 'rescheduled',
        ]);

        // Gửi thông báo cho người thuê
        if ($appointment->tenant_id) {
            $formattedDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y');
            $timeStr = substr((string)$appointment->appointment_time, 0, 5);
            $roomName = $appointment->room?->name ?? 'phòng trọ';

            Notification::notify(
                $appointment->tenant_id,
                'Chủ trọ đề xuất giờ xem phòng mới',
                "Chủ trọ đề xuất đổi lịch xem phòng {$roomName} sang {$timeStr} ngày {$formattedDate}. Lời nhắn: \"{$appointment->owner_note}\". Vui lòng vào xác nhận.",
                'appointment',
                'appointment',
                $appointment->id
            );
        }

        return redirect()
            ->route('owner.appointments.index')
            ->with('success', 'Đã gửi đề xuất giờ xem phòng mới cho khách thuê.');
    }

    private function updateStatus(
        Request $request,
        int $id,
        string $status,
        string $message
    ) {
        $ownerId = Auth::id();

        $appointment = ViewingAppointment::query()
            ->whereHas('room.property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'rescheduled'])) {
            return redirect()
                ->route('owner.appointments.index')
                ->withErrors([
                    'status' => 'Lịch hẹn này đã được xử lý rồi.',
                ]);
        }

        $data = ['status' => $status];

        if ($status === 'confirmed') {
            $data['confirmed_at'] = now();
        } elseif ($status === 'cancelled') {
            $data['cancelled_at'] = now();
        }

        $appointment->update($data);

        // Gửi thông báo cho khách thuê
        if ($appointment->tenant_id) {
            $roomName = $appointment->room?->name ?? 'phòng trọ';
            if ($status === 'confirmed') {
                Notification::notify(
                    $appointment->tenant_id,
                    'Lịch xem phòng đã được xác nhận',
                    "Chủ trọ đã xác nhận lịch hẹn xem phòng {$roomName} của bạn.",
                    'appointment',
                    'appointment',
                    $appointment->id
                );
            } elseif ($status === 'cancelled') {
                Notification::notify(
                    $appointment->tenant_id,
                    'Lịch xem phòng bị từ chối',
                    "Rất tiếc, chủ trọ đã từ chối lịch xem phòng {$roomName}.",
                    'appointment',
                    'appointment',
                    $appointment->id
                );
            }
        }

        return redirect()
            ->route('owner.appointments.index')
            ->with('success', $message);
    }
}
