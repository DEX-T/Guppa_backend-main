<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FooterSocialMedia extends Model
{
    use HasFactory, SoftDeletes;
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
