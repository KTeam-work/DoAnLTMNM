<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    public function __construct(public ChatMessage $message) {}
    public function broadcastOn(): array
    {
        $conversation = $this->message->conversation;
        return [new PrivateChannel('chat-user.'.$conversation->tenant_id), new PrivateChannel('chat-user.'.$conversation->owner_id)];
    }
    public function broadcastAs(): string { return 'chat.message'; }
    public function broadcastWith(): array
    {
        return ['id' => $this->message->id, 'conversation_id' => $this->message->conversation_id, 'sender_id' => $this->message->sender_id, 'body' => $this->message->body, 'created_at' => $this->message->created_at->toISOString()];
    }
}
