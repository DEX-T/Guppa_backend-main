<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Footer extends Model
{
    use HasFactory, SoftDeletes;
    protected  $guarded = [];

    public function copy_right(): HasOne
    {
        return $this->hasOne(FooterCopyRight::class);
    }

    public function social_media(): HasMany
    {
        return $this->hasMany(FooterSocialMedia::class);
    }
}
