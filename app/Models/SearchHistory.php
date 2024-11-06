<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the search_result associated with the SearchHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function search_result(): HasOne
    {
        return $this->hasOne(SearchResult::class);
    }
}
