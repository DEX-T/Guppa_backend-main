<?php

namespace App\Domain\Entities;

use App\Models\Chat;
use App\Models\User;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChatEntity
{
    private $id;
    private $sender_chat_id;
    private $receiver_chat_id;
    private $IsBlocked;
    private $_currentUser;
    private $userDetail;
    private $chatHead;

    public function __construct(Chat $chat, User $currentUser){
        $this->id = $chat->id;
        $this->sender_chat_id = $chat->sender_chat_id;
        $this->receiver_chat_id = $chat->receiver_chat_id;
        $this->IsBlocked = $chat->IsBlocked;
        $this->_currentUser = $currentUser;
        $this->userDetail = $this->userDetails($chat->receiver_chat_id, $chat->sender_chat_id);
        $this->chatHead = $this->chatHeadDetails();
    }

    public function getUserDetail(){
        return $this->userDetail;
    }

    public function getChatHead(){
        return $this->chatHead;
    }

    public function getChatId(){
        return $this->id;
    }

    public function getSenderChatId(){
        return $this->sender_chat_id;
    }

    public function  getReceiverChatId(){
        return $this->receiver_chat_id;
    }

    public function getIsBlocked(){
        return $this->IsBlocked;
    }


    public function userDetails($receiver_chat_id, $sender_chat_id){
        Log::info("On user details current user " . $this->_currentUser->chatId);
        $detail = User::where('chatId', '!=', $this->_currentUser->chatId)->Where('chatId', $receiver_chat_id)->orWhere('chatId', $sender_chat_id)->first();
        if($detail != null){
            return [
                'user_id' => $detail->id,
                'name' => $detail->first_name . " " . $detail->last_name,
                'chat_id' => $detail->chatId,
                'email' => $detail->email,
                'profile_photo' => asset("storage/app/public/uploads/" . $detail->profile_photo)
            ];
          
        }else{
            return [];
        }
    }

    public function chatHeadDetails(){
        Log::info("On user details current user " . $this->_currentUser->chatId);
        $countChat = Conversation::where('chat_id', $this->id)->count();
        $takeFirst = Conversation::where('chat_id', $this->id)->Latest()->limit(1)->first();
        if($countChat > 0){
            return [
                'message' => $takeFirst->message,
                'time' => Carbon::parse($takeFirst->created_at)->format("g:i A"),
                'total_messages' => $countChat
            ];
        }else{
            return [];
        }
    
    }
    
}