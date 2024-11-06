<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discover extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    /**
     * Get all of the background for the Discover
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function background(): HasMany
    {
        return $this->hasMany(DiscoverBackground::class);
    }
}
