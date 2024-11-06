<?php

 namespace App\Domain\DTOs\Response\Messaging;

use App\Domain\Entities\ChatEntity;

class ChatResponseDto
{
   
    public $chat_id;
    public $sender_chat_id;
    public $receiver_chat_id;
    public $IsBlocked;
    public $userDetail;
    public $chatHead;

    public function __construct(ChatEntity $chat){
        $this->chat_id = $chat->getChatId();
        $this->sender_chat_id = $chat->getSenderChatId();
        $this->receiver_chat_id = $chat->getReceiverChatId();
        $this->IsBlocked = $chat->getIsBlocked();
        $this->userDetail = $chat->getUserDetail();
        $this->chatHead = $chat->getChatHead();
    }


    public function toArray(){
        return [
            'chat_id' => $this->chat_id,
            'sender_chat_id' => $this->sender_chat_id,
            'receiver_chat_id' => $this->receiver_chat_id,
            'IsBlocked' => $this->IsBlocked,
            'userDetail' => $this->userDetail,
            'chatHead' => $this->chatHead
        ];
    }
}