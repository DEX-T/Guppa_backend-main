<?php

namespace App\Domain\DTOs;

class ApiResponseDto
{
    public bool $status;
    public string $message;
    public  $data;
    public int $code;

    public function __construct(bool $status, string $message, int $code = null, $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

}
