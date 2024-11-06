<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavbarLogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the navbar that owns the NavbarButton
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function navbar(): BelongsTo
    {
        return $this->belongsTo(Navbar::class);
    }
}
