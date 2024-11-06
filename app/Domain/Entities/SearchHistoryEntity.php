<?php

namespace App\Domain\Entities;

use App\Models\SearchHistory;
use App\Models\SearchResult;
use App\Models\User;

class SearchHistoryEntity
{
    public $id;
    public $skills;
    public $ratings;
    public $experience;
    public $description;
    public $search_result;
    public $created_at;
    public $updated_at;

    public function __construct(SearchHistory $searchHistory){
        $this->id = $searchHistory->id;
        $this->skills = $searchHistory->skills;
        $this->ratings = $searchHistory->ratings;
        $this->experience = $searchHistory->experience;
        $this->description = $searchHistory->description;
        $this->search_result = $this->getResult($searchHistory->id);
        $this->created_at = $searchHistory->created_at;
        $this->updated_at = $searchHistory->updated_at;
        
    }


    public function getResult($id){
        $results = SearchResult::where('search_history_id', $id)->get();
        if($results->isNotEmpty()){
            $NewResults = $results->map(function($result){
                    return [
                        'id' => $result->id,
                        'freelancer' => $this->getFreelancer($result->freelancer_id),
                        'skills' => $result->skills,
                        'ratings' => $result->ratings,
                        'experience' => $result->experience,
                    ];
            });
            return $NewResults;
        }else{
            return [];
        }
    }

    public function getFreelancer($freelancerId){
        $freelancer = User::find($freelancerId);
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->first_name . " " . $freelancer->last_name,
            'email' => $freelancer->email,
            'phone' => $freelancer->phone_no,
            'chat_id' => $freelancer->chatId,
            'profile_picture' => asset('storage/app/public/uploads/'.$freelancer->profile_picture),
        ];
    }
}
   