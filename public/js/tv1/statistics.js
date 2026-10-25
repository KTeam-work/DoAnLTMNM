
document.addEventListener('DOMContentLoaded', () => {
    const exportButton = document.getElementById('exportStatistics');

    if (!exportButton) return;

    exportButton.addEventListener('click', () => {
        const rows = [
            ['Nhóm thống kê', 'Tên', 'Số lượng'],
        ];

        document.querySelectorAll('#roleStatistics .st-bar-row').forEach((item) => {
            rows.push(['Người dùng', item.dataset.label, item.dataset.value]);
        });

        document.querySelectorAll('#postStatistics .st-bar-row').forEach((item) => {
            rows.push(['Tin đăng', item.dataset.label, item.dataset.value]);
        });

        const escapeCsv = (value) =>
            `"${String(value ?? '').replaceAll('"', '""')}"`;

        const csv = '\uFEFF' + rows
            .map((row) => row.map(escapeCsv).join(','))
            .join('\r\n');

        const url = URL.createObjectURL(
            new Blob([csv], { type: 'text/csv;charset=utf-8;' })
        );

        const link = document.createElement('a');
        link.href = url;
        link.download = 'thong-ke-tro-oi.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    });
});
