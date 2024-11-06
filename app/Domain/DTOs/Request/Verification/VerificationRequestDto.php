<?php

namespace App\Domain\DTOs\Request\Verification;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class VerificationRequestDto
{

    public string $document_type;
    public string $government_id;
    public string $selfie_with_id;
    public string $full_name;
    public   $date_of_birth;
    public string $current_address;
    public string $phone_number;
    public string $email;
    public string $nationality;
    public string $id_document_number;

    public function __construct($data)
    {
        $this->document_type = $data['document_type'];
        $this->government_id = $data['government_id'];
        $this->selfie_with_id = $data['selfie_with_id'];
        $this->full_name = $data['full_name'];
        $this->date_of_birth = $data['date_of_birth'];
        $this->current_address = $data['current_address'];
        $this->phone_number = $data['phone_number'];
        $this->email = $data['email'];
        $this->nationality = $data['nationality'];
        $this->id_document_number = $data['id_document_number'];
    }
}
