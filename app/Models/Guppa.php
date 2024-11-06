<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guppa extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get all of the guppa_cards for the Guppa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guppa_cards(): HasMany
    {
        return $this->hasMany(GuppaCard::class);
    }
}
