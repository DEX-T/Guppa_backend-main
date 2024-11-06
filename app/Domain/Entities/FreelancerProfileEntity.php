<?php

namespace App\Domain\Entities;

use App\Models\User;

class FreelancerProfileEntity
{
    private string $user_id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $phone_number;
    private string $country;
    private  $gigs;
    private  $years_of_experience;
    private  $looking_for;
    private  $skills;
    private  $portfolio_link_website;
    private  $language;
    private  $short_bio;
    private  $hourly_rate;
    private  $profile_photo = null;
    private $chatId = null;
 


    public function __construct(User $user){
        $this->user_id = $user->id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->phone_number = $user->phone_no;
        $this->country = $user->country;
        $this->email = $user->email;
        $this->profile_photo = $user->profile_photo;
        $this->chatId = $user->chatId;

        $this->gigs = $user->on_boarded->gigs ?? null;
        $this->years_of_experience = $user->on_boarded->years_of_experience ?? null;
        $this->looking_for = $user->on_boarded->looking_for ?? null;
        $this->skills = $user->on_boarded->skills ?? null;
        $this->portfolio_link_website = $user->on_boarded->portfolio_link_website ?? null;
        $this->language = $user->on_boarded->language ?? null;
        $this->short_bio = $user->on_boarded->short_bio ?? null;
        $this->hourly_rate = $user->on_boarded->hourly_rate ?? null;
      

    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getPhoneNo(): string
    {
        return $this->phone_number;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

   //get gigs
   public function getGigs()
   {
    return $this->gigs;
   }

   //get years of experience
   public function getYearsOfExperience(){
    return $this->years_of_experience;
   }

   //get looking for
   public function getLookingFor(){
    return $this->looking_for;
   }

   //get skills
   public function getSkills(){
    return $this->skills;
   }

   //get portfolio link website
   public function getPortfolioLinkWebsite(){
    return $this->portfolio_link_website;
   }

   //get language 
   public function getLanguage(){
    return $this->language;
   }

   //get short bio
   public function getShortBio(){
    return $this->short_bio;
   }

   //get hourly rate
   public function getHourlyRate(){
    return $this->hourly_rate;
   }


   //get hourly rate
   public function getProfilePhoto(){
    return $this->profile_photo;
   }

   public function getChatId(){
    return $this->chatId;
   }
}