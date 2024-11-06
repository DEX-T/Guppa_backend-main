<?php

namespace App\Traits;

trait WithSorting
{
    public array $sorts = [];
    public function sortBy($field){

        if(!isset($this->sorts[$field])) return $this->sorts[$field] = 'desc';

        if($this->sorts[$field] === 'desc') return $this->sorts[$field] = 'asc';
        unset($this->sorts[$field]);


    }

    public function applySorting($query){
        if($this->sorts == []){
            $query->latest();
        }else {
            foreach ($this->sorts as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }
}
