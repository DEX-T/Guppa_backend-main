<?php

namespace App\Domain\Entities;
use App\Models\FooterCopyRight;

class FooterCopyrightEntity
{
     private int $id;
     private int $footer_id;
    private string $title;
    private string $description;
    


    public function __construct(FooterCopyRight $footerCopyright){
        $this->id = $footerCopyright->id;
        $this->footer_id = $footerCopyright->footer_id;
        $this->title = $footerCopyright->title;
        $this->description = $footerCopyright->description;

     }

    public function getId(){
        return $this->id;
      }
    public function getFooterId(){
        return $this->footer_id;
      }
    
      public function getFootertitle(){
        return $this->title;
      }
    
      public function getFooterdescription(){
        return $this->description;
      }
    // Define your entity properties and methods here
}