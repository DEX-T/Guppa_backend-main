<?php

namespace App\Domain\Entities;
use App\Models\Footer;


class FooterEntity
{
    private int $id;
    private string $title;
    private string $description;
    
    
    public function __construct(Footer $footer){
        $this->id = $footer->id;
        $this->title = $footer->title;
        $this->description = $footer->description;

     }

    public function getFooterId(){
        return $this->id;
      }
    
      public function getFootertitle(){
        return $this->title;
      }
    
      public function getFooterdescription(){
        return $this->description;
      }
 
    // Define your entity properties and methods here
}