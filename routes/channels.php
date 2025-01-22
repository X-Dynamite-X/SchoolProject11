<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('conversation_{id}', function ($user, $id) {
    $conversation = Conversation::find($id);
    if (!$conversation) {
        return false;
    }
    return (int) $conversation->user_one_id === (int) $user->id ||
        (int) $conversation->user_two_id === (int) $user->id;
});
Broadcast::channel('message_in_conversation_{id}_isRead', function ($user, $id) {
    $conversation = Conversation::find($id);
    if (!$conversation) {
        return false;
    }
    return (int) $conversation->user_one_id === (int) $user->id ||
        (int) $conversation->user_two_id === (int) $user->id;
});
