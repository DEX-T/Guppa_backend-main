<?php

 namespace App\Domain\DTOs\Response\Jobs;


class ExtractedTextResponseDto
{
    public $content;
   

    public function __construct($content){
        $this->content = $content;
       
    }


    public function toArray(): array
    {
        return  [
            'content' => $this->content,
        ];
    }
}
