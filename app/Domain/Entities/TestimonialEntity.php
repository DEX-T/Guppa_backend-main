<?php

namespace App\Domain\Entities;
use App\Models\Testimonial;

class TestimonialEntity
{
    private int $id;
    private string $title;
    private string $description;
    


    public function __construct(Testimonial $testimonial){
        $this->id = $testimonial->id;
        $this->title = $testimonial->title;
        $this->description = $testimonial->description;

     }

    public function getTestimonialId(){
        return $this->id;
      }
    
      public function getTestimonialtitle(){
        return $this->title;
      }
    
      public function getTestimonialdescription(){
        return $this->description;
      }
      
      
      
      
    // Define your entity properties and methods here
}