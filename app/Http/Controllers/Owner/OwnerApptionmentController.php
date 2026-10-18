<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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

        if ($appointment->status !== 'pending') {
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

        return redirect()
            ->route('owner.appointments.index')
            ->with('success', $message);
    }
}
