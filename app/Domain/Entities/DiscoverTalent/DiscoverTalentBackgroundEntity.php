<?php

namespace App\Domain\Entities\DiscoverTalent;

use App\Models\DiscoverBackground;

class DiscoverTalentBackgroundEntity
{

    private int $id;
    private int $discover_id;
    private string $image_url;
    private string $created_at;
    private string $modified_at;


    public function __construct(DiscoverBackground $DiscoverTalentBackground)
    {
        $this->id = $DiscoverTalentBackground->id;
        $this->discover_id = $DiscoverTalentBackground->discover_id;
        $this->image_url = $DiscoverTalentBackground->image_url;
        $this->created_at = $DiscoverTalentBackground->created_at;
        $this->modified_at = $DiscoverTalentBackground->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDiscoverId(): int
    {
        return $this->discover_id;
    }
    public function getImageUrl(): string
    {
        return asset("storage/app/public/uploads/".$this->image_url);
    }
    public function createdAt(): string
    {
        return $this->created_at;
    }
    public function modifieldAt(): string
    {
        return $this->modified_at;
    }
}
