<?php

 namespace App\Domain\DTOs\Request\Testimonial;

class CreateTestimonialRequestDto
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