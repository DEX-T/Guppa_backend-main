<?php

namespace App\Domain\Entities\DiscoverTalent;

use App\Models\Discover;
use App\Models\DiscoverBackground;

class DiscoverTalentEntity
{

    private int $id;
    private string $title;
    private string $description;
    private string $button_text;
    private $image_url;


    public function __construct(Discover $DiscoverTalent)
    {
        $this->id = $DiscoverTalent->id;
        $this->title = $DiscoverTalent->title;
        $this->description = $DiscoverTalent->description;
        $this->button_text = $DiscoverTalent->button_text;
        $this->image_url = $this->getBg();
    }

    public  function getBg(){
        $bg = DiscoverBackground::where('discover_id', 1)->first();
        if($bg != null){
            return  asset("storage/app/public/uploads/".$bg->image_url);
        }else{
            return null;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getButtonText(): string
    {
        return $this->button_text;
    }
    public function getImageUrl()
    {
        return $this->image_url;
    }
}
