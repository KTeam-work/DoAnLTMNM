import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const config = window.chatConfig;
const box = document.getElementById('chat-messages');
const status = document.getElementById('chat-status');
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const headers = {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf};
if (box) box.scrollTop = box.scrollHeight;
function append(message) {
    if (!box || box.querySelector(`[data-id="${message.id}"]`)) return;
    const bubble = document.createElement('div');
    bubble.className = 'bubble' + (message.sender_id === config.userId ? ' mine' : '');
    bubble.dataset.id = message.id;
    const body = document.createElement('span'); body.textContent = message.body;
    const time = document.createElement('time'); time.textContent = new Date(message.created_at).toLocaleString('vi-VN', {hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric'});
    bubble.append(body, time); box.append(bubble); box.scrollTop = box.scrollHeight;
}
if (config.key) {
    window.Pusher = Pusher;
    const echo = new Echo({broadcaster: 'reverb', key: config.key, wsHost: config.host || location.hostname, wsPort: config.port || 8080, wssPort: config.port || 443, forceTLS: config.scheme === 'https', enabledTransports: ['ws', 'wss']});
    echo.connector.pusher.connection.bind('connected', () => { if (status) status.textContent = 'Đã kết nối'; });
    echo.connector.pusher.connection.bind('unavailable', () => { if (status) status.textContent = 'Mất kết nối. Hãy tải lại trang để xem tin mới.'; });
    echo.private(`chat-user.${config.userId}`).listen('.chat.message', message => {
        if (message.conversation_id === config.conversationId) {
            append(message);
            if (message.sender_id !== config.userId) fetch(config.readUrl, {method: 'POST', headers});
        } else if (message.sender_id !== config.userId) {
            const notice = document.createElement('div'); notice.className = 'alert alert-success m-2';
            notice.textContent = 'Bạn có tin nhắn mới. Tải lại trang để xem.';
            document.querySelector('.chat-list').prepend(notice);
        }
    });
} else if (status) status.textContent = 'Chưa cấu hình kết nối chat.';
document.getElementById('chat-form')?.addEventListener('submit', async event => {
    event.preventDefault();
    const input = document.getElementById('chat-input');
    const body = input.value.trim(); if (!body) return;
    const button = event.currentTarget.querySelector('button'); button.disabled = true;
    try {
        const response = await fetch(config.sendUrl, {method: 'POST', headers, body: JSON.stringify({body})});
        if (!response.ok) throw new Error();
        append(await response.json()); input.value = ''; input.focus();
    } catch { if (status) status.textContent = 'Không gửi được tin nhắn. Vui lòng thử lại.'; }
    finally { button.disabled = false; }
});
