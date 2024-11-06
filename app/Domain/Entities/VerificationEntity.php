<?php

namespace App\Domain\Entities;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Carbon;

class VerificationEntity
{
    private int $id;
    private string $document_type;
    private string $government_id;
    private string $selfie_with_id;
    private string $full_name;
    private  $date_of_birth;
    private string $current_address;
    private string $phone_number;
    private string $email;
    private string $nationality;
    private string $id_document_number;
    private string $status;
    private  $created_at;
    private  $updated_at;
    private $client;

    /**
     * @param Verification $verification
     */
    public function __construct(Verification $verification)
    {
        $this->id = $verification->id;
        $this->document_type = $verification->document_type;
        $this->government_id = $verification->government_id;
        $this->selfie_with_id = $verification->selfie_with_id;
        $this->full_name = $verification->full_name;
        $this->date_of_birth = $verification->date_of_birth;
        $this->current_address = $verification->current_address;
        $this->phone_number = $verification->phone_number;
        $this->email = $verification->email;
        $this->nationality = $verification->nationality;
        $this->id_document_number = $verification->id_document_number;
        $this->status = $verification->status;
        $this->created_at = $verification->created_at;
        $this->updated_at = $verification->updated_at;
        $this->client = $this->getClientDetail($verification->user_id);
    }

    public function getClient(){
        return $this->client;
    }
    
    public function getClientDetail($clientId){
        $client = User::where('id', $clientId)->first();
        return [
            'id' => $client->id,
            'name' => $client->last_name . " " . $client->first_name,
            'profile_photo' => asset('storage/app/public/uploads/' . $client->profile_photo),
            'email' => $client->email
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDocumentType(){
        return $this->document_type;
    }
    public function getGovernmentId(): string
    {
        // return asset('storage/app/public/uploads/'.$this->government_id);
        return asset('storage/app/public/uploads/'.$this->government_id);
    }

    public function getSelfieWithId(): string
    {
        // return asset('storage/app/public/uploads/'.$this->selfie_with_id);
        return asset('storage/app/public/uploads/'.$this->selfie_with_id);

    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    public function getCurrentAddress(): string
    {
        return $this->current_address;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function getIdDocumentNumber()
    {
        return $this->id_document_number;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
