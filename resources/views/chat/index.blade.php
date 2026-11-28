@extends($user->role === 'owner' ? 'Layout.landlord' : 'Layout.tenant')
@section('title', 'Tin nhắn | Trọ Ơi')
@push('styles')
<style>
.chat-wrap{max-width:1150px;margin:32px auto 60px;padding:0 18px}.chat-shell{display:grid;grid-template-columns:310px 1fr;min-height:600px;background:#fff;border:1px solid #dfe7e3;border-radius:18px;overflow:hidden;box-shadow:0 8px 28px #1232}.chat-list{border-right:1px solid #e2eae5}.chat-list h2,.chat-head{padding:20px;margin:0;border-bottom:1px solid #e2eae5;font-size:18px}.chat-link{display:block;padding:16px 20px;color:#243b30;text-decoration:none;border-bottom:1px solid #eef2ef}.chat-link:hover,.chat-link.active{background:#eaf7ef}.chat-link small{display:block;color:#64766a}.chat-badge{float:right;background:#16834d;color:white;border-radius:20px;padding:2px 9px}.chat-main{display:flex;flex-direction:column;min-width:0}.chat-head small{display:block;color:#63776a;font-weight:400;margin-top:4px}.chat-messages{flex:1;padding:22px;background:#f7faf8;overflow:auto;max-height:520px;min-height:400px}.bubble{max-width:75%;width:fit-content;padding:10px 14px;margin:8px 0;border-radius:14px;background:white;border:1px solid #e1eae4;white-space:pre-wrap;overflow-wrap:anywhere}.bubble.mine{margin-left:auto;background:#16834d;color:white;border-color:#16834d}.bubble time{display:block;font-size:11px;opacity:.75;margin-top:4px}.chat-form{display:flex;gap:10px;padding:16px;border-top:1px solid #e2eae5}.chat-form input{flex:1;min-width:0;border:1px solid #cad8ce;border-radius:10px;padding:12px}.chat-form button{border:0;border-radius:10px;background:#16834d;color:#fff;padding:0 20px}.chat-empty{margin:auto;text-align:center;padding:30px;color:#64766a}.chat-status{font-size:13px;color:#63776a;padding:0 16px 8px}@media(max-width:700px){.chat-shell{grid-template-columns:1fr}.chat-list{border-right:0;border-bottom:1px solid #e2eae5;max-height:230px;overflow:auto}.chat-messages{min-height:300px}}
</style>
@endpush
@section('content')
<div class="chat-wrap"><h1 class="h3 mb-3">Tin nhắn</h1><div class="chat-shell">
    <aside class="chat-list"><h2>Cuộc trò chuyện</h2>
        @forelse($conversations as $item)
            <a class="chat-link {{ $conversation?->id === $item->id ? 'active' : '' }}" href="{{ route('chat.show', $item) }}" data-no-ajax="true">
                @if($item->unread_count)<span class="chat-badge">{{ $item->unread_count }}</span>@endif
                <strong>{{ $user->id === $item->tenant_id ? $item->owner?->name : $item->tenant?->name }}</strong>
                <small>{{ $item->room?->name }}</small>
            </a>
        @empty <p class="p-3 text-muted">Chưa có cuộc trò chuyện nào.</p> @endforelse
    </aside>
    <section class="chat-main">
        @if($conversation)
            <div class="chat-head">{{ $user->id === $conversation->tenant_id ? $conversation->owner?->name : $conversation->tenant?->name }}<small>Phòng {{ $conversation->room?->name }} · <a href="{{ route('rooms.show', $conversation->room_id) }}">Xem phòng</a></small></div>
            <div class="chat-messages" id="chat-messages" aria-live="polite">
                @foreach($messages as $message)
                    <div class="bubble {{ $message->sender_id === $user->id ? 'mine' : '' }}" data-id="{{ $message->id }}"><span>{{ $message->body }}</span><time>{{ $message->created_at->format('H:i d/m/Y') }}</time></div>
                @endforeach
            </div>
            <form class="chat-form" id="chat-form" data-no-ajax="true"><input id="chat-input" maxlength="2000" autocomplete="off" placeholder="Nhập tin nhắn..." aria-label="Nội dung tin nhắn" required><button type="submit">Gửi</button></form>
            <div class="chat-status" id="chat-status" role="status">Đang kết nối...</div>
        @else <div class="chat-empty">Chọn một cuộc trò chuyện để xem tin nhắn.</div> @endif
    </section>
</div></div>
@endsection
@push('scripts')
@php
    $chatConfig = ['userId' => $user->id, 'conversationId' => $conversation?->id, 'sendUrl' => $conversation ? route('chat.send', $conversation) : null, 'readUrl' => $conversation ? route('chat.read', $conversation) : null, 'key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')];
@endphp
<script>window.chatConfig = @json($chatConfig);</script>
@vite('resources/js/chat.js')
@endpush
