

@extends('Layout.landlord')

@section('title', 'Quản lý sửa chữa | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/maintenance/show.css') }}">
@endpush

@section('content')


<main class="page-wrap">
  <a href="{{ route('owner.maintenance.index') }}" class="back-link">← Quay lại danh sách sửa chữa</a>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 my-3">
    <h1 class="h4 fw-bold mb-0" style="color: var(--green-dark);">#TCK-{{ $maintenance->id }} — {{ $maintenance->title }}</h1>
    @php $statusLabels = ['pending' => 'Chờ xử lý', 'processing' => 'Đang sửa', 'completed' => 'Hoàn thành', 'rejected' => 'Đã từ chối']; @endphp
    <span class="badge-status {{ $maintenance->status }}">{{ $statusLabels[$maintenance->status] ?? $maintenance->status }}</span>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <div class="panel">
    <h5>Thông tin yêu cầu</h5>
    <div class="row g-3" style="font-size: .9rem;">
      <div class="col-md-6">Phòng: <strong>{{ $maintenance->room->name ?? '' }}</strong></div>
      <div class="col-md-6">Người thuê: <strong>{{ $maintenance->tenant->name ?? '' }}</strong> ({{ $maintenance->tenant->phone ?? '' }})</div>
      <div class="col-md-6">Mức độ: <strong>{{ ['low' => 'Thấp', 'medium' => 'Trung bình', 'high' => 'Cao', 'urgent' => 'Khẩn cấp'][$maintenance->priority] ?? $maintenance->priority }}</strong></div>
      <div class="col-md-6">Gửi lúc: {{ $maintenance->created_at->format('H:i d/m/Y') }}</div>
      @if($maintenance->contract)
      <div class="col-md-6">Hợp đồng: <strong>{{ $maintenance->contract->contract_code }}</strong></div>
      @endif
      @if($maintenance->damage_type)
      <div class="col-md-6">
        Loại hư hại:
        @if($maintenance->damage_type === 'landlord')
          <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Hao mòn tự nhiên (Chủ trọ chịu)</span>
        @else
          <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Do khách làm hỏng (Khách chịu)</span>
        @endif
      </div>
      @endif

      @if($maintenance->cost > 0)
      <div class="col-md-6">Chi phí sửa chữa: <strong class="text-danger">{{ number_format($maintenance->cost) }} đ</strong></div>
      @endif

      @if($maintenance->invoice_id)
      <div class="col-12 mt-2">
        <div class="p-2 px-3 rounded-3 bg-light border d-inline-flex align-items-center gap-2">
          <span><i class="bi bi-receipt"></i> Hóa đơn đính kèm:</span>
          <strong>#{{ $maintenance->invoice?->invoice_code ?? $maintenance->invoice_id }}</strong>
          <a href="{{ route('owner.invoices.show', $maintenance->invoice_id) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 12px;">Xem hóa đơn</a>
        </div>
      </div>
      @endif

      @if($maintenance->description)
      <div class="col-12">Mô tả: {{ $maintenance->description }}</div>
      @endif
      @if($maintenance->image)
      <div class="col-12">Ảnh đính kèm:<br><img src="{{ asset('storage/' . $maintenance->image) }}" alt="Ảnh hiện trường" style="max-width: 320px; border-radius: 12px; border: 1px solid var(--border);"></div>
      @endif
      @if($maintenance->owner_note)
      <div class="col-12">Ghi chú của bạn: {{ $maintenance->owner_note }}</div>
      @endif
    </div>
  </div>

  @if(in_array($maintenance->status, ['pending', 'processing', 'completed']))
  <div class="panel">
    <h5>{{ $maintenance->status === 'completed' ? 'Cập nhật phân loại & Hóa đơn chi phí' : 'Cập nhật xử lý' }}</h5>
    <form action="{{ route('owner.maintenance.status', $maintenance->id) }}" method="POST" class="row g-3" id="maintenanceStatusForm">
      @csrf
      @method('PUT')
      <div class="col-md-4">
        <label class="form-label small fw-bold">Trạng thái yêu cầu</label>
        <select name="status" id="statusSelect" class="form-select" required onchange="toggleDamageFlow(this.value)">
          @if($maintenance->status === 'pending')
            <option value="processing">Đang sửa</option>
            <option value="completed">Hoàn thành</option>
          @elseif($maintenance->status === 'processing')
            <option value="completed" selected>Hoàn thành</option>
          @elseif($maintenance->status === 'completed')
            <option value="completed" selected>Hoàn thành</option>
            <option value="processing">Chuyển lại về Đang sửa</option>
          @endif
          <option value="rejected">Từ chối</option>
        </select>
      </div>

      <div class="col-md-8">
        <label class="form-label small fw-bold">Ghi chú cho người thuê</label>
        <input type="text" name="owner_note" value="{{ old('owner_note', $maintenance->owner_note) }}" class="form-control" placeholder="VD: Đã thay linh kiện mới, kiểm tra hoạt động tốt">
      </div>

      {{-- 2 LUỒNG XỬ LÝ HƯ HỎNG: CHỦ TRỌ CHỊU HAY KHÁCH CHỊU --}}
      <div class="col-12" id="damageFlowSection" style="display: {{ old('status', $maintenance->status) === 'completed' ? 'block' : 'none' }};">
        <div class="p-3 rounded-3 border bg-light">
          <label class="form-label small fw-bold text-dark d-block mb-2">Phân loại trách nhiệm chi phí:</label>
          
          <div class="d-flex flex-column gap-2 mb-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="damage_type" id="damage_landlord" value="landlord" 
                @checked(old('damage_type', $maintenance->damage_type ?? 'landlord') === 'landlord') onchange="toggleCostInput(false)">
              <label class="form-check-label fw-semibold" for="damage_landlord">
                <i class="bi bi-shield-check text-success me-1"></i> Hao mòn tự nhiên / Thiết bị cũ (Chủ trọ chịu chi phí)
                <span class="d-block text-muted small fw-normal">Chi phí 0 đ đối với khách thuê, không tạo hóa đơn.</span>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="damage_type" id="damage_tenant" value="tenant" 
                @checked(old('damage_type', $maintenance->damage_type) === 'tenant') onchange="toggleCostInput(true)">
              <label class="form-check-label fw-semibold" for="damage_tenant">
                <i class="bi bi-exclamation-triangle text-danger me-1"></i> Do người thuê làm hỏng (Khách thuê chi trả)
                <span class="d-block text-muted small fw-normal">Hệ thống sẽ tự động tạo hóa đơn riêng kèm mã QR để người thuê thanh toán ngay.</span>
              </label>
            </div>
          </div>

          <div id="costInputWrap" style="display: {{ old('damage_type', $maintenance->damage_type) === 'tenant' ? 'block' : 'none' }}; max-width: 320px;">
            <label class="form-label small fw-bold text-danger">Số tiền sửa chữa (VNĐ):</label>
            <div class="input-group">
              <input type="number" name="cost" id="costInput" min="0" step="1000" 
                value="{{ old('cost', $maintenance->cost > 0 ? (int)$maintenance->cost : '') }}" 
                class="form-control fw-bold" placeholder="VD: 250000">
              <span class="input-group-text fw-bold">đ</span>
            </div>
            @if($maintenance->invoice_id)
              <div class="text-success small mt-1">Đã xuất hóa đơn #{{ $maintenance->invoice?->invoice_code ?? $maintenance->invoice_id }}</div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-success px-4 fw-semibold">
          {{ $maintenance->invoice_id ? 'Lưu cập nhật' : 'Lưu & Tạo hóa đơn (nếu do khách chịu)' }}
        </button>
      </div>
    </form>
  </div>

  <script>
    function toggleDamageFlow(status) {
      const flow = document.getElementById('damageFlowSection');
      if (status === 'completed') {
        flow.style.display = 'block';
      } else {
        flow.style.display = 'none';
      }
    }
    function toggleCostInput(show) {
      const costWrap = document.getElementById('costInputWrap');
      const costInput = document.getElementById('costInput');
      if (show) {
        costWrap.style.display = 'block';
        costInput.setAttribute('required', 'required');
      } else {
        costWrap.style.display = 'none';
        costInput.removeAttribute('required');
        costInput.value = '';
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      const sel = document.getElementById('statusSelect');
      if (sel) toggleDamageFlow(sel.value);
    });
  </script>
  @endif
</main>


@endsection