<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];
    public function room() { return $this->belongsTo(Room::class); }
    public function tenant() { return $this->belongsTo(User::class, 'tenant_id'); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function messages() { return $this->hasMany(ChatMessage::class); }
    public function includes(int $userId): bool { return $this->tenant_id === $userId || $this->owner_id === $userId; }
}
