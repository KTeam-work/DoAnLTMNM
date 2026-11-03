{{-- =========================================================
     MODAL THANH TOÁN QR SEPAY (DÙNG CHUNG CHO TENANT)
     ========================================================= --}}

<div class="modal fade" id="sepayQrModal" tabindex="-1" aria-labelledby="sepayQrModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header text-white" style="background: #20584f; padding: 18px 24px;">
        <div class="d-flex align-items-center gap-2">
          <span style="font-size: 22px;">📱</span>
          <div>
            <h5 class="modal-title fw-bold mb-0" id="sepayQrModalLabel" style="font-size: 17px;">Thanh Toán QR SePay</h5>
            <small class="opacity-75" style="font-size: 12px;">Hóa đơn: <strong id="modalInvoiceCodeText">—</strong></small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background: #fbfaf6;">

        <!-- 1. Trạng thái Đang tải -->
        <div id="modalQrLoading" class="text-center py-5">
          <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="mt-3 fw-bold text-dark" style="font-size: 15px;">Đang khởi tạo mã QR thanh toán...</div>
          <div class="text-muted" style="font-size: 13px;">Hệ thống đang kết nối cổng SePay để tạo mã chuyển khoản duy nhất.</div>
        </div>

        <!-- 2. Trạng thái Lỗi -->
        <div id="modalQrError" class="alert alert-danger py-4 text-center d-none" style="border-radius: 14px;">
          <div style="font-size: 32px; margin-bottom: 8px;">⚠️</div>
          <div class="fw-bold mb-2" id="modalQrErrorMessage" style="font-size: 15px;">Có lỗi xảy ra</div>
          <button type="button" class="btn btn-outline-danger btn-sm mt-2 px-3 fw-bold" onclick="retryLoadQr()">Thử lại</button>
        </div>

        <!-- 3. Nội dung mã QR và Thông tin chuyển khoản -->
        <div id="modalQrContent" class="d-none">
          <div class="row g-4 align-items-center">
            
            <!-- Cột trái: Ảnh QR Code -->
            <div class="col-md-5 text-center">
              <div class="p-3 bg-white border rounded-4 shadow-sm d-inline-block w-100" style="max-width: 290px;">
                <div class="mb-2 fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Quét qua app ngân hàng</div>
                <div class="position-relative d-inline-block">
                  <img id="modalQrImg" src="" alt="Mã QR SePay" class="img-fluid rounded-3" style="width: 100%; max-width: 240px; min-height: 240px; object-fit: contain; background: #fdfdfd;">
                </div>
                <div class="mt-2 text-muted" style="font-size: 11px;">Hỗ trợ tất cả ngân hàng &amp; ví điện tử</div>
              </div>
            </div>

            <!-- Cột phải: Thông tin tài khoản & Nội dung -->
            <div class="col-md-7">
              <div class="bg-white p-3 p-sm-4 rounded-4 border shadow-sm">
                
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                  <span class="text-muted" style="font-size: 13px;">Ngân hàng thụ hưởng:</span>
                  <span class="fw-bold text-dark" id="modalBankNameText">MSB</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                  <span class="text-muted" style="font-size: 13px;">Chủ tài khoản:</span>
                  <span class="fw-bold text-dark" id="modalAccountNameText">SEPAY TEST</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                  <div>
                    <span class="text-muted d-block" style="font-size: 12px;">Số tài khoản:</span>
                    <strong class="font-monospace fs-5 text-primary" id="modalAccountNumText">0000000001</strong>
                  </div>
                  <button type="button" class="btn btn-sm btn-light border px-2 py-1 fw-bold text-muted" onclick="copyModalField('modalAccountNumText', 'Đã chép số tài khoản!')" title="Sao chép">
                    📋 Chép
                  </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                  <div>
                    <span class="text-muted d-block" style="font-size: 12px;">Số tiền:</span>
                    <strong class="fs-5" style="color: #20584f;" id="modalAmountText">0 đ</strong>
                  </div>
                  <button type="button" class="btn btn-sm btn-light border px-2 py-1 fw-bold text-muted" id="btnCopyAmount" onclick="copyRawAmount()" title="Sao chép">
                    📋 Chép
                  </button>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: #fff8e8; border: 1px dashed #e8b931;">
                  <div>
                    <span class="text-muted d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 700;">Nội dung chuyển khoản (bắt buộc):</span>
                    <strong class="font-monospace fs-5 text-danger" id="modalPaymentCodeText">TROOI...</strong>
                  </div>
                  <button type="button" class="btn btn-sm btn-warning fw-bold text-dark px-3 py-1" onclick="copyModalField('modalPaymentCodeText', 'Đã chép nội dung!')">
                    📋 Chép
                  </button>
                </div>

                <div class="mt-2 text-danger" style="font-size: 11px; line-height: 1.4;">
                  ⚠️ <strong>Quan trọng:</strong> Vui lòng giữ <strong>chính xác nội dung chuyển khoản</strong> ở trên để hệ thống tự động gạch nợ trong 3-10 giây.
                </div>

              </div>
            </div>

          </div>

          <!-- Trạng thái lắng nghe giao dịch thời gian thực -->
          <div id="modalPollingWaiting" class="mt-3 p-3 rounded-4 bg-white border text-center d-flex align-items-center justify-content-center gap-2" style="font-size: 13px; color: #536561;">
            <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
            <span>Đang chờ bạn chuyển khoản... Hệ thống sẽ tự động gạch nợ ngay khi nhận tiền.</span>
          </div>

          <!-- Trạng thái Thanh toán thành công -->
          <div id="modalPollingSuccess" class="mt-3 p-4 rounded-4 bg-success text-white text-center d-none shadow">
            <div style="font-size: 38px; line-height: 1;">🎉</div>
            <h5 class="fw-bold mt-2 mb-1">THANH TOÁN THÀNH CÔNG!</h5>
            <div style="font-size: 13px;" class="opacity-90">Hóa đơn đã được ghi nhận thanh toán thành công qua SePay QR. Trang sẽ tự tải lại...</div>
          </div>

        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer d-flex justify-content-between align-items-center" style="background: #f1ede3; border-top: 1px solid #e6dcc2; padding: 14px 24px;">
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-warning btn-sm px-3 fw-bold shadow-sm" id="btnSimulateTest" onclick="simulateTestPayment()" title="Mô phỏng SePay bắn webhook tiền vào">
            ⚡ Giả lập thanh toán ngay (Demo)
          </button>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-success btn-sm px-3 fw-bold" id="btnManualCheckStatus" onclick="manualCheckStatus()">
            🔄 Kiểm tra lại
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Toast thông báo sao chép --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
  <div id="sepayToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-bold" id="sepayToastMessage">
        Đã sao chép vào bộ nhớ tạm!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
    </div>
  </div>
</div>

