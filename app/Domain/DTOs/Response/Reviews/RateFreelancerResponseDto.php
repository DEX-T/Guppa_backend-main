<?php

namespace App\Domain\DTOs\Response\Reviews;

use App\Domain\Entities\RateFreelancerEntity;
use App\Models\FreelancerRating;

class RateFreelancerResponseDto
{
    public $id;
    public $freelancer_id;
    public $rated_by;
    public $rating;
    public $comment;
    public  $created_at;
    public $rater;
    public $freelancer_details;

    function __construct(RateFreelancerEntity $rating)
    {
        $this->id = $rating->id;
        $this->freelancer_id = $rating->freelancer_id;
        $this->rated_by = $rating->rated_by;
        $this->rating = $rating->rating;
        $this->comment = $rating->comment;
        $this->created_at = $rating->created_at;
        $this->rater = $rating->rater;
        $this->freelancer_details = $rating->freelancer_details;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'freelancer' => $this->freelancer_details,
            'rater' => $this->rater,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'date_rated' => $this->created_at
        ];
    }
}
