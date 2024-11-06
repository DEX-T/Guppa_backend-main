<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuppaCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the guppa that owns the GuppaCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guppa(): BelongsTo
    {
        return $this->belongsTo(Guppa::class);
    }
}
