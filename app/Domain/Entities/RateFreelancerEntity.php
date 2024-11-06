<?php

namespace App\Domain\Entities;

use App\Models\FreelancerRating;
use App\Models\MyJob;
use App\Models\User;

class RateFreelancerEntity
{
    public $id;
    public $freelancer_id;
    public $rated_by;
    public $rating;
    public $comment;
    public  $created_at;
    public $rater;
    public $freelancer_details;

    function __construct(FreelancerRating $rating)
    {
        $this->id = $rating->id;
        $this->freelancer_id = $rating->freelancer_id;
        $this->rated_by = $rating->rated_by;
        $this->rating = $rating->rating;
        $this->comment = $rating->comment;
        $this->created_at = $rating->created_at;
        $this->rater = $this->getRater($rating->rated_by);
        $this->freelancer_details = $this->getFreelancerDetails($rating->freelancer_id);
    }

    public function getRater($raterId): array
    {
        $rater = User::findOrFail($raterId);
        return [
            'id' => $rater->id,
            'name' => $rater->first_name . " " . $rater->last_name,
            'profile_photo' =>  asset('storage/app/public/uploads/'.$rater->profile_photo)
        ];
    }

    public function getFreelancerDetails($freelancerId): array
    {
        $freelancer = User::findOrFail($freelancerId);
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->first_name . " " . $freelancer->last_name,
            'profile_photo' =>  asset('storage/app/public/uploads/'.$freelancer->profile_photo),
            'no_of_projects' => $freelancer->contracts()->count()
        ];
    }
}
