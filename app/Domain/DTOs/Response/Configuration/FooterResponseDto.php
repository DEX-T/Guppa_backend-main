<?php

 namespace App\Domain\DTOs\Response\Configuration;
 use App\Domain\Entities\FooterEntity;
use App\Models\Footer;
use Illuminate\Support\Facades\Date;

class FooterResponseDto
{
    public int $id;
    public string $title;
    public string $description;


    public function __construct(FooterEntity $testimonial){
        $this->id = $testimonial->getFooterId();
        $this->title = $testimonial->getFootertitle();
        $this->description = $testimonial->getFooterdescription();
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