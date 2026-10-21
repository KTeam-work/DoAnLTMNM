
@extends('Layout.landlord')

@section('title', 'Quản lý hoa đơn | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/invoice/show.css') }}">
@endpush

@section('content')


<main class="page-wrap">
  <a href="{{ route('owner.invoices.index') }}" class="back-link">← Quay lại danh sách hóa đơn</a>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 my-3">
    <h1 class="h4 fw-bold mb-0" style="color: var(--green-dark);">#{{ $invoice->invoice_code }}</h1>
    @php
      $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
      $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
      $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
    @endphp
    <span class="badge-status {{ $displayStatus }}">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span>
  </div>

  <div class="panel">
    <h5>Thông tin chung</h5>
    <div class="row g-3" style="font-size: .9rem;">
      <div class="col-md-6">Phòng: <strong>{{ $invoice->contract->room->name ?? '' }}</strong> ({{ $invoice->contract->room->property->name ?? '' }})</div>
      <div class="col-md-6">Người thuê: <strong>{{ $invoice->contract->tenant->name ?? '' }}</strong> ({{ $invoice->contract->tenant->phone ?? '' }})</div>
      <div class="col-md-4">Kỳ: <strong>{{ $invoice->billing_month->format('m/Y') }}</strong></div>
      <div class="col-md-4">Ngày lập: {{ $invoice->issue_date->format('d/m/Y') }}</div>
      <div class="col-md-4">Hạn thanh toán: <strong class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $invoice->due_date->format('d/m/Y') }}</strong></div>
    </div>
  </div>

  <div class="panel">
    <h5>Chi tiết các khoản</h5>
    <div class="table-responsive">
      <table class="data-table">
        <thead><tr><th>#</th><th>Hạng mục</th><th class="text-end">Số lượng</th><th class="text-end">Đơn giá</th><th class="text-end">Thành tiền</th></tr></thead>
        <tbody>
          @foreach($invoice->items as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->description }}</td>
            <td class="text-end">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
            <td class="text-end">{{ number_format((float) $item->unit_price) }}</td>
            <td class="text-end fw-bold">{{ number_format((float) $item->amount) }} đ</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="text-end mt-3" style="font-size: .9rem;">
      <div>Tạm tính: {{ number_format((float) $invoice->subtotal) }} đ</div>
      @if((float) $invoice->discount > 0)<div>Giảm giá: {{ number_format((float) $invoice->discount) }} đ</div>@endif
      <div class="fw-bold fs-5" style="color: var(--green-dark);">Tổng: {{ number_format((float) $invoice->total) }} đ</div>
    </div>
  </div>

  <div class="panel">
    <h5>Lịch sử thanh toán</h5>
    @forelse($invoice->payments as $payment)
      <div class="d-flex justify-content-between align-items-center border-bottom py-2 flex-wrap gap-2" style="font-size: .9rem;">
        <div><strong>{{ $payment->payment_code }}</strong> · {{ number_format((float) $payment->amount) }} đ · {{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR', 'online' => 'Online'][$payment->method] ?? $payment->method }}{{ $payment->transaction_code ? ' · ' . $payment->transaction_code : '' }}</div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge-status {{ $payment->status === 'success' ? 'paid' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">{{ ['pending' => 'Chờ xác nhận', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][$payment->status] ?? $payment->status }}</span>
          @if($payment->status === 'pending')
            <form action="{{ route('owner.payments.confirm', $payment->id) }}" method="POST" class="d-inline m-0">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận đã nhận {{ number_format((float) $payment->amount) }} đ?')">✓ Xác nhận</button>
            </form>
            <form action="{{ route('owner.payments.reject', $payment->id) }}" method="POST" class="d-inline m-0">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Từ chối giao dịch này?')">Từ chối</button>
            </form>
          @endif
        </div>
      </div>
    @empty
      <div class="text-muted">Chưa có giao dịch nào.</div>
    @endforelse
  </div>
</main>

@endsection