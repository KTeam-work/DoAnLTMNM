
(function () {
    var select = document.getElementById('contractSelect');
    var form = document.getElementById('memberForm');
    if (!select || !form) return;
    function sync() {
        var opt = select.options[select.selectedIndex];
        form.action = "{{ url('/owner/contracts') }}/" + opt.value + "/members";
        document.getElementById('contractIdHidden').value = opt.value;
        document.getElementById('infoCode').textContent = '#' + (opt.getAttribute('data-code') || '');
        document.getElementById('infoTenant').textContent = opt.getAttribute('data-tenant') || '—';
        document.getElementById('infoRoom').textContent = opt.getAttribute('data-room') || '—';
        document.getElementById('infoCount').textContent =
            (1 + parseInt(opt.getAttribute('data-count') || '0', 10)) + ' / ' + (opt.getAttribute('data-max') || '?') + ' người';
    }
    select.addEventListener('change', sync);
    sync();
})();

