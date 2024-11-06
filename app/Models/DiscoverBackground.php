<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscoverBackground extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    /**
     * Get the discover that owns the DiscoverBackground
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discover(): BelongsTo
    {
        return $this->belongsTo(Discover::class);
    }
}
