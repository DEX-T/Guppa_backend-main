<?php

namespace App\Domain\DTOs\Response\SupportTicket;
use App\Domain\Entities\SupportTicket\BidPaymentConfigEntity;
use App\Domain\Entities\SupportTicket\SupportTicketEntity;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Log;


class SupportTicketResponseDto
{
    public int $id;

    public string $user_id;
    public  $ticket_id;

    public string $type;
    public string $message;
    public string $status;

    public string $createdAt;
    public  $modifiedAt;
    public  $attachments;
    public $user;


    public function __construct(SupportTicketEntity $supportTicket)
    {
        $this->id = $supportTicket->getId();
        $this->user_id = $supportTicket->getUserId();
        $this->ticket_id = $supportTicket->getTicketId();
        $this->type = $supportTicket->getType();
        $this->message = $supportTicket->getMessage();
        $this->status = $supportTicket->getStatus();
        $this->createdAt = $supportTicket->getCreatedAt();
        $this->modifiedAt = $supportTicket->getModifiedAt();
        $this->attachments = $supportTicket->getAttachment();
        $this->user = $supportTicket->getUser();
    }



    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'ticket_id' => $this->ticket_id,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->modifiedAt,
            'attachments' => $this->attachments,
            'user' => $this->user,
        ];
    }
}
