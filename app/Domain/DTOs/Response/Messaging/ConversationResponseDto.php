<?php

 namespace App\Domain\DTOs\Response\Messaging;

use App\Domain\Entities\ConversationEntity;
use Illuminate\Support\Facades\Log;

class ConversationResponseDto
{
    public $chat_id;
    public $sender_chat_id;
    public $receiver_chat_id;
    public $message;
    public $created_at;
    public $updated_at;
    public $message_id;
    public $IsRead;
    public $IsFile;
    public $userDetail;


    public function __construct(ConversationEntity $conversation){
        $this->chat_id =  $conversation->getChatId();
        $this->sender_chat_id =  $conversation->getSenderId();  
        $this->receiver_chat_id =  $conversation->getReceiverId();
        $this->message =  $conversation->getMessage();
        $this->created_at =  $conversation->getCreatedAt();
        $this->updated_at =  $conversation->getUpdatedAt();
        $this->message_id = $conversation->getMessageId();
        $this->IsRead = $conversation->getIsRead();
        $this->IsFile = $conversation->getIsFile();
        $this->userDetail = $conversation->getUserDetail();

    }

    
    public function toArray(){
        return [
            'chat_id' => $this->chat_id,
            'sender_chat_id' => $this->sender_chat_id,
            'receiver_chat_id' => $this->receiver_chat_id,
            'message' => $this->message,
            'message_id' => $this->message_id,
            'IsRead' => $this->IsRead,
            'IsFile' => $this->IsFile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'userDetail' => $this->userDetail
        ];
    }
}