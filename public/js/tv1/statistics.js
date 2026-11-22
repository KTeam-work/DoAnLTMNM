/**
 * Trọ Ơi - Admin Advanced Analytics Scripts
 * Handles Hover / Click Interactive Column Ratio Display & CSV Export
 */
function initAdminStatistics() {
    const barGroups = document.querySelectorAll('.stat-bar-group.clickable-bar');
    const monthTitle = document.getElementById('detailMonthTitle');
    const postCount = document.getElementById('detailPostCount');
    const postBar = document.getElementById('detailPostBar');
    const userCount = document.getElementById('detailUserCount');
    const userBar = document.getElementById('detailUserBar');

    function updateDetail(bar) {
        barGroups.forEach((b) => b.classList.remove('active'));
        bar.classList.add('active');

        const month = bar.dataset.month || '';
        const posts = bar.dataset.posts || '0';
        const users = bar.dataset.users || '0';
        const postPct = bar.dataset.postPct || '0';
        const userPct = bar.dataset.userPct || '0';

        if (monthTitle) monthTitle.innerText = `Chi tiết ${month}`;
        if (postCount) postCount.innerText = `${posts} tin (${postPct}%)`;
        if (postBar) postBar.style.width = `${postPct}%`;
        if (userCount) userCount.innerText = `${users} người (${userPct}%)`;
        if (userBar) userBar.style.width = `${userPct}%`;
    }

    barGroups.forEach((bar) => {
        // Rờ chuột (hover / mouseenter) là tự động hiện tỷ lệ ngay lập tức
        bar.addEventListener('mouseenter', () => updateDetail(bar));
        bar.addEventListener('click', () => updateDetail(bar));
    });

    // Xuất dữ liệu CSV
    const exportButton = document.getElementById('exportStatistics');
    if (exportButton && !exportButton.dataset.bound) {
        exportButton.dataset.bound = 'true';
        exportButton.addEventListener('click', () => {
            const rows = [
                ['BÁO CÁO THỐNG KÊ GIÁM SÁT HỆ THỐNG TRỌ ƠI', '', ''],
                ['Thời gian xuất', new Date().toLocaleString('vi-VN'), ''],
                ['', '', ''],
                ['Nhóm chỉ số', 'Chỉ tiêu', 'Số lượng']
            ];

            rows.push(['Tin đăng', 'Đã duyệt & Đang hiển thị', document.querySelector('.stat-giant-num')?.innerText?.trim() || '0']);
            rows.push(['Chủ trọ', 'Tài khoản chủ trọ', document.querySelector('.card-navy .stat-class-count')?.innerText?.trim() || '0']);
            rows.push(['Khách thuê', 'Tài khoản khách thuê', document.querySelector('.card-blue .stat-class-count')?.innerText?.trim() || '0']);
            rows.push(['Phòng trọ', 'Tổng số phòng trọ', document.querySelector('.card-teal .stat-class-count')?.innerText?.trim() || '0']);
            rows.push(['Hợp đồng', 'Hợp đồng hiệu lực', document.querySelector('.card-sky .stat-class-count')?.innerText?.trim() || '0']);

            rows.push(['', '', '']);
            rows.push(['TĂNG TRƯỞNG THEO 12 THÁNG', '', '']);
            rows.push(['Tháng', 'Tin đăng', 'Người dùng']);

            barGroups.forEach((b) => {
                rows.push([b.dataset.month || '', b.dataset.posts || '0', b.dataset.users || '0']);
            });

            const escapeCsv = (value) => `"${String(value ?? '').replaceAll('"', '""')}"`;
            const csvContent = '\uFEFF' + rows.map((row) => row.map(escapeCsv).join(',')).join('\r\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `bao-cao-thong-ke-tro-oi-${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        });
    }
}

// Chạy ngay lập tức và khi DOM sẵn sàng
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAdminStatistics);
} else {
    initAdminStatistics();
}
document.addEventListener('ajax:page-loaded', initAdminStatistics);
