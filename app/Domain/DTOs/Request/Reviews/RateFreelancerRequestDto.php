<?php

namespace App\Domain\DTOs\Request\Reviews;

class RateFreelancerRequestDto
{
    public $freelancer_id;
    public $rated_by;
    public $rating;
    public $comment;

    function __construct(array $validated)
    {
        $this->freelancer_id = $validated['freelancer_id'];
        $this->rated_by = $validated['rated_by'];
        $this->rating = $validated['rating'];
        $this->comment = $validated['comment'];
    }
}
