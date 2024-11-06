<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FooterCopyRight extends Model
{
    use HasFactory, SoftDelete;
    protected $guarded = [];

    /**
     * Get the footer that owns the FooterCopyRight
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function footer(): BelongsTo
    {
        return $this->belongsTo(Footer::class);
    }


}
