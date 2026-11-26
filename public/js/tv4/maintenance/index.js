(() => {
  if (window.ownerMaintenanceActionsInitialized) return;
  window.ownerMaintenanceActionsInitialized = true;

  // Delegation works for both direct loads and pages inserted by AJAX.
  document.addEventListener('click', event => {
    const start = event.target.closest('.btn-start-repair');
    const reject = event.target.closest('.btn-reject-repair');
    if (!start && !reject) return;

    const button = start || reject;
    const modal = document.getElementById(start ? 'startRepairModal' : 'rejectRepairModal');
    const form = document.getElementById(start ? 'startRepairForm' : 'rejectRepairForm');
    if (!modal || !form || !window.bootstrap) return;

    event.preventDefault();
    form.action = button.dataset.action;
    if (start) {
      document.getElementById('modalTicketCode').textContent = '#TCK-' + (button.dataset.ticketId || '');
      document.getElementById('modalTicketTitle').textContent = button.dataset.ticketTitle || '';
      document.getElementById('modalTicketRoom').textContent = (button.dataset.ticketRoom || '—') + ' · ' + (button.dataset.ticketTenant || '—');
    }
    bootstrap.Modal.getOrCreateInstance(modal).show();
  });

  function initMaintenanceTooltips() {
    if (!document.getElementById('startRepairModal') || !window.bootstrap) return;
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el));
  }

  document.addEventListener('DOMContentLoaded', initMaintenanceTooltips);
  initMaintenanceTooltips();
  setTimeout(() => document.body.classList.remove('skeleton-mode'), 500);
})();
