<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuppaPasswordRestToken extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'expiry' => 'datetime:Y-m-d H:i:s',
    ];

    //hidden
    protected $hidden = [
        'token',
        
    ];

    public function setUpdatedAt($value)
    {
        return $this;
    }

    // Optionally, you can disable the updated_at column for saving
    public function getUpdatedAtColumn()
    {
        return null;
    }
}
