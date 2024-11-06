<?php

namespace App\Domain\DTOs\Request\Jobs;

use DateTime;

class ApplyJobRequestDto
{
    public int $job_id;
    public int $user_id;
    public  $project_timeline;
    public string $cover_letter_file;
    public string $cover_letter;
    public string $payment_type;
    public  $milestone_description = [];
    public  $milestone_amount = [];
    public float $total_milestone_price = 0.0;
    public float $project_price = 0.0;
    public float $service_charge;
    public float $total_payable;

    public function __construct(array $data)
    {
        $this->job_id = $data['job_id'];
        $this->user_id = $data['user_id'];
        $this->project_timeline = $data['project_timeline'];
        $this->cover_letter_file = $data['cover_letter_file'];
        $this->cover_letter = $data['cover_letter'];
        $this->payment_type = $data['payment_type'];
        $this->milestone_description = is_array($data['milestone_description']) ? $data['milestone_description'] : [];
        $this->milestone_amount = is_array($data['milestone_amount']) ? $data['milestone_amount'] : [];
        $this->total_milestone_price = $data['total_milestone_price'];
        $this->project_price = $data['project_price'];
        $this->service_charge = $data['service_charge'];
        $this->total_payable = $data['total_payable'];

    }

    public function calculateTotalMilestonePrice(){
        $total = 0;
        foreach ($this->milestone_amount as $key => $value) {
            $total += $value;
        }
       return $total;

    }

    public function calculateTotalPayable($price, $service_charge){
        $payable = 0.0;
        //minus 1% from the $price to get the total payable
        $dis = $price * $service_charge;
        $payable = $price - $dis;
        return $payable;

    }

    public function ServiceCharge($price){
        $service_charge = 0.01;
        $onePercent = $price * $service_charge;
        return [
            'service_charge' => $service_charge,
            'amount' => $onePercent
        ];

    }
}
