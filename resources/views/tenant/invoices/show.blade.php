


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/invoices/show.css') }}">
@endpush

@section('content')



<!-- MAIN CONTENT -->
<div class="page-wrap" id="invoice-detail" style="padding-top: 20px;">
  
  <!-- Breadcrumb -->
  <div class="breadcrumb-custom mb-3 animate-up delay-1 skeleton-box rounded" style="color: var(--muted); font-size: 13px; font-weight: 500;">
    <span class="hide-on-skeleton">
      <a href="{{ route('tenant.home') }}" class="text-decoration-none text-muted">Trang chủ</a> <span class="mx-2 text-light-gray">/</span>
      <a href="{{ route('tenant.invoices.index') }}" class="text-decoration-none text-muted">Hóa đơn</a> <span class="mx-2 text-light-gray">/</span>
      <span class="fw-bold" style="color: var(--green-dark);">Chi tiết #{{ $invoice->invoice_code }}</span>
    </span>
  </div>

  @php
    $statusLabels = ['unpaid' => 'Chưa Thanh Toán', 'pending' => 'Chờ Xác Nhận', 'paid' => 'Đã Thanh Toán', 'overdue' => 'Quá Hạn', 'cancelled' => 'Đã Hủy'];
    $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
    $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
    $canPay = in_array($invoice->status, ['unpaid', 'pending', 'overdue']) && ! $invoice->payments->whereIn('status', ['pending', 'success'])->count();
  @endphp

  @if(session('success'))
    <div class="alert alert-success animate-up delay-1">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger animate-up delay-1">
      <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="row g-4">
    <!-- CỘT TRÁI: TỜ HÓA ĐƠN DOANH NGHIỆP -->
    <div class="col-lg-8 animate-up delay-2">
      <div class="invoice-paper skeleton-box">
        <div class="hide-on-skeleton">
          
          <!-- Watermark Đóng Dấu -->
          <div class="invoice-stamp stamp-pending">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</div>

          <!-- Header Hóa Đơn -->
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-5">
            <div>
              <h2 class="fw-bold mb-1" style="color: var(--green-dark); letter-spacing: -1px; text-transform: uppercase;">Hóa Đơn Thuê Phòng</h2>
              <div class="text-muted" style="font-size: 14px;">Mã số: <strong class="text-dark">#{{ $invoice->invoice_code }}</strong></div>
              <div class="text-muted" style="font-size: 14px;">Ngày lập: {{ $invoice->issue_date->format('d/m/Y') }} · Kỳ {{ $invoice->billing_month->format('m/Y') }}</div>
            </div>
            <div class="text-end">
              <div style="font-size: 26px; font-weight: 800; letter-spacing: -1px; color: var(--green-dark);">Trọ <span style="color: var(--yellow);">Ơi</span></div>
              <div class="text-muted" style="font-size: 12px; font-weight: 500;">Nền tảng quản lý lưu trú</div>
            </div>
          </div>

          <!-- Thông tin 2 bên (Bill From / Bill To) -->
          <div class="row mb-5" style="background: rgba(248, 249, 250, 0.5); padding: 20px; border-radius: 12px;">
            <div class="col-sm-6 mb-3 mb-sm-0 border-end">
              <div style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 700; margin-bottom: 8px;">Đơn vị phát hành</div>
              <div class="fw-bold text-dark" style="font-size: 16px;">{{ $invoice->contract->room->property->name ?? '' }}</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Chủ cơ sở: {{ $invoice->contract->owner->name ?? '' }}</div>
              <div class="text-muted" style="font-size: 13px;">SĐT: {{ $invoice->contract->owner->phone ?? '' }}</div>
            </div>
            <div class="col-sm-6 ps-sm-4">
              <div style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 700; margin-bottom: 8px;">Khách hàng (Người thuê)</div>
              <div class="fw-bold text-dark" style="font-size: 16px;">{{ $invoice->contract->tenant->name ?? '' }}</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Phòng thuê: <strong class="text-dark">{{ $invoice->contract->room->name ?? '' }}</strong></div>
              <div class="text-muted" style="font-size: 13px;">Hợp đồng: {{ $invoice->contract->contract_code ?? '' }}</div>
            </div>
          </div>

          <!-- Bảng Chi Phí -->
          <div class="table-responsive mb-4">
            <table class="w-100 invoice-table">
              <thead>
                <tr>
                  <th style="width: 5%;">#</th>
                  <th style="width: 45%;">Hạng mục tính phí</th>
                  <th class="text-center" style="width: 15%;">Số lượng</th>
                  <th class="text-end" style="width: 15%;">Đơn giá</th>
                  <th class="text-end" style="width: 20%;">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                  <td class="text-muted">{{ $index + 1 }}</td>
                  <td><div class="fw-bold">{{ $item->description }}</div></td>
                  <td class="text-center">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
                  <td class="text-end text-muted">{{ number_format((float) $item->unit_price) }}</td>
                  <td class="text-end fw-bold">{{ number_format((float) $item->amount) }} đ</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Khối Tổng Tiền -->
          <div class="total-box d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
              <div style="font-size: 13px; color: var(--green-dark); font-weight: 700; text-transform: uppercase;">Tổng số tiền cần thanh toán</div>
              <div class="text-muted mt-1" style="font-size: 12px;">Tạm tính: {{ number_format((float) $invoice->subtotal) }} đ @if((float) $invoice->discount > 0)· Giảm giá: {{ number_format((float) $invoice->discount) }} đ @endif</div>
            </div>
            <div class="text-end">
              <div style="font-size: 32px; font-weight: 800; color: var(--green-dark); letter-spacing: -1px;">{{ number_format((float) $invoice->total) }} đ</div>
            </div>
          </div>

          <!-- Điều khoản / Ghi chú -->
          <div class="mt-4 p-3 print-hide" style="background: rgba(245, 200, 75, 0.1); border-radius: 8px; border-left: 3px solid var(--yellow);">
            <strong style="font-size: 13px; color: #9b6a00;">Lưu ý từ ban quản lý:</strong>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #7a5400;">Quý khách vui lòng hoàn tất thanh toán trước ngày <strong class="text-danger">{{ $invoice->due_date->format('d/m/Y') }}</strong>.</p>
          </div>

          <!-- Lịch sử thanh toán -->
          @if($invoice->payments->isNotEmpty())
          <div class="mt-4">
            <strong style="font-size: 13px;">Lịch sử thanh toán</strong>
            <ul class="mb-0 mt-2" style="font-size: 13px;">
              @foreach($invoice->payments as $payment)
                <li>{{ $payment->payment_code }} — {{ number_format((float) $payment->amount) }} đ — {{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR', 'online' => 'Online'][$payment->method] ?? $payment->method }} — {{ ['pending' => 'chờ xác nhận', 'success' => 'thành công', 'failed' => 'thất bại', 'cancelled' => 'đã hủy'][$payment->status] ?? $payment->status }}{{ $payment->paid_at ? ' lúc ' . $payment->paid_at->format('H:i d/m/Y') : '' }}</li>
              @endforeach
            </ul>
          </div>
          @endif

        </div>
      </div>
    </div>

    <!-- CỘT PHẢI: BẢNG ĐIỀU KHIỂN THANH TOÁN -->
    <div class="col-lg-4 animate-up delay-3">
      <div class="payment-sidebar skeleton-box">
        <div class="hide-on-skeleton">
          
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color: var(--text);">Trạng thái</h5>
            <span class="badge-status {{ $displayStatus }}" style="font-size: 12px; padding: 6px 12px; letter-spacing: 0.5px;">{{ strtoupper($statusLabels[$displayStatus] ?? $displayStatus) }}</span>
          </div>

          <div class="mb-4 bg-light p-3 rounded-3 border">
            <div class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 700;">Hạn chót thanh toán</div>
            <div class="fw-bold d-flex align-items-center gap-2 {{ $isOverdue ? 'text-danger' : '' }}" style="font-size: 18px;">
              {{ $invoice->due_date->format('d/m/Y') }}
              @if($isOverdue)
                <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 10px;">Quá hạn</span>
              @endif
            </div>
            <div class="fw-bold mt-2" style="font-size: 20px; color: var(--green-dark);">{{ number_format((float) $invoice->total) }} đ</div>
          </div>

          <hr style="border-top: 1px dashed #ddd; margin: 20px 0;">

          <!-- Form thanh toán -->
          <div id="pay">
            @if($canPay)
              <form id="payForm" action="{{ route('tenant.payment.store') }}" method="POST" class="print-hide">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <label class="fw-bold" style="font-size: 13px;">Phương thức thanh toán</label>
                <select name="method" id="payMethod" class="form-select mb-3" required>
                  <option value="bank_transfer">🏦 Chuyển khoản</option>
                  <option value="cash">💵 Tiền mặt (chủ nhà xác nhận)</option>
                  <option value="qr">📱 QR</option>
                  <option value="online">🌐 Online</option>
                </select>

                <div class="text-center mb-3 p-3 rounded-4" style="background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0;">
                  <h6 class="fw-bold mb-2" style="color: var(--muted); font-size: 12px;">THÔNG TIN NHẬN THANH TOÁN</h6>
                  <div class="fw-bold mb-1" style="color: var(--green-dark); font-size: 15px;">{{ $invoice->contract->owner->name ?? 'Chủ nhà' }}</div>
                  <div class="text-muted mb-1" style="font-size: 13px;">SĐT: {{ $invoice->contract->owner->phone ?? '—' }}</div>
                  <div class="mt-2" style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ number_format((float) $invoice->total) }} đ</div>
                  <div class="text-muted" style="font-size: 12px;">Nội dung CK: {{ $invoice->invoice_code }} {{ $invoice->contract->room->name ?? '' }}</div>
                  <button type="button" onclick="copyPayInfo()" class="btn btn-light w-100 shadow-none border fw-bold text-muted py-2 mt-3 d-flex justify-content-center align-items-center gap-2" style="border-radius: 10px; font-size: 13px;">
                    📋 Sao chép số tiền &amp; nội dung
                  </button>
                </div>

                <input type="text" name="transaction_code" class="form-control mb-3" placeholder="Mã giao dịch sau khi chuyển (nếu có)" maxlength="100">

                <button type="submit" class="btn-brand w-100 py-3 mb-2" style="font-size: 15px; border-radius: 12px; background: var(--yellow); color: var(--green-dark); font-weight: 800;">✅ Tôi đã chuyển khoản</button>
                <div class="text-muted text-center" style="font-size: 12px;">Bấm để lưu thanh toán và gửi cho chủ nhà xác nhận.</div>
              </form>
            @elseif($invoice->status === 'paid')
              <div class="alert alert-success mb-0">Hóa đơn đã được thanh toán. Cảm ơn bạn!</div>
            @elseif($invoice->status === 'cancelled')
              <div class="alert alert-secondary mb-0">Hóa đơn đã bị hủy.</div>
            @else
              <div class="alert alert-info mb-0">Bạn đã gửi thanh toán, đang chờ chủ nhà xác nhận.</div>
            @endif
          </div>

          <div class="d-flex flex-column gap-2 print-hide mt-3">
            <div class="row g-2 mt-1">
              <div class="col-6">
                <button onclick="window.print()" class="btn btn-outline-brand w-100 py-2 d-flex justify-content-center align-items-center gap-2" style="font-size: 13px; background: #f8f9fa; border-color: #ddd; color: var(--text);">
                  🖨️ In Hóa Đơn
                </button>
              </div>
              <div class="col-6">
                <button onclick="window.print()" class="btn btn-outline-brand w-100 py-2 d-flex justify-content-center align-items-center gap-2" style="font-size: 13px; background: #f8f9fa; border-color: #ddd; color: var(--text);">
                  📥 Lưu PDF
                </button>
              </div>
            </div>
          </div>

<!-- TOAST THÔNG BÁO COPY -->
<div class="toast-container-custom">
  <div id="copyToast" class="toast-custom">
    <span style="color: var(--yellow); font-size: 16px;">✔</span> <span id="copyToastText">Đã sao chép!</span>
  </div>
</div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOAST THÔNG BÁO COPY -->
<div class="toast-container-custom">
  <div id="copyToast" class="toast-custom">
    <span style="color: var(--yellow); font-size: 16px;">✔</span> Đã sao chép nội dung chuyển khoản!
  </div>
</div>

@push('scripts')
    <script src="{{ asset('js/tv3/tenant/invoices/show.js') }}"></script>
@endpush


@endsection