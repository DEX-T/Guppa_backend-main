<?php

 namespace App\Domain\DTOs\Response\Account;

use App\Models\Bid;
use App\Models\MyJob;
use App\Models\FreelancerPortfolio;
use App\Domain\Entities\FreelancerProfileEntity;

class FreelancerProfileResponseDto
{
    public string $user_id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $phone_number;
    public string $country;
    public  $gigs;
    public  $years_of_experience;
    public  $looking_for;
    public  $skills;
    public  $portfolio_link_website;
    public  $language;
    public  $short_bio;
    public  $hourly_rate;
    public  $profile_photo = null;
    public $total_projects;
    public $total_hours;
    public $total_earnings;
    public $portfolios;
    public $bid;
    public $chatId;

    public function __construct(FreelancerProfileEntity $user){
        $this->user_id = $user->getUserId();
        $this->first_name = $user->getFirstName();
        $this->last_name = $user->getLastName();
        $this->phone_number = $user->getPhoneNo();
        $this->country = $user->getCountry();
        $this->email = $user->getEmail();
        $this->profile_photo = $user->getProfilePhoto();
        $this->gigs = $user->getGigs();
        $this->years_of_experience = $user->getYearsOfExperience();
        $this->looking_for = $user->getLookingFor();
        $this->skills = $user->getSkills();
        $this->portfolio_link_website = $user->getPortfolioLinkWebsite();
        $this->language = $user->getLanguage();
        $this->short_bio = $user->getShortBio();
        $this->hourly_rate = $user->getHourlyRate();
        $this->total_projects = $this->getProjects($user->getUserId());
        $this->total_hours = $this->getHours($user->getUserId());
        $this->total_earnings = $this->getEarnings($user->getUserId());
        $this->portfolios = $this->getFreelancerPortfolio();
        $this->bid = $this->getBid();
        $this->chatId = $user->getChatId();

    }

    public function getBid(){
        $bid = Bid::where('user_id', $this->user_id)->first('bid');
        return $bid;
    }
    public function getFreelancerPortfolio(){
        $designs = FreelancerPortfolio::where('user_id', $this->user_id)->get();
        if($designs->isNotEmpty()){
          $design =   $designs->map(function($design) {
                return [
                    // 'file_path' => asset("storage/app/public/uploads/".$design->file_path),
                    'file_path' => asset("storage/app/public/uploads/".$design->file_path),
                    'description' => $design->description
                ];
            })->toArray();

            return $design;
        }else{
            return null;
        }
    }
    
    public function userJob($userId){
        return MyJob::where(['user_id' => $userId]);
    }

    public function getProjects($id){
        $projects  = $this->userJob($id)->count();
        return $projects;
    }

    public function getHours($id){
        $hours  = $this->userJob($id)->sum('total_hours_worked');
        return $hours;
    }

    public function getEarnings($id){
        $earnings  = $this->userJob($id)->sum('total_earnings');
        return $earnings;
    }

    public function toArray(){
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'country' => $this->country,
            // 'profile_photo' => asset('storage/app/public/uploads/'.$this->profile_photo),
            'profile_photo' => asset('storage/app/public/uploads/'.$this->profile_photo),
            'gigs' => $this->gigs,
            'years_of_experience' => $this->years_of_experience,
            'looking_for' => $this->looking_for,
            'skills' => $this->skills,
            'portfolio_link_website' => $this->portfolio_link_website,
            'language' => $this->language,
            'short_bio' => $this->short_bio,
            'hourly_rate' => $this->hourly_rate,
            'total_projects' => $this->total_projects,
            'total_hours' => $this->total_hours,
            'total_earnings' => $this->total_earnings,
            'portfolios' => $this->portfolios,
            'bid_points' => $this->bid,
            'chat_id' => $this->chatId
            
        ];
    }
}