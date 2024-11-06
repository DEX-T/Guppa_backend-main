<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralMiddleware extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get the prefix that owns the GeneralMiddleware
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prefix(): BelongsTo
    {
        return $this->belongsTo(Prefix::class);
    }

    /**
     * Get all of the controllers for the GeneralMiddleware
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function controllers(): HasMany
    {
        return $this->hasMany(GuppaController::class);
    }
}
