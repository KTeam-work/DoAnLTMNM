@php
    $currentUser = $user ?? auth()->user();
    $currentRole = $currentUser?->role ?? 'tenant';

    $layout = match($currentRole) {
        'owner' => 'Layout.landlord',
        'admin' => 'Layout.admin',
        default => 'Layout.tenant',
    };

    $roleLabel = match($currentRole) {
        'owner' => 'Chủ trọ',
        'admin' => 'Quản trị viên',
        default => 'Người thuê / Tìm phòng',
    };

    $roleIcon = match($currentRole) {
        'owner' => 'fa-building-user',
        'admin' => 'fa-shield-halved',
        default => 'fa-house-user',
    };

    $roleBadgeClass = match($currentRole) {
        'owner' => 'owner',
        'admin' => 'admin',
        default => 'tenant',
    };

    $dashboardRoute = match($currentRole) {
        'owner' => route('landlord.dashboard'),
        'admin' => route('admin.dashboard'),
        default => route('tenant.dashboard'),
    };

    // Danh sách câu hỏi gợi ý nhanh theo vai trò
    $suggestions = match($currentRole) {
        'owner' => [
            'Báo cáo tổng quan khu trọ tháng này',
            'Tôi còn bao nhiêu phòng trống?',
            'Có những hóa đơn nào chưa thanh toán?',
            'Tỷ lệ lấp đầy phòng trọ hiện tại',
            'Doanh thu tháng này của tôi là bao nhiêu?',
        ],
        'admin' => [
            'Thống kê người dùng toàn hệ thống',
            'Tổng số phòng và tỷ lệ phòng trống',
            'Tổng số khu trọ đang hoạt động',
            'Có bao nhiêu chủ trọ và người thuê?',
        ],
        default => [
            'Có những phòng nào đang còn trống?',
            'Tìm phòng trọ giá dưới 3 triệu',
            'Phòng nào ở được 3 người?',
            'Phòng 12A còn trống không, giá bao nhiêu?',
            'Hợp đồng thuê của tôi còn hạn bao lâu?',
            'Tháng này tôi cần thanh toán bao nhiêu?',
        ]
    };
@endphp

@extends($layout)

@section('title', 'Trợ lý AI Trọ Ơi | Hỗ trợ 24/7')

@push('styles')
    {{-- FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- CSS riêng của Chatbot --}}
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
@endpush

@section('content')
<div class="chatbot-page">
    <div class="chatbot-container">
        <div class="chatbot-card">

            {{-- =========================================================
                 CỘT TRÁI: SIDEBAR THÔNG TIN & GỢI Ý (DESKTOP)
            ========================================================== --}}
            <aside class="chatbot-sidebar">
                {{-- Thông tin Trợ lý AI --}}
                <div class="bot-brand">
                    <div class="bot-avatar-glow">
                        <div class="pulse-ring"></div>
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="bot-brand-info">
                        <h4>Trọ Ơi AI <i class="fa-solid fa-sparkles" style="color: #f5c84b; font-size: 13px;"></i></h4>
                        <div class="bot-status-tag">
                            <span class="status-dot"></span> Đang trực tuyến
                        </div>
                    </div>
                </div>

                {{-- Thẻ danh tính người dùng --}}
                <div class="user-identity-card">
                    <div class="user-avatar-mini">
                        {{ strtoupper(mb_substr($currentUser?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-identity-meta">
                        <div class="user-identity-name" title="{{ $currentUser?->name }}">
                            {{ $currentUser?->name ?? 'Người dùng' }}
                        </div>
                        <span class="role-badge {{ $roleBadgeClass }}">
                            <i class="fa-solid {{ $roleIcon }}"></i> {{ $roleLabel }}
                        </span>
                    </div>
                </div>

                {{-- Khối câu hỏi gợi ý thông minh --}}
                <div class="suggestions-box">
                    <div class="suggestions-title">
                        <i class="fa-regular fa-lightbulb"></i> Gợi ý câu hỏi nhanh
                    </div>
                    <div class="suggestion-list">
                        @foreach($suggestions as $item)
                            <button type="button" class="suggestion-btn" onclick="sendQuickPrompt('{{ addslashes($item) }}')">
                                <span>{{ $item }}</span>
                                <i class="fa-solid fa-chevron-right arrow"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tiện ích chân sidebar --}}
                <div class="sidebar-footer">
                    <button type="button" class="btn-sidebar-action" id="sidebarClearBtn" onclick="confirmClearHistory()">
                        <i class="fa-regular fa-trash-can"></i> Xóa lịch sử trò chuyện
                    </button>
                    <a href="{{ $dashboardRoute }}" class="btn-sidebar-action">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại trang chính
                    </a>
                </div>
            </aside>

            {{-- =========================================================
                 CỘT PHẢI: KHUNG TRÒ CHUYỆN CHÍNH
            ========================================================== --}}
            <section class="chatbot-main">
                {{-- Header của Khung Chat --}}
                <div class="chat-main-header">
                    <div class="chat-header-title">
                        <div>
                            <h3>Trợ lý Trọ Ơi AI</h3>
                            <p>Kết nối trực tiếp dữ liệu MySQL thực tế &bull; Sẵn sàng giải đáp 24/7</p>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        <button type="button" class="btn-clear-chat" onclick="confirmClearHistory()" title="Bắt đầu cuộc trò chuyện mới">
                            <i class="fa-regular fa-trash-can"></i> Xóa đoạn chat
                        </button>
                    </div>
                </div>

                {{-- Khu vực hiển thị tin nhắn --}}
                <div class="chat-messages" id="chatBox">
                    {{-- Thẻ chào mừng mặc định --}}
                    <div class="welcome-card" id="welcomeCard">
                        <div class="welcome-header">
                            <div class="bot-avatar-glow" style="width: 42px; height: 42px; font-size: 20px;">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <div>
                                <h5>Xin chào {{ $currentUser?->name ?? 'bạn' }}! 👋</h5>
                                <div style="font-size: 13px; color: var(--muted, #78837e);">
                                    Tôi là trợ lý AI thông minh của <strong>Trọ Ơi</strong>. Tôi có thể hỗ trợ bạn những gì hôm nay?
                                </div>
                            </div>
                        </div>

                        <p style="font-size: 13.5px; color: #475569; margin: 0 0 10px;">
                            Bạn có thể hỏi tôi về thông tin phòng trọ, giá thuê, diện tích, tiện ích, hóa đơn điện nước hoặc tình trạng phòng đang hoạt động.
                        </p>

                        <div class="welcome-chips">
                            @foreach(array_slice($suggestions, 0, 4) as $chip)
                                <button type="button" class="welcome-chip-btn" onclick="sendQuickPrompt('{{ addslashes($chip) }}')">
                                    ✨ {{ $chip }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Typing Indicator (Hiển thị khi AI đang suy nghĩ) --}}
                    <div class="chat-msg-row bot typing-indicator-row" id="typingIndicator">
                        <div class="msg-avatar">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="msg-content-wrap">
                            <div class="typing-bubble">
                                <div class="typing-dots">
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                </div>
                                <span class="typing-text">Trọ Ơi AI đang soạn câu trả lời...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thanh cuộn ngang các câu hỏi gợi ý nhanh (Nổi ngay trên thanh nhập) --}}
                <div class="quick-chips-bar">
                    @foreach($suggestions as $chip)
                        <button type="button" class="chip-pill" onclick="sendQuickPrompt('{{ addslashes($chip) }}')">
                            💡 {{ $chip }}
                        </button>
                    @endforeach
                </div>

                {{-- Khung nhập tin nhắn --}}
                <div class="chat-input-area">
                    <form id="chatForm" class="chat-input-form" onsubmit="handleFormSubmit(event)">
                        <textarea
                            id="messageInput"
                            class="chat-textarea"
                            placeholder="Nhập câu hỏi của bạn tại đây... (Nhấn Enter để gửi, Shift+Enter xuống dòng)"
                            rows="1"
                            required
                        ></textarea>

                        <button type="submit" id="sendBtn" class="btn-send-msg" title="Gửi tin nhắn">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>

                    <div class="chat-disclaimer">
                        <span><i class="fa-solid fa-shield-halved"></i> Dữ liệu phòng thực tế từ MySQL &bull; Phân quyền an toàn theo tài khoản</span>
                        <span>Nhấn <strong>Enter</strong> để gửi</span>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');
    const welcomeCard = document.getElementById('welcomeCard');

    const STORAGE_KEY = 'tro_oi_chat_history_{{ $currentRole }}_{{ $currentUser?->id ?? "guest" }}';

    // Tự động co giãn chiều cao của Textarea khi nhập nhiều dòng
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        if (this.scrollHeight > 120) {
            this.style.overflowY = 'auto';
        } else {
            this.style.overflowY = 'hidden';
        }
    });

    // Bắt sự kiện phím Enter để gửi tin nhắn (Shift+Enter để xuống dòng)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chatForm').dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });

    // Cuộn xuống cuối cùng của khung chat
    function scrollToBottom() {
        setTimeout(() => {
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 50);
    }

    // Định dạng thời gian hiện tại HH:MM
    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    // Bộ phân giải Markdown nhẹ & An toàn tuyệt đối chống XSS
    function formatMarkdown(rawText) {
        if (!rawText) return '';

        // 1. Thoát các ký tự HTML nguy hiểm
        let text = rawText
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        // 2. Chuyển đổi Headers
        text = text.replace(/^### (.*$)/gim, '<h6>$1</h6>');
        text = text.replace(/^## (.*$)/gim, '<h5>$1</h5>');
        text = text.replace(/^# (.*$)/gim, '<h4>$1</h4>');

        // 3. Chuyển đổi Bold (**text**) và Italic (*text*)
        text = text.replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>');
        text = text.replace(/\*(.*?)\*/gim, '<em>$1</em>');

        // 4. Đường phân cách ngang (---)
        text = text.replace(/^---+$/gim, '<hr style="border:0; border-top:1px solid rgba(32,88,79,0.15); margin:12px 0;">');

        // 5. Danh sách gạch đầu dòng (* hoặc -)
        text = text.replace(/^\s*[\*\-]\s+(.*$)/gim, '<li>$1</li>');
        text = text.replace(/(<li>.*<\/li>)/gim, '<ul>$1</ul>');
        text = text.replace(/<\/ul>\s*<ul>/gim, '');

        // 6. Chuyển đổi xuống dòng
        text = text.replace(/\n/gim, '<br>');

        return text;
    }

    // Thêm bong bóng tin nhắn vào giao diện
    function appendMessage(role, content, time = null, save = true) {
        const timeStr = time || getCurrentTime();

        // Ẩn welcome card khi đã có tin nhắn
        if (welcomeCard) {
            welcomeCard.style.display = 'none';
        }

        const row = document.createElement('div');
        row.className = `chat-msg-row ${role}`;

        const avatarIcon = role === 'user' ? '{{ strtoupper(mb_substr($currentUser?->name ?? "U", 0, 1)) }}' : '<i class="fa-solid fa-robot"></i>';

        const bubbleContent = role === 'bot' ? formatMarkdown(content) : document.createTextNode(content).textContent;

        row.innerHTML = `
            <div class="msg-avatar">${avatarIcon}</div>
            <div class="msg-content-wrap">
                <div class="msg-bubble">${bubbleContent}</div>
                <div class="msg-time">${timeStr}</div>
            </div>
        `;

        // Chèn trước typing indicator
        chatBox.insertBefore(row, typingIndicator);
        scrollToBottom();

        if (save) {
            saveMessageToHistory(role, content, timeStr);
        }
    }

    // Lưu vào LocalStorage
    function saveMessageToHistory(role, content, time) {
        try {
            const history = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            history.push({ role, content, time });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
        } catch (e) {
            console.error('Không thể lưu lịch sử chat:', e);
        }
    }

    // Tải lại lịch sử từ LocalStorage
    function loadHistory() {
        try {
            const history = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            if (history.length > 0) {
                if (welcomeCard) welcomeCard.style.display = 'none';
                history.forEach(item => {
                    appendMessage(item.role, item.content, item.time, false);
                });
            }
        } catch (e) {
            console.error('Không thể tải lịch sử chat:', e);
        }
    }

    // Xóa lịch sử trò chuyện
    function confirmClearHistory() {
        if (confirm('Bạn có chắc chắn muốn xóa toàn bộ đoạn hội thoại này để bắt đầu lại không?')) {
            localStorage.removeItem(STORAGE_KEY);
            // Xóa tất cả tin nhắn trừ typing indicator và welcome card
            const rows = document.querySelectorAll('.chat-msg-row:not(.typing-indicator-row)');
            rows.forEach(r => r.remove());
            if (welcomeCard) welcomeCard.style.display = 'block';
            scrollToBottom();
        }
    }

    // Gửi câu hỏi nhanh từ chip hoặc sidebar
    function sendQuickPrompt(promptText) {
        messageInput.value = promptText;
        document.getElementById('chatForm').dispatchEvent(new Event('submit', { cancelable: true }));
    }

    // Xử lý gửi tin nhắn
    async function handleFormSubmit(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        // Hiển thị tin nhắn của User
        appendMessage('user', message);

        // Reset input
        messageInput.value = '';
        messageInput.style.height = 'auto';

        // Khóa input và bật typing indicator
        messageInput.disabled = true;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        typingIndicator.style.display = 'flex';
        scrollToBottom();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                || '{{ csrf_token() }}';

            const response = await fetch("{{ route('chatbot.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            // Tắt typing indicator
            typingIndicator.style.display = 'none';

            if (data.success && data.reply) {
                appendMessage('bot', data.reply);
            } else {
                appendMessage('bot', data.message || '⚠️ Hệ thống hiện đang bảo trì hoặc gặp sự cố kết nối. Vui lòng thử lại sau.');
            }

        } catch (err) {
            console.error('Chatbot API Error:', err);
            typingIndicator.style.display = 'none';
            appendMessage('bot', '⚠️ Không thể kết nối với máy chủ. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau giây lát.');
        } finally {
            // Mở khóa input
            messageInput.disabled = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
            messageInput.focus();
            scrollToBottom();
        }
    }

    // Khởi chạy khi load trang
    document.addEventListener('DOMContentLoaded', function() {
        loadHistory();
        messageInput.focus();
    });
</script>
@endpush
