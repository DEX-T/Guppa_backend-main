<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GuppaJob extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected  $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'job_tags', 'guppa_job_id', 'tag_id');
    }

    public function scopeActive($query){
        return $query->where('visibility', 'active');
    }

    public function scopeInActive($query){
        return $query->where('visibility', 'inactive');
    }

    public function scopeAvailable($query){
        return $query->where('job_status', 'available');
    }

    public function scopeTaken($query){
        return $query->where('job_status', 'taken');
    }

    public function scopeHourly($query){
        return $query->where('project_type', 'hourly');
    }

    public function scopeContract($query){
        return $query->where('project_type', 'contract');
    }

    /**
     * Get the user that owns the GuppaJob
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the applieds for the GuppaJob
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(AppliedJob::class);
    }
}
