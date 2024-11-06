<?php

namespace App\Domain\Entities;
use App\Models\FooterSocialMedia;

class FooterSocialMediaEntity
{
    private int $id;
    private int $footer_id;
    private string $icon;
    private string $url;
    private string $status;
    private $created_at;
    private $updated_at;
    


    public function __construct(FooterSocialMedia $FooterSocialMedia){
        $this->id = $FooterSocialMedia->id;
        $this->footer_id = $FooterSocialMedia->footer_id;
        $this->icon = $FooterSocialMedia->icon;
        $this->url = $FooterSocialMedia->url;
        $this->status = $FooterSocialMedia->status;
        $this->created_at = $FooterSocialMedia->created_at;
        $this->updated_at = $FooterSocialMedia->updated_at;

     }

    public function getStatus(){
        return $this->status;
    }
    
    public function getId(){
        return $this->id;
      }
    
    public function getFooter_Id(){
        return $this->id;
      }
    
      public function getIcon(){
        return $this->icon;
      }
    
      public function getUrl(){
        return $this->url;
      }

      public function getCreatedAt(){
        return $this->created_at;
      }

      public function getUpdatedAt(){
        return $this->updated_at;
      }
      
}