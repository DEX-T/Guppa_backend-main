<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class APILog extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected  $table = "api_logs";
    protected $guarded = [];

}
