<?php

namespace App\Domain\DTOs\Request\DiscoverTalent;

class UpdateDiscoverTalentBackgroundRequestDto
{
    public int $id;
    public int $discover_id;
    public string $image_url;


    public function __construct(int $id, int $discover_id, string $image_url)
    {
        $this->id = $id;
        $this->discover_id = $discover_id;
        $this->image_url = $image_url;
    }


}
