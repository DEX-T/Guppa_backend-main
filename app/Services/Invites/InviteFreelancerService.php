<?php

namespace App\Services\Invites;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invite;
use App\enums\UserRoles;
use App\Models\GuppaJob;
use App\Events\InviteEvent;
use App\Models\Notification;
use App\Models\SearchResult;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\SearchHistory;
use App\Events\InviteStatusEvent;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\SearchHistoryEntity;
use App\Domain\Entities\InviteFreelancerEntity;
use Illuminate\Support\Carbon as SupportCarbon;
use App\Domain\Interfaces\Invites\IInviteFreelancerService;
use App\Domain\DTOs\Response\Invites\InviteOnlyJobResponseDto;
use App\Domain\DTOs\Response\Invites\SearchHistoryResponseDto;
use App\Domain\DTOs\Request\Invites\InviteFreelancerRequestDto;
use App\Domain\DTOs\Response\Invites\InviteFreelancerResponseDto;

class InviteFreelancerService implements IInviteFreelancerService
{
    protected $_currentUser;
    public function __construct() {
        $this->_currentUser = Auth::user();
    }
    public function searchFreelancer(Request $request)
    {
        try {
            
            // Search for freelancers by skills, ratings, and experience. //WIP
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|int',
                'skills' => 'nullable|string',
                'ratings' => 'nullable|integer',
                'experience' => 'nullable|string',
            ]);

            if($validator->fails()){
                Log::error("Validation error ");
                return new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new InviteFreelancerRequestDto($validated, $this->_currentUser->id);
            Log::info("Dto mapped ");

            //find users 
            $freelancers = User::with('on_boarded')->where([
                'role' => UserRoles::FREELANCER, 
                'status' => 'active',
            ])->where('email_verified_at', '!=' , null)
            ->whereHas('on_boarded', function($query) use ($dto){
                $query->when($dto->skills, fn($query, $skill) => $query->where('skills', 'like', '%'.$skill.'%'));
                $query->when($dto->experience, fn($query, $experience) => $query->where('years_of_experience', 'like', '%'.$experience .'%'));
            })
            ->when($dto->ratings, fn($query, $rate) => $query->where('user_ratings', 'like', '%'.$rate .'%'));
        
            $freelancers =  $freelancers->get();

            Log::info("Find freelancers ");
            $job = GuppaJob::where('id', $dto->job_id)->first();

            $history = new SearchHistory();
            $history->client_id = $this->_currentUser->id;
            $history->skills = $dto->skills;
            $history->ratings = $dto->ratings;
            $history->experience = $dto->experience;
            $history->description = "Searching for freelancer for job : ( title: " .$job->title ." description: " . $job->description;
            $history->created_at = Carbon::now();
            $history->save();
            Log::info("History created ");


            if($freelancers->isNotEmpty()){
                foreach($freelancers as $freelancer){
                    $searchResult = new SearchResult();
                    $searchResult->search_history_id = $history->id;
                    $searchResult->freelancer_id = $freelancer->id;
                    $searchResult->created_at = Carbon::now();
                    $searchResult->save();
                }
                Log::info("Search result created ");
                $returnDto = $freelancers->map(function($freelancer){
                    return [
                        'id' => $freelancer->id,
                        'name' => $freelancer->first_name . " " . $freelancer->last_name,
                        'email' => $freelancer->email,
                        'profile_photo' => asset('storage/app/public/uploads/'.$freelancer->profile_photo),
                        'rating' => $freelancer->user_ratings,
                        'experience' => $freelancer->on_boarded->years_of_experience,
                        'skills' => $freelancer->on_boarded->skills
                    ];
                });
                Log::info("Response returned ");

                //return new ApiResponseDto
                return new ApiResponseDto(true, "freelancers found", HttpStatusCode::OK, $returnDto->toArray());
            }else{
                return new ApiResponseDto(false, "no freelancers found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
  
        }
    }

    public function inviteFreelancer(Request $request)
    {
       try {
            $validated = $request->validate([
                'freelancer_id' => 'required|integer',
                'job_id' => 'required|integer',
                'description' => 'required|string',
            ]);

            if(!$validated){
                return new ApiResponseDto(false, "Invalid request", HttpStatusCode::BAD_REQUEST, $validated);
            }
            $freelancer = User::findOrFail($validated['freelancer_id']);
            $job = GuppaJob::findOrFail($validated['job_id']);

            $invite = new Invite();
            $invite->client_id = $this->_currentUser->id;
            $invite->freelancer_id = $validated['freelancer_id'];
            $invite->guppa_job_id = $validated['job_id'];
            $invite->description = $validated['description'];
            $invite->status = "pending";
            $invite->created_at = Carbon::now();
            $invite->save();
            Log::info("Invite created ");
           
            $freelancer->notifications->create([
                "title" => "Invitation for Job bidding",
                "message" => "You have been invited to bid for job, please check your invites for more details",
                "isRead" => false,
                "created_at" => Carbon::now()
            ]);
            event(new InviteEvent($freelancer, $job));
            return new ApiResponseDto(true, "Invite created", HttpStatusCode::OK);
       } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }

    }

    public function invitesOnlyJobs()
    {
       try {
            $jobs = GuppaJob::where(['user_id' => $this->_currentUser->id, 'job_visibility' => 'invite'])->get();
            if($jobs->isNotEmpty()){
                $dto = $jobs->map(function($job){
                    return new InviteOnlyJobResponseDto($job);
                });
                return new ApiResponseDto(true, "Invites only jobs found", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No invites only jobs found", HttpStatusCode::NOT_FOUND);
            }

       } catch (\Exception $e) {
         return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }


    public function searchHistory()
    {
       try {
            $histories = SearchHistory::where(['client_id' => $this->_currentUser->id])->get();
            if($histories->isNotEmpty()){
                $dto = $histories->map(function($history){
                    $historyEntity = new SearchHistoryEntity($history);
                    return new SearchHistoryResponseDto($historyEntity);
                });
                return new ApiResponseDto(true, "Search history fetched", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No search history found", HttpStatusCode::NOT_FOUND);
            }

       } catch (\Exception $e) {
         return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }
   
    public function InvitesSent(){
        try {
            $invites = Invite::where(['client_id' => $this->_currentUser->id])->get();
            Log::info("invites ", [$invites]);
            if($invites->isNotEmpty()){
                $dto = $invites->map(function($invite){
                    Log::info("invite dto", [$invite]);
                    $inviteEntity = new InviteFreelancerEntity($invite);
                    Log::info("invites entity", [$invite]);
                    return new InviteFreelancerResponseDto($inviteEntity);
                });
                Log::info("invites response", [$dto->toArray()]);

                return new ApiResponseDto(true, "Invites sent fetched", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No invites was sent", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function MyInvites(){
        try {
            $invites = Invite::where(['freelancer_id' => $this->_currentUser->id])->get();
            if($invites->isNotEmpty()){
                $dto = $invites->map(function($invite){
                    $inviteEntity = new InviteFreelancerEntity($invite);
                    return new InviteFreelancerResponseDto($inviteEntity);
                });
                return new ApiResponseDto(true, "Invites Found", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Invites Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
       
    }

    public function acceptInvite($id){
        try {
            $invites = Invite::where(['id' => $id])->first();
            if($invites){
                $invites->status = "accepted";
                $invites->save();
                
                $notification = new Notification();
                $notification->user_id = $invites->client_id;
                $notification->title = "Invitation Accepted";
                $notification->message = $this->_currentUser->first_name ." has accepted your invitation, check the invites to initiate a chat";
                $notification->isRead = false;
                $notification->created_at = Carbon::now();
                $notification->save();
                
                $client = User::findOrFail($invites->client_id);
                $job = GuppaJob::findOrFail($invites->guppa_job_id);
                event(new InviteStatusEvent($invites, $client, $this->_currentUser, $job));
                return new ApiResponseDto(true, "Invite Accepted", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Invite Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function declineInvite($id){
        try {
            $invites = Invite::where(['id' => $id])->first();
            if($invites){
                $invites->status = "declined";
                $invites->save();

                $notification = new Notification();
                $notification->user_id = $invites->client_id;
                $notification->title = "Invitation Declined";
                $notification->message = $this->_currentUser->first_name ." has declined your invitation";
                $notification->isRead = false;
                $notification->created_at = Carbon::now();
                $notification->save();

                $client = User::findOrFail($invites->client_id);
                $job = GuppaJob::findOrFail($invites->guppa_job_id);
                event(new InviteStatusEvent($invites, $client, $this->_currentUser, $job));
                return new ApiResponseDto(true, "Invite Declined", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Invite Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
