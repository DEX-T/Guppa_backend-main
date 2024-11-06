<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

class ConversationEntity
{
    private $chat_id;
    private $sender_chat_id;
    private $receiver_chat_id;
    private $message;
    private $created_at;
    private $updated_at;
    private $message_id;
    private $IsRead;
    private $IsFile;
    private $_currentUser;
    private $userDetail;


    public function __construct(Conversation $conversation, $currentUser){
        $this->chat_id =  $conversation->chat_id;
        $this->sender_chat_id =  $conversation->sender_chat_id;  
        $this->receiver_chat_id =  $conversation->receiver_chat_id;
        $this->message =  $conversation->IsFile ? asset('storage/app/public/uploads/'.$conversation->message) : $conversation->message;
        $this->created_at =  Carbon::parse($conversation->created_at)->format("g:i A");
        $this->updated_at = Carbon::parse($conversation->updated_at)->format("g:i A") ;
        $this->message_id = $conversation->id;
        $this->IsRead = $conversation->read_at != null ? true : false;
        $this->IsFile = $conversation->IsFile;
        $this->_currentUser = $currentUser;
        $this->userDetail = $this->userDetails($conversation->receiver_chat_id, $conversation->sender_chat_id);
       
    }

    public function getUserDetail(){
        return $this->userDetail;
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
    
    public function getIsFile(){
        return $this->IsFile;
    }

    public function getIsRead(){
        return $this->IsRead;
    }

    public function getChatId(){
        return $this->chat_id;
    }

    public function getMessageId(){
        return $this->message_id;
    }

    public function getSenderId(){
        return $this->sender_chat_id;
    }

    public function getReceiverId(){
        return $this->receiver_chat_id;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function getUpdatedAt(){
        return $this->updated_at;
    }
}