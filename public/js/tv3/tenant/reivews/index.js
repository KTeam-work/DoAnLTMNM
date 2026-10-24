  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 600); 
  });
  
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('review-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Chặn load lại trang

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Đang gửi...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            submitBtn.disabled = false;
            submitBtn.innerText = '🚀 Gửi Đánh Giá';

            // Chấp nhận status 200 hoặc 201 (vì Controller trả về 201 Created)
            if ((status === 200 || status === 201) && body.success) {
                // 1. Reset form
                form.reset();

                // 2. Xóa thông báo "Bạn chưa có đánh giá nào" nếu có
                const noReviewText = document.getElementById('no-review-text');
                if (noReviewText) {
                    noReviewText.remove();
                }

                // 3. Lấy dữ liệu review từ JSON trả về và tự vẽ HTML
                const r = body.review;
                const stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);

                const newCardHtml = `
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="static-stars">${stars}</div>
                                <div class="fw-bold mt-1 text-dark" style="font-size: 15px;">${r.room_name}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge-visible mb-1 d-inline-block">✓ Đang hiển thị</span>
                                <div class="text-muted" style="font-size: 11px;">${r.created_at}</div>
                            </div>
                        </div>
                        ${r.comment ? `<p class="text-muted mb-0 mt-2" style="font-size: 13.5px; line-height: 1.6;">"${r.comment}"</p>` : ''}
                    </div>
                `;

                // 4. Chèn review mới lên đầu danh sách
                const reviewList = document.getElementById('review-list');
                reviewList.insertAdjacentHTML('afterbegin', newCardHtml);

                // 5. Cập nhật lại tổng số lượng lượt đánh giá
                const countSpan = document.getElementById('review-count');
                if (countSpan) {
                    let currentCountText = countSpan.innerText.replace(/\D/g, '');
                    let newCount = parseInt(currentCountText || 0) + 1;
                    countSpan.innerText = `Tổng: ${newCount} lượt`;
                }

                alert(body.message);
            } else {
                alert(body.message || 'Có lỗi xảy ra, vui lòng kiểm tra lại.');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerText = '🚀 Gửi Đánh Giá';
            console.error('Lỗi AJAX:', error);
        });
    });
});