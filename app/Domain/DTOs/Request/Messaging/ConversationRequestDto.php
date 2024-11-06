<?php

 namespace App\Domain\DTOs\Request\Messaging;

class ConversationRequestDto
{
    public string $sender_chat_id, $receiver_chat_id;
    public string $message;
    public int $chat_id;
    public int $message_id;
    public bool $IsFile;

    public function __construct(array $data){
        $this->sender_chat_id = $data['sender_chat_id'];
        $this->receiver_chat_id = $data['receiver_chat_id'];
        $this->message = $data['message'];
        $this->chat_id = $data['chat_id'];
        $this->message_id = $data['message_id'] ?? 0;
        $this->IsFile = $data['IsFile'];
    }
}