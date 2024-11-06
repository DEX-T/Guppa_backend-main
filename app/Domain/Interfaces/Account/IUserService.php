<?php

namespace App\Domain\Interfaces\Account;

use Illuminate\Http\Request;

interface IUserService
{
    public function getUserCheckList();
    public function getUserById(int $id);
    public function getAllUsers();
    public function getClientById(int $id);
    public function getAllClients();
    public function getAllAdmins();

    public function ForgotPassword(Request $request);
    public function ResetPassword(Request $request);
    public function UploadProfile(Request $request);
    public function getFreelancerProfile(int $userId);
    public function getFreelancerPublicProfile(int $userId);
    public function getBids();
    public function getUserBids();

    public function upsert_portfolio(Request $request);
    public function delete_portfolio(int $id);
    public function generate_chatId();

    public function updateSkills(Request $request);
    public function updateHourlyRate(Request $request);
    public function updateShortBio(Request $request);
    public function updateLanguage(Request $request);
    public function updateLookingFor(Request $request);

    public function activateUser(int $userId);
    public function deleteUser(int $userId);
    public function deactivateUser(int $userId);
    // public function editUserProfile(int $userId);
    // public function trackUserLogin(int $userId);
    // public function monitorUserActivity(int $userId);
     public function trackUserProfile();
     public function updateUserDetails(Request $request);
     public function getCurrentUser();
     public function changePassword(Request $request);




}
