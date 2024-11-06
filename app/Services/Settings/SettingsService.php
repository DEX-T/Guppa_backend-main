<?php

namespace App\Services\Settings;

use App\Models\User;
use App\Models\MyJob;
use App\Models\Setting;
use App\Models\GuppaJob;
use App\Models\TwoFaTracker;
use App\enums\HttpStatusCode;
use Illuminate\Support\Facades\DB;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Domain\Interfaces\Settings\ISettingsService;

class SettingsService  implements ISettingsService
{
    protected $_currentUser;
    public function __construct() {
        $this->_currentUser = Auth::user();
    }
    public function updateEmailNotifications()
    {
        try {
            $enabled = $this->getSettings()->email_notifications ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['email_notifications' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePushNotifications()
    {
        try {
            $enabled = $this->getSettings()->push_notifications ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['push_notifications' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSmsNotifications()
    {
        try {
            $enabled = $this->getSettings()->sms_notifications ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['sms_notifications' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateInAppNotifications()
    {
         try {
            $enabled = $this->getSettings()->in_app_notifications ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['in_app_notifications' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfileVisibility()
    {
         try {
            $enabled = $this->getSettings()->profile_visibility ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['profile_visibility' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSearchVisibility()
    {
         try {
            $enabled = $this->getSettings()->search_visibility ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['search_visibility' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDataSharing()
    {
         try {
            $enabled = $this->getSettings()->data_sharing ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['data_sharing' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLocationSettings()
    {
         try {
            $enabled = $this->getSettings()->location_settings ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['location_settings' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAdPreferences()
    {
         try {
            $enabled = $this->getSettings()->ad_preferences ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['ad_preferences' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateActivityStatus()
    {
         try {
            $enabled = $this->getSettings()->activity_status ? false : true;
            Log::info("status ". $enabled);
            $this->getSettings()->update(['activity_status' => $enabled]);
            return new ApiResponseDto(true, "Preference updated!", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettings()
    {
        return Setting::firstOrCreate(['user_id' => $this->_currentUser->id]);
    }

    public function Settings()
    {
        $settings =  Setting::firstOrCreate(['user_id' => $this->_currentUser->id]);
        $dto =
            [
                "id"=> $settings->id,
                "user_id"=> (int)$settings->user_id,
                "email_notifications"=> (boolean)$settings->email_notifications,
                "push_notifications"=> (boolean)$settings->push_notifications,
                "sms_notifications"=> (boolean)$settings->sms_notifications,
                "in_app_notifications"=> (boolean)$settings->in_app_notifications,
                "profile_visibility"=> (boolean)$settings->profile_visibility,
                "search_visibility"=> (boolean)$settings->search_visibility,
                "data_sharing"=> (boolean)$settings->data_sharing,
                "location_settings"=> (boolean)$settings->location_settings,
                "ad_preferences"=> (boolean)$settings->ad_preferences,
                "activity_status"=> (boolean)$settings->activity_status,

        ];
        return new ApiResponseDto(true, "Settings", HttpStatusCode::OK, $dto);
    }

    public function requestAccountData()
    {
        try {
            // Get current user details
            $user = User::findOrFail($this->_currentUser->id);
            $settings = $this->getSettings();
            $jobs = GuppaJob::where('user_id', $this->_currentUser->id)->get();
            $contracts = MyJob::where('user_id', $this->_currentUser->id)->get();
            $onBoarded = $this->_currentUser->on_boarded;

            // Prepare data array
            $data = [
                'user' => $user->toArray(),
                'settings' => $settings->toArray(),
                'jobs' => $jobs->toArray(),
                'contracts' => $contracts->toArray(),
                'on_boarded' => $onBoarded ? $onBoarded->toArray() : [], // Handle if null
            ];

            // Return file download response and delete the file after sending it
            return new ApiResponseDto(true, "file", HttpStatusCode::OK, $data);
            

        } catch (\Exception $e) {
            // Log the error and return a response
            Log::error('Error requesting account data: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to generate account data'], 500);
        }
    }


    public function deleteAccountPermanently()
    {
        $user = User::findOrFail($this->_currentUser->id);
        $userData = $user->toArray();

        try {
            // Begin transaction
            DB::beginTransaction();
    
            // Perform the logout
            auth()->logout();
    
            // Delete the user account
            $user->delete();
    
            // Commit transaction
            DB::commit();
    
            return new ApiResponseDto(true, "Account deleted ", HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
    
            Log::error('Account deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => $userData,
            ]);
            return new ApiResponseDto(false, "Server Error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function Is2FaVerified(){
            $tracker = TwoFaTracker::where('user_id', $this->_currentUser->id)->first();
            $data = [
                'is_verified' => $tracker && $tracker->is_verified ? true : false,
                'is_enabled' => $this->_currentUser->is_2fa_enabled ? true : false
            ];
            return new ApiResponseDto(true, "is verified", HttpStatusCode::OK, $data);
        
    }
}