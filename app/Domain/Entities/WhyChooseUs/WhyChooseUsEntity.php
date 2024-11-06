<?php

namespace App\Domain\Entities\WhyChooseUs;

use App\Models\Guppa;


class WhyChooseUsEntity
{

    private int $id;
    private string $heading;
    private string $description;


    public function __construct(Guppa $WhyChooseUs)
    {
        $this->id = $WhyChooseUs->id;
        $this->heading = $WhyChooseUs->heading;
        $this->description = $WhyChooseUs->description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWhyChooseUsHeading(): string
    {
        return $this->heading;
    }
    public function getWhyChooseUsDescription(): string
    {
        return $this->description;
    }
}
