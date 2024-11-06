<?php

 namespace App\Domain\DTOs\Response\Tokens;

use DateTime;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Database\Eloquent\Collection;

class AccessTokensResponseDto
{
    public string $tokenable_type;
    public string $name;
    public string $token;
    public string $tokenable_id;
    public string $abilities;
    public  $last_used_at;
    public  $expires_at;
    public  $createdAt;
    public  $updatedAt;

    public function __construct(PersonalAccessToken $token)
    {
        $this->tokenable_type = $token->tokenable_type;
        $this->tokenable_id = $token->tokenable_id;
        $this->name = $token->name;
        $this->token = $token->token;
        $this->abilities = $token->abilities;
        $this->last_used_at = $token->last_used_at;
        $this->expires_at = $token->expires_at;
        $this->createdAt = $token->created_at;
        $this->updatedAt = $token->updated_at;
       
        
    }

    public function toArray(){
        return [
            'tokenable_type' => $this->tokenable_type,
            'tokenable_id' => $this->tokenable_id,
            'name' => $this->name,
            'token' => $this->token,
            'abilities' => $this->abilities,
            'last_used_at' => $this->last_used_at,
            'expires_at' => $this->expires_at,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
            
        ];
    }
    // Define your DTO properties and methods here
}