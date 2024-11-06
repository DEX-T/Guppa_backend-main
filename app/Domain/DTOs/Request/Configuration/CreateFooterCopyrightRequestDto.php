<?php

 namespace App\Domain\DTOs\Request\Configuration;

class CreateFooterCopyrightRequestDto
{
        
    public string $title;
    public string $description;
    public int $footer_id;

    public function __construct(string $title, string $description, int $footer_id){
        $this->title = $title;
        $this->description = $description;
        $this->footer_id = $footer_id;
    }
    
    // Define your DTO properties and methods here
}