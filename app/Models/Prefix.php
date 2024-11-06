<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prefix extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the general_middlewares for the Prefix
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function general_middlewares(): HasMany
    {
        return $this->hasMany(GeneralMiddleware::class);
    }
}
