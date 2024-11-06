<?php

namespace App\Domain\DTOs\Response\WhyChooseUs;

use App\Domain\Entities\WhyChooseUs\WhyChooseUsCardEntity;

class WhyChooseUsCardResponseDto
{
    public int $id;
    public int $whychooseus_id;
    public string $picture;
    public string $title;
    public string $description;
    public string $created_at;
    public string $modified_at;


    public function __construct(WhyChooseUsCardEntity $WhyChooseUsCardEntity)
    {
        $this->id = $WhyChooseUsCardEntity->getId();
        $this->whychooseus_id = $WhyChooseUsCardEntity->getWhyChooseUsId();
        $this->picture = $WhyChooseUsCardEntity->getPicture();
        $this->title = $WhyChooseUsCardEntity->getTitle();
        $this->description = $WhyChooseUsCardEntity->getDescription();
        $this->created_at = $WhyChooseUsCardEntity->createdAt();
        $this->modified_at = $WhyChooseUsCardEntity->modifieldAt();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'whychooseus_id' => $this->whychooseus_id,
            'picture' => $this->picture,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->modified_at,
        ];
    }
}
