<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppliedJob extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected  $guarded = [];

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Get the job that owns the AppliedJob
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(GuppaJob::class);
    }
}
