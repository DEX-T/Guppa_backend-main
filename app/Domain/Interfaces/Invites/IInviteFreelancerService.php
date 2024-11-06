<?php

namespace App\Domain\Interfaces\Invites;

use Illuminate\Http\Request;

use function Laravel\Prompts\search;

interface IInviteFreelancerService
{
 
    public function searchFreelancer(Request $request);
    public function inviteFreelancer(Request $request);
    public function invitesOnlyJobs();
    public function searchHistory();
    public function acceptInvite(int $id);
    public function InvitesSent();
    public function MyInvites();
    public function declineInvite(int $id);
 
}