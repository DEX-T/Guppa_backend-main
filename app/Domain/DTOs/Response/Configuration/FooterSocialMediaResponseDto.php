<?php

 namespace App\Domain\DTOs\Response\Configuration;
 use App\Domain\Entities\FooterSocialMediaEntity;
use App\Models\FooterSocialMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class FooterSocialMediaResponseDto
{
    public int $id;
    public int $footer_id;
    public string $icon;
    public string $url;
    public string $status;
    public $created_at;
    public $updated_at;


    public function __construct(FooterSocialMediaEntity $footerSocialMedia){
        $this->id = $footerSocialMedia-> getId();
        $this->footer_id = $footerSocialMedia->getFooter_Id();
        $this->icon = $footerSocialMedia->getIcon();
        $this->url = $footerSocialMedia->getUrl();
        $this->status = $footerSocialMedia->getStatus();
        $this->created_at = $footerSocialMedia->getCreatedAt();
        $this->updated_at = $footerSocialMedia->getUpdatedAt();
    }

    public function toArray()
    {
        return [ 
                'id' => $this->id,
                'footer_id' => $this->footer_id,
                'icon' => $this->icon,
                'url' => $this->url,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
        ];
    }
}