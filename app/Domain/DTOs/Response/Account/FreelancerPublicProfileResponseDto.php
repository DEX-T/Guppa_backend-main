<?php

 namespace App\Domain\DTOs\Response\Account;

use App\Models\MyJob;
use App\Models\FreelancerPortfolio;
use App\Domain\Entities\FreelancerProfileEntity;

class FreelancerPublicProfileResponseDto
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
        $this->total_projects = $this->getProjects();
        $this->portfolios = $this->getFreelancerPortfolio();
        $this->chatId = $user->getChatId();

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
    
    public function userJob(){
        return MyJob::where(['user_id' => $this->user_id]);
    }

    public function getProjects(){
        $projects  = $this->userJob()->count();
        return $projects;
    }

    public function getHours(){
        $hours  = $this->userJob()->sum('total_hours_worked');
        return $hours;
    }

    public function getEarnings(){
        $earnings  = $this->userJob()->sum('total_earnings');
        return $earnings;
    }

    public function toArray(){
        return [
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
            'portfolios' => $this->portfolios,
            'chat_id' => $this->chatId
            
        ];
    }
}