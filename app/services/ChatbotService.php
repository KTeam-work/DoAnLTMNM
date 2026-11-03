<?php

namespace App\Services;

use App\Models\User;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Property;
use Carbon\Carbon;

class ChatbotService
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Lấy dữ liệu ngữ cảnh an toàn từ MySQL tùy theo phân quyền người dùng
     */
    private function buildContext(?User $user = null): array
    {
        $role = $user?->role ?? 'tenant';
        $context = [
            'vai_tro' => $role,
            'ten_nguoi_dung' => $user?->name ?? 'Khách',
        ];

        // 1. NGƯỜI THUÊ (TENANT) HOẶC KHÁCH CHƯA ĐĂNG NHẬP
        if ($role === 'tenant' || !$user) {
            // Lấy danh sách phòng đang hoạt động (không lấy phòng ẩn 'hidden')
            $rooms = Room::with(['property.owner', 'amenities'])
                ->whereIn('status', ['available', 'rented', 'maintenance', 'pending'])
                ->limit(35)
                ->get();

            $context['danh_sach_phong'] = $this->formatRooms($rooms);

            // Dữ liệu cá nhân của người thuê (chỉ lấy nếu đã đăng nhập)
            if ($user) {
                // Hợp đồng thuê của người dùng
                $myContracts = Contract::with(['room.property', 'owner'])
                    ->where('tenant_id', $user->id)
                    ->get();

                $context['hop_dong_cua_toi'] = $myContracts->map(function ($c) {
                    return [
                        'ma_hop_dong' => $c->contract_code ?? ('HD-' . $c->id),
                        'phong' => $c->room?->name ?? 'Chưa rõ',
                        'khu_tro' => $c->room?->property?->name ?? 'Chưa rõ',
                        'chu_tro' => $c->owner?->name ?? 'Chủ trọ',
                        'sdt_chu_tro' => $c->owner?->phone ?? '',
                        'gia_thue' => number_format((float) $c->rent, 0, ',', '.') . ' VNĐ/tháng',
                        'tien_coc' => number_format((float) $c->deposit, 0, ',', '.') . ' VNĐ',
                        'ngay_bat_dau' => $c->start_date ? $c->start_date->format('d/m/Y') : 'Chưa rõ',
                        'ngay_ket_thuc' => $c->end_date ? $c->end_date->format('d/m/Y') : 'Không thời hạn',
                        'trang_thai' => match($c->status) {
                            'active' => 'Đang hiệu lực',
                            'draft' => 'Bản nháp',
                            'expired' => 'Đã hết hạn',
                            'terminated' => 'Đã thanh lý',
                            default => $c->status
                        },
                    ];
                })->toArray();

                // Hóa đơn của người thuê
                $contractIds = $myContracts->pluck('id');
                $myInvoices = Invoice::with('contract.room')
                    ->whereIn('contract_id', $contractIds)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                $context['hoa_don_cua_toi'] = $myInvoices->map(function ($inv) {
                    return [
                        'ma_hoa_don' => $inv->invoice_code ?? ('HDN-' . $inv->id),
                        'phong' => $inv->contract?->room?->name ?? 'Phòng',
                        'thang_tinh_tien' => $inv->billing_month ? Carbon::parse($inv->billing_month)->format('m/Y') : '',
                        'tong_tien' => number_format((float) $inv->total, 0, ',', '.') . ' VNĐ',
                        'han_thanh_toan' => $inv->due_date ? Carbon::parse($inv->due_date)->format('d/m/Y') : '',
                        'trang_thai' => match($inv->status) {
                            'paid' => 'Đã thanh toán',
                            'unpaid' => 'Chưa thanh toán',
                            'pending' => 'Chờ xác nhận',
                            'overdue' => 'Quá hạn',
                            'cancelled' => 'Đã hủy',
                            default => $inv->status
                        },
                    ];
                })->toArray();
            }
        }

        // 2. CHỦ TRỌ (OWNER)
        elseif ($role === 'owner') {
            // Lấy tất cả phòng thuộc khu trọ của chủ trọ này
            $rooms = Room::with(['property', 'amenities'])
                ->whereHas('property', function ($q) use ($user) {
                    $q->where('owner_id', $user->id);
                })
                ->get();

            $totalRooms = $rooms->count();
            $rentedRooms = $rooms->where('status', 'rented')->count();
            $availableRooms = $rooms->where('status', 'available')->count();
            $maintenanceRooms = $rooms->where('status', 'maintenance')->count();
            $occupancyRate = $totalRooms > 0 ? round(($rentedRooms / $totalRooms) * 100, 1) : 0;

            // Hóa đơn thuộc các khu trọ của chủ trọ
            $ownerPropertyIds = Property::where('owner_id', $user->id)->pluck('id');
            $ownerRoomIds = Room::whereIn('property_id', $ownerPropertyIds)->pluck('id');
            $ownerContractIds = Contract::whereIn('room_id', $ownerRoomIds)->pluck('id');

            $invoices = Invoice::whereIn('contract_id', $ownerContractIds)->get();

            $unpaidInvoices = $invoices->whereIn('status', ['unpaid', 'overdue', 'pending']);
            $totalUnpaidAmount = (float) $unpaidInvoices->sum('total');

            // Doanh thu đã thanh toán trong tháng hiện tại
            $currentMonth = Carbon::now()->startOfMonth();
            $paidThisMonth = $invoices->filter(function ($inv) use ($currentMonth) {
                return $inv->status === 'paid' && Carbon::parse($inv->billing_month)->startOfMonth()->equalTo($currentMonth);
            })->sum('total');

            $context['thong_ke_chu_tro'] = [
                'tong_so_khu_tro' => $ownerPropertyIds->count(),
                'tong_so_phong' => $totalRooms,
                'phong_dang_thue' => $rentedRooms,
                'phong_con_trong' => $availableRooms,
                'phong_bao_tri' => $maintenanceRooms,
                'ty_le_lap_day' => $occupancyRate . '%',
                'doanh_thu_thang_nay' => number_format((float) $paidThisMonth, 0, ',', '.') . ' VNĐ',
                'so_hoa_don_chua_thu' => $unpaidInvoices->count(),
                'tong_tien_chua_thu' => number_format($totalUnpaidAmount, 0, ',', '.') . ' VNĐ',
            ];

            $context['danh_sach_phong'] = $this->formatRooms($rooms);
        }

        // 3. QUẢN TRỊ VIÊN (ADMIN)
        elseif ($role === 'admin') {
            $totalUsers = User::count();
            $totalOwners = User::where('role', 'owner')->count();
            $totalTenants = User::where('role', 'tenant')->count();
            $totalProperties = Property::count();
            $totalRooms = Room::count();
            $availableRooms = Room::where('status', 'available')->count();
            $rentedRooms = Room::where('status', 'rented')->count();

            $context['thong_ke_he_thong'] = [
                'tong_nguoi_dung' => $totalUsers,
                'so_chu_tro' => $totalOwners,
                'so_nguoi_thue' => $totalTenants,
                'tong_khu_tro' => $totalProperties,
                'tong_so_phong' => $totalRooms,
                'phong_con_trong' => $availableRooms,
                'phong_da_thue' => $rentedRooms,
                'ty_le_lap_day_toan_san' => $totalRooms > 0 ? round(($rentedRooms / $totalRooms) * 100, 1) . '%' : '0%',
            ];
        }

        return $context;
    }

    /**
     * Format dữ liệu danh sách phòng sang cấu trúc rõ ràng
     */
    private function formatRooms($rooms): array
    {
        $statusMap = [
            'available' => 'Còn trống',
            'pending' => 'Chờ duyệt / Đang xử lý',
            'rented' => 'Đã cho thuê',
            'maintenance' => 'Đang sửa chữa / bảo trì',
            'hidden' => 'Đang ẩn',
        ];

        $typeMap = [
            'rooming_house' => 'Dãy trọ / Phòng trọ',
            'apartment' => 'Căn hộ mini / Chung cư mini',
            'house' => 'Nhà nguyên căn',
        ];

        return $rooms->map(function ($room) use ($statusMap, $typeMap) {
            $prop = $room->property;
            $owner = $prop?->owner;

            $fullAddress = collect([$prop?->address, $prop?->ward, $prop?->district])
                ->filter()
                ->implode(', ');

            $amenityList = $room->amenities && $room->amenities->isNotEmpty()
                ? $room->amenities->pluck('name')->implode(', ')
                : 'Tiện ích cơ bản';

            return [
                'id' => $room->id,
                'ma_phong' => $room->room_code ?? ('P-' . $room->id),
                'ten_phong' => $room->name,
                'gia_thue' => number_format((float) $room->price, 0, ',', '.') . ' VNĐ/tháng',
                'gia_thue_so' => (float) $room->price,
                'tien_coc' => number_format((float) $room->deposit, 0, ',', '.') . ' VNĐ',
                'dien_tich' => $room->area ? ($room->area . ' m²') : 'Chưa cập nhật',
                'so_nguoi_toi_da' => $room->max_people ? ($room->max_people . ' người') : 'Chưa cập nhật',
                'tang' => $room->floor ? ('Tầng ' . $room->floor) : 'Trệt / Chưa cập nhật',
                'trang_thai' => $statusMap[$room->status] ?? $room->status,
                'tien_ich' => $amenityList,
                'khu_tro' => $prop?->name ?? 'Chưa xác định',
                'loai_hinh' => $typeMap[$prop?->type] ?? ($prop?->type ?? 'Phòng trọ'),
                'dia_chi' => $fullAddress ?: 'Chưa cập nhật địa chỉ',
                'chu_tro' => $owner?->name ?? 'Ban quản lý',
                'sdt_chu_tro' => $owner?->phone ?? '',
                'mo_ta' => $room->description ? strip_tags($room->description) : '',
            ];
        })->toArray();
    }

    /**
     * Xử lý câu hỏi và tạo câu trả lời từ Gemini AI
     */
    public function answer(?User $user, string $message): string
    {
        $contextData = $this->buildContext($user);

        $contextJson = json_encode(
            $contextData,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        $roleName = match($user?->role) {
            'owner' => 'Chủ trọ (quản lý khu trọ)',
            'admin' => 'Quản trị viên hệ thống (Admin)',
            default => 'Người tìm phòng / Khách thuê',
        };

        $userName = $user?->name ? "({$user->name})" : "";

        $systemPrompt = "
Bạn là trợ lý ảo AI thông minh, nhiệt tình và thân thiện của website quản lý phòng trọ 'Trọ Ơi'.
Người dùng đang trò chuyện: {$roleName} {$userName}.

Dữ liệu thực tế từ hệ thống (database MySQL đã được xác thực và lọc đúng quyền):
{$contextJson}

Nguyên tắc và hướng dẫn trả lời:
1. Luôn trả lời bằng tiếng Việt, giọng điệu niềm nở, lịch sự, tự nhiên và chuyên nghiệp.
2. Dựa vào dữ liệu được cung cấp ở trên để trả lời các câu hỏi về:
   - Phòng trọ (tìm phòng, giá cả, diện tích, tiện ích, tình trạng phòng còn trống hay đã thuê).
   - Hợp đồng thuê và hóa đơn tiền phòng/điện nước của người thuê (nếu người dùng là Tenant).
   - Báo cáo số liệu, tỷ lệ lấp đầy, phòng trống, hóa đơn công nợ, doanh thu (nếu người dùng là Owner).
   - Báo cáo thống kê toàn hệ thống (nếu người dùng là Admin).
3. Tuyệt đối không tự bịa đặt giá cả, số tiền hoặc tình trạng phòng không có trong dữ liệu.
4. Nếu người dùng hỏi một phòng cụ thể đang 'Đã cho thuê', hãy báo rõ phòng đã được thuê và gợi ý các phòng 'Còn trống' khác phù hợp.
5. Với các câu hỏi chào hỏi hoặc hướng dẫn sử dụng website Trọ Ơi:
   - Hãy giải thích rõ ràng các tính năng như Tìm phòng, Đặt lịch hẹn xem phòng, Quản lý hợp đồng & hóa đơn.
6. Trình bày câu trả lời rõ ràng, dùng gạch đầu dòng, in đậm các thông tin quan trọng để người dùng dễ theo dõi.
";

        return $this->gemini->chat(
            $message,
            $systemPrompt
        );
    }
}
