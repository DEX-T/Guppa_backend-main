<?php

 namespace App\Domain\DTOs\Response\Invites;

use App\Domain\Entities\SearchHistoryEntity;

class SearchHistoryResponseDto
{
    public $id;
    public $skills;
    public $ratings;
    public $experience;
    public $description;
    public $search_result;
    public $created_at;
    public $updated_at;

    public function __construct(SearchHistoryEntity $searchHistory){
        $this->id = $searchHistory->id;
        $this->skills = $searchHistory->skills;
        $this->ratings = $searchHistory->ratings;
        $this->experience = $searchHistory->experience;
        $this->description = $searchHistory->description;
        $this->search_result = $searchHistory->search_result;
        $this->created_at = $searchHistory->created_at;
        $this->updated_at = $searchHistory->updated_at;
        
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'skills' => $this->skills,
            'ratings' => $this->ratings,
            'experience' => $this->experience,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'search_result' => $this->search_result,
        ];
    }
   
}