<?php

 namespace App\Domain\DTOs\Request\Messaging;

class ConversationAttachmentRequestDto
{
    public string $sender_chat_id, $receiver_chat_id;
    public int $chat_id;

    public function __construct(array $data){
        $this->sender_chat_id = $data['sender_chat_id'];
        $this->receiver_chat_id = $data['receiver_chat_id'];
        $this->chat_id = $data['chat_id'];
    }
}