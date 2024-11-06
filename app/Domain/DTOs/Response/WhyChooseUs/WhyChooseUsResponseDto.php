<?php

namespace App\Domain\DTOs\Response\WhyChooseUs;

use App\Domain\Entities\WhyChooseUs\WhyChooseUsEntity;

class WhyChooseUsResponseDto
{
    public int $id;
    public string $heading;
    public string $description;


    public function __construct(WhyChooseUsEntity $WhyChooseUs)
    {
        $this->id = $WhyChooseUs->getId();
        $this->heading = $WhyChooseUs->getWhyChooseUsHeading();
        $this->description = $WhyChooseUs->getWhyChooseUsDescription();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'description' => $this->description,
        ];
    }
}
