<?php

namespace App\Domain\DTOs\Response\DiscoverTalent;

use App\Domain\Entities\DiscoverTalent\DiscoverTalentEntity;

class DiscoverTalentResponseDto
{
    public int $id;
    public string $title;
    public string $description;
    public string $button_text;
    public $image_url;


    public function __construct(DiscoverTalentEntity $DiscoverTalent)
    {
        $this->id = $DiscoverTalent->getId();
        $this->title = $DiscoverTalent->getTitle();
        $this->description = $DiscoverTalent->getDescription();
        $this->button_text = $DiscoverTalent->getButtonText();
        $this->image_url = $DiscoverTalent->getImageUrl();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'image_url' => $this->image_url
        ];
    }
}
