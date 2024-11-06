<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchResult extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the search_history that owns the SearchResult
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function search_history(): BelongsTo
    {
        return $this->belongsTo(SearchHistory::class);
    }
}
