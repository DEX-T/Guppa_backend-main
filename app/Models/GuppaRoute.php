<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuppaRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the controller that owns the GuppaRoute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controller(): BelongsTo
    {
        return $this->belongsTo(GuppaController::class);
    }

    
}
