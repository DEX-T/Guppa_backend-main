<?php

 namespace App\Domain\DTOs\Response\Configuration;
use App\Domain\Entities\FooterCopyrightEntity;
use App\Models\FooterCopyRight;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class FooterCopyrightResponseDto
{
    public int $id;
    public string $title;
    public string $description;
    public int $footer_id;


    public function __construct(FooterCopyrightEntity $footerCopyright){
        $this->id = $footerCopyright->getId();
        $this->footer_id = $footerCopyright->getFooterId();
        $this->title = $footerCopyright->getFootertitle();
        $this->description = $footerCopyright->getFooterdescription();
    }

    public function toArray()
    {
        return [ 
                'id' => $this->id,
                'footer_id' => $this->footer_id,
                'title' => $this->title,
                'description' => $this->description,
        ];
    }
   
    // Define your DTO properties and methods here
}