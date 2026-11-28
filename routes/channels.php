<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat-user.{id}', fn (User $user, int $id) => $user->id === $id);
