<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\Contract;
use App\Models\Conversation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index(Request $request, ?Conversation $conversation = null)
    {
        $user = $request->user();
        abort_unless(in_array($user->role, ['owner', 'tenant'], true), 403);
        if ($conversation && $conversation->exists) {
            abort_unless($conversation->includes($user->id), 404);
            $conversation->messages()->where('sender_id', '!=', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        } else {
            $conversation = null;
        }
        $conversations = Conversation::query()->where(fn ($q) => $q->where('tenant_id', $user->id)->orWhere('owner_id', $user->id))
            ->with(['room', 'owner', 'tenant'])->withCount(['messages as unread_count' => fn ($q) => $q->where('sender_id', '!=', $user->id)->whereNull('read_at')])
            ->orderByDesc('updated_at')->get();
        $messages = $conversation ? $conversation->messages()->orderBy('id')->get() : collect();
        return view('chat.index', compact('conversation', 'conversations', 'messages', 'user'));
    }

    public function start(Request $request, Room $room)
    {
        abort_unless($request->user()->role === 'tenant', 403);
        $room->load('property.owner');
        abort_unless($room->status === 'available' && $room->property?->status === 'active' && $room->property->owner?->role === 'owner'
            && $room->rentalPosts()->where('status', 'published')->where(fn ($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now()))->exists(), 404);
        $conversation = Conversation::firstOrCreate(['room_id' => $room->id, 'tenant_id' => $request->user()->id, 'owner_id' => $room->property->owner_id]);
        return redirect()->route('chat.show', $conversation);
    }

    public function startFromContract(Request $request, Contract $contract)
    {
        abort_unless($request->user()->role === 'tenant' && $contract->tenant_id === $request->user()->id, 404);
        $conversation = Conversation::firstOrCreate([
            'room_id' => $contract->room_id,
            'tenant_id' => $contract->tenant_id,
            'owner_id' => $contract->owner_id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function send(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->includes($request->user()->id), 404);
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $body = trim($data['body']);
        if ($body === '') return response()->json(['message' => 'Vui lòng nhập tin nhắn.'], 422);
        $message = DB::transaction(function () use ($conversation, $request, $body) {
            $message = $conversation->messages()->create(['sender_id' => $request->user()->id, 'body' => $body]);
            $conversation->touch();
            return $message;
        });
        try { event(new ChatMessageSent($message->load('conversation'))); }
        catch (\Throwable $e) { Log::error('Chat broadcast failed', ['error' => $e->getMessage()]); }
        return response()->json(['id' => $message->id, 'conversation_id' => $conversation->id, 'sender_id' => $message->sender_id, 'body' => $message->body, 'created_at' => $message->created_at->toISOString()], 201);
    }

    public function read(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->includes($request->user()->id), 404);
        $conversation->messages()->where('sender_id', '!=', $request->user()->id)->whereNull('read_at')->update(['read_at' => now()]);
        return response()->noContent();
    }
}
