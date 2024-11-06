<?php

 namespace App\Domain\DTOs\Request\FreelancerPortfolio;

class PortfolioRequestDto
{
    public $file_path;
    public $description;
    public $freelancer_id;
    public $portfolio_id;

    public function __construct($file_path, $description, $freelancer_id, $portfolio_id = 0){
        $this->file_path = $file_path;
        $this->description = $description;
        $this->freelancer_id = $freelancer_id;
        $this->portfolio_id = $portfolio_id;
    }
    
}