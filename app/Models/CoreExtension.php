<?php

namespace App\Models;

use App\Models\Core;
use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreExtension extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get the core that owns the CoreExtension
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function core(): BelongsTo
    {
        return $this->belongsTo(Core::class);
    }
}
