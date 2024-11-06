<?php

namespace App\Domain\DTOs\Response\Verification;

use App\Domain\Entities\VerificationEntity;
use Carbon\Carbon;

class VerificationResponseDto
{
    public int $id;
    public string $document_type;
    public string $government_id;
    public string $selfie_with_id;
    public string $full_name;
    public  $date_of_birth;
    public string $current_address;
    public string $phone_number;
    public string $email;
    public string $nationality;
    public string  $id_document_number;
    public string $status;
    public  $created_at;
    public $updated_at;
    public $client;

    /**
     * @param VerificationEntity $verificationEntity
     */
    public function __construct(VerificationEntity $verificationEntity)
    {
        $this->id = $verificationEntity->getId();
        $this->document_type = $verificationEntity->getDocumentType();
        $this->government_id = $verificationEntity->getGovernmentId();
        $this->selfie_with_id = $verificationEntity->getSelfieWithId();
        $this->full_name = $verificationEntity->getFullName();
        $this->date_of_birth =  $verificationEntity->getDateOfBirth();
        $this->current_address = $verificationEntity->getCurrentAddress();
        $this->phone_number = $verificationEntity->getPhoneNumber();
        $this->email = $verificationEntity->getEmail();
        $this->nationality = $verificationEntity->getNationality();
        $this->id_document_number = $verificationEntity->getIdDocumentNumber();
        $this->status = $verificationEntity->getStatus();
        $this->created_at = $verificationEntity->getCreatedAt();
        $this->updated_at = $verificationEntity->getUpdatedAt();
        $this->client = $verificationEntity->getClient();
    }

    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'document_type' => $this->document_type,
            'government_id' => $this->government_id,
            'selfie_with_id' => $this->selfie_with_id,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth,
            'current_address' => $this->current_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'nationality' => $this->nationality,
            'id_document_number' => $this->id_document_number,
            'status' => $this->status,
            'date_submitted' => $this->created_at,
            'date_approved' => $this->updated_at,
            'client' => $this->client
        ];
    }
}
