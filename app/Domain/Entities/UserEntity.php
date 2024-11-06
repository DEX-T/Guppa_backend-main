<?php
namespace App\Domain\Entities;

use App\Models\FreelancerOnBoarding;
use App\Models\GuppaJob;
use App\Models\MyJob;
use App\Models\TwoFaTracker;
use App\Models\User;
use App\Models\Verification;

class UserEntity {

    private int $id;
    private string $first_name;
    private string $last_name;
    private string $phone_no;
    private string $country;
    private string $email;
    private bool $IsVerified;
    private string $role;
    private $chatId;
    private $profile_photo;
    private string $status;
    private $onboarding_data;
    private $verification_data;

    private  $gender;

    private  $age_group;

    private $user_ratings;
    private $created_at;
    private $totalGig;
    private $totalJobs;
    private $Is2FaActive;
    private $Is2FaVerified;



    public function __construct(User $user) {
        $this->id = $user->id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->phone_no = $user->phone_no;
        $this->country = $user->country;
        $this->email = $user->email;
        $this->IsVerified = $user->email_verified_at !== null ? true : false;
        $this->role = $user->role;
        $this->chatId = $user->chatId;
        $this->profile_photo = $user->profile_photo;
        $this->status = $user->status;
        $this->gender = $user->gender;
        $this->age_group = $user->age_group;
        $this->user_ratings = $user->user_ratings;
        $this->created_at = $user->created_at;
        $this->onboarding_data = $this->getFreelancerOnBoardingData($user->id);
        $this->verification_data = $this->getClientVerification($user->id);
        $this->totalGig = $this->getTotalGigs($user->id);
        $this->totalJobs= $this->getTotalJobs($user->id);
        $this->Is2FaActive = $user->is_2fa_enabled == 1 ? true : false;
        $this->Is2FaVerified = $this->checkIs2FaVerified($user->id);
    }

    public function checkIs2FaVerified($userId){
        $tracker = TwoFaTracker::where('user_id', $userId)->first();
        if($tracker && $tracker->is_verified){
            return true;
        }else{
            return false;
        }
    }

    public function getIs2FaVerified(){
        return $this->Is2FaVerified;
    }

    public function getTotal_gigs(){
        return $this->totalGig;
    }
    public function getTotal_Jobs(){
        return $this->totalJobs;
    }
    
    public function getTotalGigs($userId){
        $gigs = MyJob::where('user_id', $userId)->count();
        return $gigs;
    }
    public function getTotalJobs($userId){
        $jobs = GuppaJob::where('user_id', $userId)->active()->count();
        return $jobs;
    }
  
    public function getOnboardingData(){
        return $this->onboarding_data;
    }
  
    public function getVerificationData(){
        return $this->verification_data;
    }

    public function getFreelancerOnBoardingData($userId){
        $onboardingData = FreelancerOnBoarding::where('user_id', $userId)->first();
        if($onboardingData != null){
            return [
                'gigs' => $onboardingData->gigs,
                'years_of_experience' => $onboardingData->years_of_experience,
                'looking_for' => $onboardingData->looking_for,
                'skills' => $onboardingData->skills,
                'portfolio_link_website' => $onboardingData->portfolio_link_website,
                'language' => $onboardingData->language,
                'short_bio' => $onboardingData->short_bio,
                'hourly_rate' => $onboardingData->hourly_rate
            ];
        }else{
            return null;
        }
    }

    public function getClientVerification($userId){
        $verification = Verification::where('user_id', $userId)->first();
        if($verification != null){
            return [
                'document_type' => $verification->document_type,
                'government_id' => asset('storage/app/public/uploads/'.$verification->government_id),
                'selfie_with_id' => asset('storage/app/public/uploads/'.$verification->selfie_with_id),
                'full_name' => $verification->full_name,
                'date_of_birth' => $verification->date_of_birth,
                'current_address' => $verification->current_address,
                'phone_number' => $verification->phone_number,
                'email' => $verification->email,
                'nationality' => $verification->nationality,
                'id_document_number' => $verification->id_document_number,
                'status' => $verification->status,
                'date_submitted' => $verification->created_at,
                'date_approved' => $verification->updated_at
            ];
        }
        return null;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getGender(){
        return $this->gender;
    }

    public function get2FaActive(){
        return $this->Is2FaActive;
    }

    public function getAgeGroup(){
        return $this->age_group;
    }
    public function getUserRatings(){
        return $this->user_ratings;
    }

    public function getProfilePic(){
        return asset('storage/app/public/uploads/'.$this->profile_photo);
    }

    public function getChatId()
    {
        return $this->chatId;
    }

    public function getUserId(): int
    {
        return $this->id;
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
        return $this->phone_no;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getIsVerified(): bool
    {
        return $this->IsVerified;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

}

