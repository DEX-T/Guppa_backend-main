<?php

 namespace App\Domain\DTOs\Response\Monitor;

use App\Models\APILog;

class ApiUsageResponseDto
{
    public $user_id;
    public $token;
    public $method;
    public $url;
    public $status;
    public $duration;
    public $ip_address;
    public $user_agent;
    public $created_at;
    public function __construct(APILog $log){
        $this->user_id = $log->user_id;
        $this->token = $log->token;
        $this->url = $log->url;
        $this->status = $log->status;
        $this->duration = $log->duration;
        $this->ip_address = $log->ip_address;
        $this->user_agent = $log->user_agent;
        $this->created_at = $log->created_at;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'token' => $this->token,
            'url' => $this->url,
            'status' => $this->status,
            'duration' => $this->duration,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at,
        ];
    }

}
