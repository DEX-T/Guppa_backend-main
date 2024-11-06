<?php

 namespace App\Domain\DTOs\Request\Configuration;
  use Ramsey\Uuid\Type\Integer;


class CreateFooterRequestDto
{

    public int $id;
    public string $title;
    public string $description;

    public function __construct(string $title, string $description, int $id = 0 ){
        $this->title = $title;
        $this->description = $description;
        $this->id = $id;
    }

    
}
   
    // Define your DTO properties and methods here
