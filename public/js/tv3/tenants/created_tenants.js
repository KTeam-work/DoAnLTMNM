
(function () {
    var select = document.getElementById('contractSelect');
    var form = document.getElementById('memberForm');
    if (!select || !form) return;
    function sync() {
        var opt = select.options[select.selectedIndex];
        if (!opt) return;
        form.action = "/owner/contracts/" + opt.value + "/members";
        document.getElementById('contractIdHidden').value = opt.value;
        document.getElementById('infoCode').textContent = '#' + (opt.getAttribute('data-code') || '');
        document.getElementById('infoTenant').textContent = opt.getAttribute('data-tenant') || '—';
        document.getElementById('infoRoom').textContent = opt.getAttribute('data-room') || '—';
        document.getElementById('infoCount').textContent =
            (1 + parseInt(opt.getAttribute('data-count') || '0', 10)) + ' / ' + (opt.getAttribute('data-max') || '?') + ' người';
    }
    select.addEventListener('change', sync);
    sync();

    // Ràng buộc kiểm tra form thêm người ở cùng (CCCD 9 hoặc 12 số, SĐT 10 số bắt đầu bằng 0)
    form.addEventListener('submit', function (e) {
        var cccdInput = form.querySelector('input[name="identity_card"]');
        var phoneInput = form.querySelector('input[name="phone"]');

        if (cccdInput) {
            var cccdVal = cccdInput.value.trim();
            if (!/^(?:[0-9]{9}|[0-9]{12})$/.test(cccdVal)) {
                e.preventDefault();
                alert('Số CCCD không hợp lệ! Vui lòng nhập đúng 12 chữ số (hoặc CMND 9 chữ số), không chứa chữ cái, số âm hay ký tự đặc biệt.');
                cccdInput.focus();
                return false;
            }
        }

        if (phoneInput && phoneInput.value.trim() !== '') {
            var phoneVal = phoneInput.value.trim();
            if (!/^0[0-9]{9}$/.test(phoneVal)) {
                e.preventDefault();
                alert('Số điện thoại không hợp lệ! Vui lòng nhập đúng 10 chữ số, bắt đầu bằng số 0 và không chứa số âm.');
                phoneInput.focus();
                return false;
            }
        }
    });
})();

