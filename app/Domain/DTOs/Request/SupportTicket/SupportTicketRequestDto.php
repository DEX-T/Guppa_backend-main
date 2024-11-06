<?php

namespace App\Domain\DTOs\Request\SupportTicket;

class SupportTicketRequestDto
{
    public int $user_id;

    public string $type;
    public string $message;
    public $attachments;



    public function __construct(int $user_id, string $type, string $message, $attachments)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->message = $message;
        $this->attachments = $attachments;


    }
}
