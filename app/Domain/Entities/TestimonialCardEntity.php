<?php

namespace App\Domain\Entities;
use App\Models\TestimonialCard;
use DateTime;



class TestimonialCardEntity
{

    private int $testimonial_card_id;
    private string $testimonial;
    private string $portfolio;
    private string $name;
    private string $profile_picture;
    private int $testimonial_Id;
    private DateTime $create_at;
    private DateTime $update_at;



    public function __construct(TestimonialCard $testimonialcard){
        $this->testimonial_card_id = $testimonialcard->id;
        $this->testimonial = $testimonialcard->testimonials;
        $this->portfolio = $testimonialcard->portfolio;
        $this->name = $testimonialcard->name;
        $this->profile_picture = $testimonialcard->profile_picture;
        $this->testimonial_Id = $testimonialcard->id;
    
    }

    public function getid(){
        return $this->testimonial_card_id;
      }
    
      public function gettestimonial(){
        return $this->testimonial;
      }
    
      public function getportfolio(){
        return $this->portfolio;
      }
      public function getname(){
        return $this->name;
      }
      public function getprofile_picture(){
        return $this->profile_picture;
      }
     
      public function gettestimonial_id(){
        return $this->testimonial_Id;
      }
      public function getCreatedAt(){
        return $this->create_at;
    }

    public function getUpdatedAt(){
        return $this->update_at;
    }
    // Define your entity properties and methods here
}