<?php

namespace App\Domain\Entities\SupportTicket;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Log;


class SupportTicketEntity
{

    private int $id;
    private int $user_id;
    private  $ticket_id;
    private string $type;
    private string $message;
    private string $status;
    private string $createdAt;
    private  $modifiedAt;
    private $attachments;
    private $user;

    public function __construct(SupportTicket $ticket)
    {
        $this->id = $ticket->id;
        $this->user_id = $ticket->user_id;
        $this->ticket_id = $ticket->ticket_id;
        $this->type = $ticket->type;
        $this->message = $ticket->message;
        $this->status = $ticket->status;
        $this->createdAt = $ticket->created_at;
        $this->modifiedAt = $ticket->updated_at;
        $this->attachments = $this->getAttachments($ticket->attachments);
        $this->user = $this->getUserDetail($ticket->user_id);
    }

    public function getUserDetail($userId){
        $user =  User::where('id', $userId)->first();
        return [
            'name' => $user->last_name . " " . $user->first_name,
            'email' => $user->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$user->profile_photo),
            'role' => $user->role

        ];
    }
    //get user ii
    public function getUser()
    {
        return $this->user;
    }
    public function getId(): int
    {
        return $this->id;
    }

   public function getUserId(): string
   {
        return $this->user_id;
   }

   public function getTicketId()
   {
        return $this->ticket_id;
   }

   public function getType(): string
   {
        return $this->type;
   }

   public function getMessage(): string
   {
        return $this->message;
   }
   public function getStatus(): string
   {
        return $this->status;
   }
   public function getCreatedAt(): string
   {
        return $this->createdAt;
   }
   public function getModifiedAt(): string
   {
        return $this->modifiedAt;
   }

   public function getAttachment()
   {
        return $this->attachments;
   }

    public function getAttachments($attachments): array
    {
        if(!empty($attachments)):
            $attachs = explode(',',$attachments);
            Log::info("attachments ", [$attachs]);
            if (count($attachs) > 1){
                $attachment = [];
                foreach($attachs as $att){
                    $attachment[] = asset('/storage/app/public/uploads/'.Str::trim($att));
                };
                return [
                    'multiple_attachments' => $attachment
                ];
            }else{
                return [
                    'single_attachment' => asset('/storage/app/public/uploads/'.Str::trim($attachments))
                ];
            }
        else:
          return [];
        endif;
    }
}
