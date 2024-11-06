<?php

namespace App\Domain\DTOs\Response\DiscoverTalent;

use App\Domain\Entities\DiscoverTalent\DiscoverTalentBackgroundEntity;

class DiscoverTalentBackgroundResponseDto
{
    public int $id;
    public int $discover_id;
    public string $image_url;
    public string $created_at;
    public string $modified_at;


    public function __construct(DiscoverTalentBackgroundEntity $DiscoverTalentBackgroundEntity)
    {
        $this->id = $DiscoverTalentBackgroundEntity->getId();
        $this->discover_id = $DiscoverTalentBackgroundEntity->getDiscoverId();
        $this->image_url = $DiscoverTalentBackgroundEntity->getImageUrl();
        $this->created_at = $DiscoverTalentBackgroundEntity->createdAt();
        $this->modified_at = $DiscoverTalentBackgroundEntity->modifieldAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'discover_id' => $this->discover_id,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->modified_at,
        ];
    }
}
