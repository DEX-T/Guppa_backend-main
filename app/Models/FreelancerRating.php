<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerRating extends Model
{
    use HasFactory;
    protected  $guarded = [];


    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}
