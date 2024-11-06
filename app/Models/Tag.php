<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory , SoftDeletes;

    protected  $guarded = [];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(GuppaJob::class, 'job_tags', 'guppa_job_id', 'tag_id');
    }

}
