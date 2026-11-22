setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);

document.addEventListener("DOMContentLoaded", function() {
  setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

  // Tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

  // Modal Bắt đầu sửa chữa
  const startModalEl = document.getElementById('startRepairModal');
  const startForm = document.getElementById('startRepairForm');
  const modalTicketCode = document.getElementById('modalTicketCode');
  const modalTicketTitle = document.getElementById('modalTicketTitle');
  const modalTicketRoom = document.getElementById('modalTicketRoom');
  let startModalInstance = null;

  if (startModalEl && window.bootstrap) {
    startModalInstance = new bootstrap.Modal(startModalEl);
  }

  document.querySelectorAll('.btn-start-repair').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const ticketId = this.dataset.ticketId || '';
      const ticketTitle = this.dataset.ticketTitle || '';
      const room = this.dataset.ticketRoom || '—';
      const tenant = this.dataset.ticketTenant || '—';
      const action = this.dataset.action || '';

      if (modalTicketCode) modalTicketCode.textContent = '#TCK-' + ticketId;
      if (modalTicketTitle) modalTicketTitle.textContent = ticketTitle;
      if (modalTicketRoom) modalTicketRoom.textContent = room + ' · ' + tenant;
      if (startForm) startForm.action = action;

      if (startModalInstance) {
        startModalInstance.show();
      }
    });
  });

  // Modal Từ chối yêu cầu
  const rejectModalEl = document.getElementById('rejectRepairModal');
  const rejectForm = document.getElementById('rejectRepairForm');
  let rejectModalInstance = null;

  if (rejectModalEl && window.bootstrap) {
    rejectModalInstance = new bootstrap.Modal(rejectModalEl);
  }

  document.querySelectorAll('.btn-reject-repair').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const action = this.dataset.action || '';
      if (rejectForm) rejectForm.action = action;

      if (rejectModalInstance) {
        rejectModalInstance.show();
      }
    });
  });
});
