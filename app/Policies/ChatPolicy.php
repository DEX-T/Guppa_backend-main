<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class ChatPolicy
{
    public function view_chat(User $user, Collection $chat)
    {
        Log::info("User policy details " . $user->chatId . ' ' . $chat->first()->sender_chat_id . ' ' . $chat->first()->receiver_chat_id);
        Log::error("Chat ", $chat->toArray());
        return $user->chatId === $chat->first()->sender_chat_id  
        || $user->chatId ===  $chat->first()->receiver_chat_id
        ? Response::allow()
        : Response::denyWithStatus(401);

    }

    public function delete_chat(User $user, Chat $chat)
    {
        return $user->chatId === $chat->sender_chat_id || $user->chatId === $chat->receiver_chat_id
        ? Response::allow()
        : Response::denyWithStatus(401);
    }

    public function delete_message(User $user, Conversation $chat)
    {
        return $user->chatId === $chat->sender_chat_id || $user->chatId === $chat->receiver_chat_id
        ? Response::allow()
        : Response::denyWithStatus(401);
    }
    
}
