<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class MyJob extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    public $table ="contracts";
    protected  $guarded = [];

    /**
     * Get the user that owns the Setting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeProgress($query){
        return $query->where('status' ,"In Progress");
    }
    public function scopeReview($query){
        return $query->where('status' ,"Awaiting Review");
    }
    public function scopeDone($query){
        return $query->where('status' ,"Done");
    }
    
}
