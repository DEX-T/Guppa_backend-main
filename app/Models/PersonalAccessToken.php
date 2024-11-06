<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;
    public $table = "personal_access_tokens";
    protected $guarded = [];

    public  function isExpired(){
        return $this->expires_at && Carbon::parse($this->expires_at)->isFuture(); 
    }


}
