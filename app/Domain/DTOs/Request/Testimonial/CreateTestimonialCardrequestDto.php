<?php

 namespace App\Domain\DTOs\Request\Testimonial;
 use Ramsey\Uuid\Type\Integer;


class CreateTestimonialCardrequestDto
{
  
    public string $testimonial;
    public int $testimonial_Id;
    public int $testimonial_card_id;

    public function __construct(string $testimonial, 
    int $testimonial_id, int $testimonial_card_id = 0){
       
        $this->testimonial = $testimonial;
        $this->testimonial_Id = $testimonial_id;
        $this->testimonial_card_id = $testimonial_card_id;

    }

    
      
    // Define your DTO properties and methods here

}