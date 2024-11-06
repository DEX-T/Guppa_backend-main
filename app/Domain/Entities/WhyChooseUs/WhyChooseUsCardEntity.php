<?php

namespace App\Domain\Entities\WhyChooseUs;

use App\Models\GuppaCard;

class WhyChooseUsCardEntity
{

    private int $id;
    private int $whychooseus_id;
    private string $picture;
    private string $title;
    private string $description;
    private string $createdat;
    private string $modifiedat;


    public function __construct(GuppaCard $whychooseuscard)
    {
        $this->id = $whychooseuscard->id;
        $this->whychooseus_id = $whychooseuscard->guppa_id;
        $this->picture = $whychooseuscard->picture;
        $this->title = $whychooseuscard->title;
        $this->description = $whychooseuscard->description;
        $this->createdat = $whychooseuscard->created_at;
        $this->modifiedat = $whychooseuscard->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWhyChooseUsId(): int
    {
        return $this->whychooseus_id;
    }
    public function getPicture(): string
    {
        return asset("storage/app/public/uploads/". $this->picture);
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function createdAt(): string
    {
        return $this->createdat;
    }
    public function modifieldAt(): string
    {
        return $this->modifiedat;
    }
}
