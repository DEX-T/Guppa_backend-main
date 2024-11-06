<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuppaController extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get the general_middle that owns the GuppaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function general_middleware(): BelongsTo
    {
        return $this->belongsTo(GeneralMiddleware::class);
    }

    /**
     * Get all of the guppa routes for the GuppaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guppa_routes(): HasMany
    {
        return $this->hasMany(GuppaRoute::class);
    }
}
