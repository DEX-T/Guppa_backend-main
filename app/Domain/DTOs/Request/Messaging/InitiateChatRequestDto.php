<?php

 namespace App\Domain\DTOs\Request\Messaging;

class InitiateChatRequestDto
{
    public  $sender_chat_id, $receiver_chat_id;
    public function __construct($sender_chat_id, $receiver_chat_id){
        $this->sender_chat_id = $sender_chat_id;
        $this->receiver_chat_id = $receiver_chat_id;
    }
}