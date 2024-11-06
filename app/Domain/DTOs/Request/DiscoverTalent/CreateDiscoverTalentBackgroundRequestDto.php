<?php

namespace App\Domain\DTOs\Request\DiscoverTalent;

class CreateDiscoverTalentBackgroundRequestDto
{
    public int $discover_id;
    public string $image_url;
    public function __construct(int $discover_id, string $image_url)
    {
        $this->discover_id = $discover_id;
        $this->image_url = $image_url;
    }
}
