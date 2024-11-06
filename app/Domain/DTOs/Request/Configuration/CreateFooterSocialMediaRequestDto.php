<?php

 namespace App\Domain\DTOs\Request\Configuration;
  use Ramsey\Uuid\Type\Integer;


class CreateFooterSocialMediaRequestDto
{
    public int $id;
    public int $footer_id;
    public string $icon;
    public string $url;

    public function __construct(string $icon, string $url, int $footer_id = 1 ){
        $this->icon = $icon;
        $this->url = $url;
        $this->footer_id = $footer_id;
    }
 
}