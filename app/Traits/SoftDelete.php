<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

trait SoftDelete
{
    protected static function bootSoftDelete()
    {
        static::addGlobalScope(new SoftDeletingScope);
    }

    public function delete()
    {
        if ($this->exists) {
            $this->runSoftDelete();
        }
    }

    protected function runSoftDelete()
    {
        $this->deleted = 1;
        $this->save();

        $this->fireModelEvent('deleted', true);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SoftDeletingScope);
    }

    public static function withoutTrashed()
    {
        return static::withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function withTrashed()
    {
        return static::withoutGlobalScope(SoftDeletingScope::class)->whereNotNull('deleted');
    }

    public static function onlyTrashed()
    {
        return static::withoutGlobalScope(SoftDeletingScope::class)->where('deleted', 1);
    }

    public function restore()
    {
        $this->deleted = 0;
        $this->save();
    }
}
