<?php

 namespace App\Domain\DTOs\Request\Onboarding;

class FreelancerOnboardingRequestDto
{
    public string $gigs;
    public string $years_of_experience;
    public string $skills;
    public string $looking_for;
    public string $portfolio_link_website;
    public string $language;
    public string $short_bio;
    public string $hourly_rate;
    public string $category;

    
    public function __construct(array $data){
            $this->gigs = $data['gigs'];
            $this->years_of_experience = $data['years_of_experience'];
            $this->looking_for = $data['looking_for'];
            $this->skills = $data['skills'];
            $this->portfolio_link_website = $data['portfolio_link_website'];
            $this->language = $data['language'];
            $this->short_bio = $data['short_bio'];
            $this->hourly_rate = $data['hourly_rate'];
            $this->category = $data['category'];

    }
    // Define your DTO properties and methods here
}
