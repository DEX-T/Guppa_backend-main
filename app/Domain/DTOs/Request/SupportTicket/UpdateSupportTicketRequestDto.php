<?php

namespace App\Domain\DTOs\Request\SupportTicket;

class UpdateSupportTicketRequestDto
{
    public int $id;
    public string $type;
    public string $message;
    public function __construct(int $id, string $type, string $message)
    {
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;


    }

}
