<?php

namespace App\Domain\Interfaces\Settings;

interface ISettingsService
{
     // Notification settings
     public function updateEmailNotifications();
     public function updatePushNotifications();
     public function updateSmsNotifications();
     public function updateInAppNotifications();
 
     // Privacy settings
     public function updateProfileVisibility();
     public function updateSearchVisibility();
     public function updateDataSharing();
     public function updateLocationSettings();
     public function updateAdPreferences();
     public function updateActivityStatus();
 

    // Data and account management
    public function requestAccountData();
    public function deleteAccountPermanently();
     // Get settings
     public function getSettings();
     public function Settings();
     public function Is2FaVerified();
}