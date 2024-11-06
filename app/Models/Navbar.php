<?php

namespace App\Models;

use App\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navbar extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the comments for the Navbar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nav_menus(): HasMany
    {
        return $this->hasMany(NavbarMenu::class);
    }

    /**
     * Get the nav_button associated with the Navbar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nav_buttons(): HasMany
    {
        return $this->hasMany(NavbarButton::class);
    }

    /**
     * Get the nav_logo associated with the Navbar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nav_logo(): HasOne
    {
        return $this->hasOne(NavbarLogo::class);
    }

    /**
     * Get all of the nav_texts for the Navbar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nav_texts(): HasMany
    {
        return $this->hasMany(NavbarText::class);
    }
}
