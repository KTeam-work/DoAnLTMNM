
(function () {
  var contractSelect = document.getElementById('contractSelect');
  var roomName = document.getElementById('roomName');
  function syncRoom() {
    if (!contractSelect) return;
    var opt = contractSelect.options[contractSelect.selectedIndex];
    roomName.textContent = opt ? (opt.getAttribute('data-room') || '—') : '—';
  }
  if (contractSelect) { contractSelect.addEventListener('change', syncRoom); syncRoom(); }
  var zone = document.getElementById('uploadZone');
  var input = document.getElementById('imageInput');
  var hint = document.getElementById('uploadHint');
  if (zone && input) {
    zone.addEventListener('click', function () { input.click(); });
    input.addEventListener('change', function () {
      if (input.files.length) hint.textContent = 'Đã chọn: ' + input.files[0].name;
    });
  }
})();