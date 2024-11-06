<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Core extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the extensions for the Core
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function extensions(): HasMany
    {
        return $this->hasMany(CoreExtension::class);
    }
}
