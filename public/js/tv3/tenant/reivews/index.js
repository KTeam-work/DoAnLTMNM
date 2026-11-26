(() => {
  if (window.tenantReviewSubmitInitialized) return;
  window.tenantReviewSubmitInitialized = true;

  function createReviewCard(review) {
    const card = document.createElement('div');
    card.className = 'review-card';
    card.dataset.reviewId = String(review.id);
    card.innerHTML = '<div class="d-flex justify-content-between align-items-start mb-2"><div><div class="static-stars"></div><div class="fw-bold mt-1 text-dark review-room-name"></div></div><div class="text-end"><span class="badge-visible mb-1 d-inline-block">Đang hiển thị</span><div class="text-muted review-date"></div></div></div>';
    card.querySelector('.static-stars').textContent = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
    card.querySelector('.review-room-name').textContent = review.room_name || '';
    card.querySelector('.review-date').textContent = review.created_at || '';
    if (review.comment) {
      const comment = document.createElement('p');
      comment.className = 'text-muted mb-0 mt-2 review-comment';
      comment.textContent = `“${review.comment}”`;
      card.appendChild(comment);
    }
    return card;
  }

  document.addEventListener('submit', async event => {
    const form = event.target;
    if (form.id !== 'review-form') return;
    event.preventDefault();
    const button = form.querySelector('button[type="submit"]');
    const feedback = form.querySelector('#review-feedback');
    button.disabled = true;
    button.textContent = 'Đang gửi...';
    feedback.textContent = '';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new FormData(form),
      });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Không thể gửi đánh giá.');
      if (!form.isConnected) return;
      const list = document.getElementById('review-list');
      const oldCard = Array.from(list.querySelectorAll('[data-review-id]')).find(card => card.dataset.reviewId === String(data.review.id));
      const card = createReviewCard(data.review);
      if (oldCard) oldCard.replaceWith(card);
      else list.prepend(card);
      document.getElementById('no-review-text')?.remove();
      document.getElementById('review-count').textContent = `Tổng: ${data.total} lượt`;
      feedback.textContent = data.created ? 'Đã gửi đánh giá.' : 'Đã cập nhật đánh giá.';
      feedback.className = 'small mt-2 text-success';
      form.reset();
    } catch (error) {
      if (form.isConnected) {
        feedback.textContent = error.message || 'Không thể gửi đánh giá. Vui lòng thử lại.';
        feedback.className = 'small mt-2 text-danger';
      }
    } finally {
      button.disabled = false;
      button.textContent = 'Gửi đánh giá';
    }
  });
})();
