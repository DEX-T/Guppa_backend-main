<?php

 namespace App\Domain\DTOs\Request\SubMiddlewares;

class SubMiddlewareRequestDto
{
    public string $value;
    public int $middleware_id;

    public function __construct(string $value, int $middleware_id = 0){
        $this->value = $value;
        $this->middleware_id = $middleware_id;
    }
    // Define your DTO properties and methods here
}