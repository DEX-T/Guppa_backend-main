<?php

 namespace App\Domain\DTOs\Response\Testimonial;
    use App\Domain\Entities\TestimonialCardEntity;
    use App\Models\TestimonialCard;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Facades\Date;

class TestimonialCardResponseDto

{
    public int $testimonial_card_id;
    public string $testimonial;
    public string $portfolio;
    public string $name;
    public string $profile_picture;
    public int $testimonial_Id;
    


    public function __construct(TestimonialCardEntity $testimonialcard){
        
        $this->testimonial_card_id = $testimonialcard->getid();
        $this->testimonial = $testimonialcard->gettestimonial();
        $this->portfolio = $testimonialcard->getportfolio();
        $this->name = $testimonialcard->getname();
        $this->profile_picture = $testimonialcard->getprofile_picture();
        $this->testimonial_id = $testimonialcard->gettestimonial_id();
       
    }

    public function toArray() 
    {
        return [
            'testimonial' => $this -> testimonial,
            'portfolio' => $this-> portfolio,
            'name' => $this -> name,
            'profile_picture' => $this -> profile_picture,
           'testimonial_id' => $this -> testimonial_id,
            
        ];
    }
    // Define your DTO properties and methods here
}