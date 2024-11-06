<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\AppliedJob;
use App\Models\Milestone;
use App\Models\MyJob;
use App\Models\Verification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;

class GeneralHelper
{
    const EXCHANGE_RATE_NAIRA_TO_DOLLAR = 0.0027; // Example rate, 1 Naira = 0.0027 USD
    const EXCHANGE_RATE_DOLLAR_TO_NAIRA = 850;    // Example rate, 1 USD = 370 Naira

    public static function containsWord($input, $word){
        //check if the input contains word
        $input = strtolower($input);
        $word = strtolower($word);
        if (strpos($input, $word) !== false) {
            return true;
        }else{
            return false;
        }

    }

    public static function UserDetail($userId)
    {
        return User::where('id', $userId)->first();
    }
    public static function generateRandomString($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
    }

    public static function capitalize($value)
    {
        if (is_string($value) && stripos($value, 'controller') !== false) {
            // Capitalize "Controller"
            $value = str_ireplace('controller', 'Controller', $value);
        }
        return $value;

    }



    public static function convertRate($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === 'NGN' && $toCurrency === 'USD') {
            return $amount * self::EXCHANGE_RATE_NAIRA_TO_DOLLAR;
        } elseif ($fromCurrency === 'USD' && $toCurrency === 'NGN') {
            return $amount * self::EXCHANGE_RATE_DOLLAR_TO_NAIRA;
        } else {
            throw new \InvalidArgumentException("Invalid currency conversion from $fromCurrency to $toCurrency.");
        }
    }

    public static function formatAmount($amount, $currency)
    {
        switch (Str::upper($currency)) {
            case 'USD':
                return '$' . number_format($amount, 2);
            case 'NGN':
                return '₦' . number_format($amount, 2);
            default:
                return number_format($amount, 2) . ' ' . $currency;
        }
    }

    public static  function extractText($file)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file);
        $content = $pdf->getText();
        return $content;

    }

    public static function CalTotalEarning($applied_job_id)
    {
        $job = AppliedJob::where('id', $applied_job_id)->first();
        Log::info("Job calc total earning ", [$job]);
        if ($job != null) {
            $total_earnings = 0;
    
            if ($job->payment_type == 'milestone') {
                $milestones = Milestone::where('applied_job_id',  $job->id)->where('status', 'completed')->get();
                Log::info("milestones logged", [$milestones]);
                $totalAmount = $milestones->sum('milestone_amount');
                $total_earnings = $totalAmount - $job->service_charge;
            } else if ($job->payment_type == 'project') {
                $total_earnings = $job->project_price - $job->service_charge;
            }
    
            return $total_earnings;
        } else {
            return false;
        }
    }
    

    public static function Kobo($amount){
        return $amount * 100;
    }

    public static function timeAgo($datetime){
        date_default_timezone_set('Africa/Lagos');
        $time = strtotime($datetime) ? strtotime($datetime) : $datetime;
        $timed = time() - $time;

        switch ($timed) {
            case $timed <= 60:
                return 'Just Now!';
                break;
            case $timed >= 60 && $timed < 3600:
                return (round($timed/60) == 1) ? 'a minute ago' : round($timed/60). ' minutes ago';
                break;
            case $timed >= 3600 && $timed < 86400:
                return (round($timed/3600) == 1) ? 'an hour ago' : round($timed/3600). '  hours ago';
                break;
            case $timed >= 86400 && $timed < 604800:
                return (round($timed/86400 ) == 1) ? 'a day ago' : round($timed/86400 ). '  days ago';
                break;

            case $timed >= 604800 && $timed < 2600640:
                return (round($timed/604800 ) == 1) ? 'a week ago' : round($timed/604800 ). '  weeks ago';
                break;
            case $timed >=  2600640 && $timed < 31207680:
                return (round($timed/604800 ) == 1) ? 'a month ago' : round($timed/604800 ). '  months ago';
                break;
            case $timed >= 31207680:
                return (round($timed/31207680 ) == 1) ? 'a year ago' : round($timed/31207680 ). '  years ago';
                break;
        }

        Log::info("time" . $timed);
    }

    public static function isClientVerified(Authenticatable $user): bool
    {
        $isVerified = Verification::where('user_id', $user->id)->where('status', 'approved')->first();
        return (bool)$isVerified;
    }
}
