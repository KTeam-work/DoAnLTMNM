@extends('Layout.landlord')

@section('title', 'Chi tiết hóa đơn #' . $invoice->invoice_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/invoice/show.css') }}">
@endpush

@section('content')

<main class="page-wrap">
  @if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 p-3 mb-4 shadow-sm border-0" 
         style="background: #ecfdf5; border-left: 5px solid #10b981 !important; border-radius: 14px; color: #065f46;" role="alert">
      <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #10b981; color: white; font-size: 18px;">
        <i class="bi bi-check-lg"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size: 14px;">Thành công!</div>
        <div style="font-size: 13px; color: #047857;">{{ session('success') }}</div>
      </div>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 p-3 mb-4 shadow-sm border-0" 
         style="background: #fef2f2; border-left: 5px solid #ef4444 !important; border-radius: 14px; color: #991b1b;" role="alert">
      <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #ef4444; color: white; font-size: 18px;">
        <i class="bi bi-exclamation-triangle"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size: 14px;">Thông báo</div>
        <div style="font-size: 13px;">{{ session('error') }}</div>
      </div>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
  @endif

  <!-- Top Navigation & Header -->
  <div class="mb-3">
    <a href="{{ route('owner.invoices.index') }}" class="back-link mb-2 d-inline-flex">
      <i class="bi bi-arrow-left"></i> Quay lại danh sách hóa đơn
    </a>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-1">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <h1 class="h3 fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">#{{ $invoice->invoice_code }}</h1>
        @php
          $statusLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Chờ xác nhận',
            'paid' => 'Đã thanh toán',
            'overdue' => 'Quá hạn',
            'cancelled' => 'Đã hủy'
          ];
          $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
          $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
        @endphp
        <span class="badge-status {{ $displayStatus }}">
          @if($displayStatus === 'pending')
            <span class="spinner-grow spinner-grow-sm text-warning" style="width: 6px; height: 6px;" role="status"></span>
          @elseif($displayStatus === 'paid')
            <i class="bi bi-check-circle-fill"></i>
          @endif
          {{ $statusLabels[$displayStatus] ?? $displayStatus }}
        </span>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-2 align-items-center flex-wrap">
        @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
          <form action="{{ route('owner.invoices.record-cash', $invoice->id) }}" method="POST" class="m-0" onsubmit="return confirm('Xác nhận khách đã nộp {{ number_format((float) $invoice->total) }} đ tiền mặt trực tiếp?')">
            @csrf
            <button type="submit" class="btn btn-success fw-bold d-flex align-items-center gap-2 shadow-sm" 
                    style="border-radius: 12px; background: #059669; border: none; font-size: 13.5px; padding: 9px 18px;">
              <i class="bi bi-cash-stack"></i> Thu tiền mặt ({{ number_format((float) $invoice->total) }} đ)
            </button>
          </form>
        @endif

        <a href="{{ route('owner.invoices.print', $invoice->id) }}" target="_blank" 
           class="btn btn-light fw-bold d-flex align-items-center gap-2 border shadow-sm" 
           style="border-radius: 12px; background: #ffffff; color: #1e293b; font-size: 13.5px; padding: 9px 18px;">
          <i class="bi bi-printer"></i> In hóa đơn
        </a>
      </div>
    </div>
  </div>

  @php
    $pendingPayment = $invoice->payments->firstWhere('status', 'pending');
  @endphp

  @if($pendingPayment)
    <!-- Callout Banner: Khách báo nộp tiền -->
    <div class="p-3 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-sm" 
         style="background: #fffbeb; border: 1px solid #fde68a;">
      <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
             style="width: 44px; height: 44px; background: #fef3c7; color: #b45309; font-size: 20px;">
          <i class="bi bi-clock-history"></i>
        </div>
        <div>
          <div class="fw-bold text-dark" style="font-size: 14.5px;">
            Khách thuê đã báo nộp tiền: <span class="text-danger">{{ number_format((float) $pendingPayment->amount) }} đ</span>
            ({{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR'][$pendingPayment->method] ?? $pendingPayment->method }})
          </div>
          <div class="text-muted small">Mã giao dịch: <strong>{{ $pendingPayment->payment_code }}</strong> · Vui lòng xác nhận sau khi bạn đã kiểm tra và nhận đủ tiền.</div>
        </div>
      </div>
      <div class="d-flex gap-2">
        <form action="{{ route('owner.payments.confirm', $pendingPayment->id) }}" method="POST" class="m-0">
          @csrf
          @method('PUT')
          <button type="submit" class="btn btn-success fw-bold px-3 py-2 shadow-sm" 
                  style="border-radius: 10px; font-size: 13px; background: #059669; border: none;" 
                  onclick="return confirm('Xác nhận đã nhận đủ {{ number_format((float) $pendingPayment->amount) }} đ và gạch nợ hóa đơn?')">
            ✓ Xác nhận đã nhận tiền
          </button>
        </form>
        <form action="{{ route('owner.payments.reject', $pendingPayment->id) }}" method="POST" class="m-0">
          @csrf
          @method('PUT')
          <button type="submit" class="btn btn-outline-danger fw-bold px-3 py-2" 
                  style="border-radius: 10px; font-size: 13px;" 
                  onclick="return confirm('Từ chối giao dịch xác nhận này?')">
            Từ chối
          </button>
        </form>
      </div>
    </div>
  @endif

  <!-- Thông tin chung (4 Cột Thẻ) -->
  <div class="invoice-panel mb-4">
    <div class="invoice-panel-header">
      <span>Thông tin chung</span>
      <span class="text-muted fw-normal small">Hợp đồng: <strong>#{{ $invoice->contract->contract_code ?? '—' }}</strong></span>
    </div>
    <div class="row g-3">
      <!-- Cột 1: Phòng & Bất động sản -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Phòng trọ</div>
          <div class="info-item-value text-truncate">{{ $invoice->contract->room->name ?? '—' }}</div>
          <div class="info-item-sub text-truncate">{{ $invoice->contract->room->property->name ?? 'Nhà trọ' }}</div>
        </div>
      </div>

      <!-- Cột 2: Khách thuê -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Khách thuê</div>
          <div class="info-item-value text-truncate">{{ $invoice->contract->tenant->name ?? '—' }}</div>
          <div class="info-item-sub text-truncate">{{ $invoice->contract->tenant->phone ?? 'Chưa có SĐT' }}</div>
        </div>
      </div>

      <!-- Cột 3: Kỳ & Ngày lập -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Kỳ thanh toán</div>
          <div class="info-item-value">Tháng {{ $invoice->billing_month->format('m/Y') }}</div>
          <div class="info-item-sub">Ngày lập: {{ $invoice->issue_date->format('d/m/Y') }}</div>
        </div>
      </div>

      <!-- Cột 4: Hạn thanh toán -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Hạn thanh toán</div>
          <div class="info-item-value {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
            {{ $invoice->due_date->format('d/m/Y') }}
          </div>
          <div class="info-item-sub">
            @if($invoice->status === 'paid')
              <span class="text-success fw-bold">Đã hoàn tất</span>
            @elseif($isOverdue)
              <span class="text-danger fw-bold">Đã quá hạn thanh toán</span>
            @else
              @php
                $days = (int) ceil(now()->diffInDays($invoice->due_date, false));
              @endphp
              <span class="text-muted">{{ $days > 0 ? 'Còn ' . $days . ' ngày' : 'Đến hạn hôm nay' }}</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chi tiết các khoản -->
  <div class="invoice-panel mb-4">
    <div class="invoice-panel-header">
      <span>Chi tiết các khoản phí</span>
      <span class="text-muted fw-normal small">{{ count($invoice->items) }} hạng mục</span>
    </div>

    <div class="table-responsive">
      <table class="invoice-table">
        <thead>
          <tr>
            <th style="width: 50px;">#</th>
            <th>Hạng mục</th>
            <th class="text-end" style="width: 120px;">Số lượng</th>
            <th class="text-end" style="width: 150px;">Đơn giá</th>
            <th class="text-end" style="width: 160px;">Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->items as $index => $item)
          <tr>
            <td class="text-muted fw-bold">{{ $index + 1 }}</td>
            <td class="fw-bold text-dark">{{ $item->description }}</td>
            <td class="text-end">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
            <td class="text-end text-muted">{{ number_format((float) $item->unit_price) }} đ</td>
            <td class="text-end fw-bold text-dark">{{ number_format((float) $item->amount) }} đ</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Tổng tiền -->
    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
      <div style="min-width: 260px;">
        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 13.5px;">
          <span>Tạm tính:</span>
          <span class="fw-bold text-dark">{{ number_format((float) $invoice->subtotal) }} đ</span>
        </div>
        @if((float) $invoice->discount > 0)
        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 13.5px;">
          <span>Giảm giá:</span>
          <span class="text-danger fw-bold">-{{ number_format((float) $invoice->discount) }} đ</span>
        </div>
        @endif
        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
          <span class="fw-bold text-dark" style="font-size: 15px;">Tổng cộng:</span>
          <span class="fw-bold fs-4" style="color: #059669;">{{ number_format((float) $invoice->total) }} đ</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Lịch sử giao dịch -->
  <div class="invoice-panel">
    <div class="invoice-panel-header">
      <span>Lịch sử thanh toán & Giao dịch</span>
      <span class="text-muted fw-normal small">{{ count($invoice->payments) }} giao dịch</span>
    </div>

    @forelse($invoice->payments as $payment)
      <div class="payment-item d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
               style="width: 40px; height: 40px; background: {{ $payment->status === 'success' ? '#ecfdf5' : ($payment->status === 'pending' ? '#fffbeb' : '#f1f5f9') }}; color: {{ $payment->status === 'success' ? '#059669' : ($payment->status === 'pending' ? '#b45309' : '#64748b') }}; font-size: 18px;">
            <i class="bi bi-{{ $payment->method === 'cash' ? 'cash-coin' : ($payment->method === 'qr' ? 'qr-code' : 'bank') }}"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="fw-bold text-dark" style="font-size: 14px;">#{{ $payment->payment_code }}</span>
              <span class="badge bg-light text-secondary border" style="font-size: 11px;">
                {{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR SePay', 'online' => 'Online'][$payment->method] ?? $payment->method }}
              </span>
            </div>
            <div class="text-muted small mt-1">
              {{ $payment->created_at ? $payment->created_at->format('H:i · d/m/Y') : '—' }}
              @if($payment->transaction_code)
                · Mã GD: <code>{{ $payment->transaction_code }}</code>
              @endif
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3">
          <span class="fw-bold fs-6 text-dark">{{ number_format((float) $payment->amount) }} đ</span>
          <span class="badge-status {{ $payment->status === 'success' ? 'paid' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">
            {{ ['pending' => 'Chờ xác nhận', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][$payment->status] ?? $payment->status }}
          </span>

          @if($payment->status === 'pending')
            <div class="d-flex gap-1">
              <form action="{{ route('owner.payments.confirm', $payment->id) }}" method="POST" class="d-inline m-0">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-sm btn-success fw-bold px-3 py-1" style="border-radius: 8px;" onclick="return confirm('Xác nhận đã nhận {{ number_format((float) $payment->amount) }} đ?')">
                  ✓ Xác nhận
                </button>
              </form>
              <form action="{{ route('owner.payments.reject', $payment->id) }}" method="POST" class="d-inline m-0">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3 py-1" style="border-radius: 8px;" onclick="return confirm('Từ chối giao dịch này?')">
                  Từ chối
                </button>
              </form>
            </div>
          @endif
        </div>
      </div>
    @empty
      <div class="text-center text-muted py-4" style="font-size: 13.5px;">
        Chưa có giao dịch thanh toán nào được ghi nhận cho hóa đơn này.
      </div>
    @endforelse
  </div>
</main>

@endsection
