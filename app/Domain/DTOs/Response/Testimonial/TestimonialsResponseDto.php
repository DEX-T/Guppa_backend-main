<?php

 namespace App\Domain\DTOs\Response\Testimonial;
use App\Domain\Entities\TestimonialEntity;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class TestimonialsResponseDto
{
    public int $id;
    public string $title;
    public string $description;


    public function __construct(TestimonialEntity $testimonial){
        $this->id = $testimonial->getTestimonialId();
        $this->title = $testimonial->getTestimonialtitle();
        $this->description = $testimonial->getTestimonialdescription();
    }

    public function toArray()
    {
        return [ 
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
        ];
    }
    // Define your DTO properties and methods here
}