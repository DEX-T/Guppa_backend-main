<?php

 namespace App\Domain\DTOs\Request\Reports;

class ReportRequestDto
{
    public $start_date;
    public $end_date;
    public $status;
    public $role;
    public $country;
    public $visibility; //active or inactive
    public $job_visibility; // public or invite only
    public $job_status; // available or taken
    public $payment_type;
    public function __construct($filters){
        $this->start_date = $filters['start_date'] ?? null;
        $this->end_date = $filters['end_date'] ?? null;
        $this->status = $filters['status'] ?? null;
        $this->role = $filters['role'] ?? null;
        $this->country = $filters['country'] ?? null;
        $this->visibility = $filters['visibility'] ?? null;
        $this->job_visibility = $filters['job_visibility'] ?? null;
        $this->payment_type = $filters['payment_type'] ?? null;
    }

}
