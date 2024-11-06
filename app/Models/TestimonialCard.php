<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestimonialCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the user that owns the TestimonialCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the testimony that owns the TestimonialCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testimony(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }
}
