<?php

namespace App\Services\Job;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\Tag;
use App\Models\User;
use App\Models\MyJob;
use App\Models\GuppaJob;
use App\Models\Milestone;
use App\Models\AppliedJob;
use App\Models\JobHistory;
use App\Models\JobTypeList;
use Illuminate\Support\Str;
use App\Models\GuppaKeyword;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Helpers\GeneralHelper;
use App\Events\JobAppliedEvent;
use App\Models\GuppaTransaction;
use App\Models\YearOfExperience;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use App\Notifications\JobCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Domain\Entities\ContractEntity;
use App\Domain\Entities\GuppaJobEntity;
use Illuminate\Support\Facades\Storage;
use App\Domain\Entities\AppliedJobEntity;
use App\Models\PendingApprovedJobPayment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Domain\Interfaces\Job\IGuppaJobService;
use App\Models\Notification as ModelsNotification;
use App\Domain\DTOs\Request\Jobs\ApplyJobRequestDto;
use App\Domain\DTOs\Request\Jobs\GuppaJobRequestDto;
use App\Domain\DTOs\Response\Jobs\ContractResponseDto;
use App\Domain\DTOs\Response\Jobs\GuppaJobResponseDto;
use App\Domain\DTOs\Response\Jobs\AppliedJobResponseDto;
use App\Domain\DTOs\Response\Jobs\ExtractedTextResponseDto;
use App\Domain\DTOs\Response\Jobs\ClientContractResponseDto;
use App\Domain\DTOs\Response\Jobs\RecommendedJobResponseDto;
use App\Domain\DTOs\Response\Jobs\ShowAppliedJobsResponseDto;
use App\Domain\DTOs\Response\Jobs\ClientAppliedJobsResponseDto;
use App\Domain\DTOs\Response\Jobs\FreelancerAppliedJobsResponseDto;

class GuppaJobService implements IGuppaJobService
{
    protected $_currentUser;
    protected $slug;
    const BASE_POINTS = 10;
    const MIN_POINTS = 5;
    const MAX_POINTS = 50;
    public function __construct(){
        $this->_currentUser = Auth::User();
    }

    public function upsertJob(Request $request)
    {
        if(Gate::denies('create_job', $this->_currentUser)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        try {
            $validator = Validator::make($request->all(), [
                'job_id' => 'nullable|integer',
                'client_id' => 'required|integer|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'tags' => 'nullable',
                'amount' => 'required|numeric|min:0',
                'time' => 'required|string',    
                'project_type' => 'required|string',
                'required_skills' => 'nullable|string',
                'experience_level' => 'nullable|string',
                'total_hour' => 'nullable|required_if:project_type,hourly|integer',
                'category' => 'nullable',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ",HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();

            $bid = Bid::where("user_id", $this->_currentUser->id)->first();
            if($bid == null || $bid->bid < 1){
               return new ApiResponseDto(false, "You need at least 1 bid point to post a job", HttpStatusCode::BAD_REQUEST);

            }

            $data = [
                'job_id' => $validated['job_id'],
                'client_id' => $validated['client_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'tags' => $validated['tags'],
                'amount' => $validated['amount'],
                'time' => $validated['time'],
                'project_type' => $validated['project_type'],
                'required_skills' => $validated['required_skills'],
                'experience_level' => $validated['experience_level'],
                'total_hour' => $validated['total_hour'],
                'category' => $validated['category']

            ];

            $dto = new GuppaJobRequestDto($data);

            $time_limit = $dto->project_type == "hourly"
                ? Carbon::now()->addHours($dto->total_hour)
                : null;


            if($dto->job_id == 0){
                $title = $dto->title;
                $this->slug = Str::slug($title);
                Log::info("Job title ". $title . " " . $this->slug);
                $existingSlug = GuppaJob::where('slug','like', '%'.$this->slug.'%')->count();
                Log::info("Count slug ". $existingSlug);

                if($existingSlug > 0 ){
                    Log::info("Existing title ". $title . " " . $this->slug . " " . $existingSlug);
                    $this->slug = $this->slug . '_' . rand(1111,9999);
                }
                Log::info("Slug ". $this->slug);

                //create
                $job = new GuppaJob();
                $job->user_id = $dto->client_id;
                $job->title = $title;
                $job->slug = $this->slug;
                $job->description = $dto->description;
                $job->amount = $dto->amount;
                $job->bid_points = 0;
                $job->time = $dto->time;
                $job->project_type = $dto->project_type;
                $job->required_skills = $dto->required_skills;
                $job->experience_level = $dto->experience_level;
                $job->time_limit = $time_limit;
                $job->category = $dto->category;

                if($job->save()){
                    if($dto->tags != null){
                        foreach(explode(',', $dto->tags) as $inputtedTag){
                            $tag = Tag::firstOrCreate([
                                'slug' => Str::slug($inputtedTag)
                            ], [
                                'tag' => ucwords(trim($inputtedTag))
                            ]);

                            $tag->jobs()->attach($job->id);
                        }
                    }

                    $assignBid = $this->calcBid($job->id);
                    $job->bid_points = $assignBid ?? 0;
                    $job->save();

                    $bid->bid -= 1;
                    $bid->save();

                }
                $his = new JobHistory();
                $his->action = "Job Created";
                $his->action_by = $dto->client_id;
                $his->guppa_job_id = $job->id;
                $his->description = GeneralHelper::UserDetail($dto->client_id)->last_name . " Created a new Job";
                $his->save();
                return new ApiResponseDto(true, "Job created", HttpStatusCode::CREATED);

            }else{
                //update
                $job = GuppaJob::findOrFail($dto->job_id);
                if(Gate::denies('update_job', $this->_currentUser, $job)){
                    return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
                }
                if($job !=null) {
                    if($dto->title != $job->title){
                        $this->slug = Str::slug($job->title);
                        Log::info("Job title ". $job->title . " " . $this->slug);
                        $existingSlug = GuppaJob::where('slug','like', '%'.$this->slug.'%')->count();
                        Log::info("Count slug ". $existingSlug);

                        if($existingSlug > 0 ){
                            Log::info("Existing title ". $job->title . " " . $this->slug . " " . $existingSlug);
                            $this->slug = $this->slug . '_' . rand(1111,9999);
                        }
                        Log::info("Slug ". $this->slug);
                    }

                    $job->user_id = $dto->client_id;
                    $job->title = $dto->title;
                    $job->slug = $this->slug;
                    $job->description = $dto->description;
                    $job->amount = $dto->amount;
                    $job->time = $dto->time;
                    $job->bid_points = $job->bid_points;
                    $job->project_type = $dto->project_type;
                    $job->category = $dto->category;


                    if ($job->save()) {
                        if ($dto->tags != null) {
                            foreach (explode(',', $dto->tags) as $inputtedTag) {
                                $tag = Tag::firstOrCreate([
                                    'slug' => Str::slug($inputtedTag)
                                ], [
                                    'tag' => ucwords(trim($inputtedTag))
                                ]);

                                $tag->jobs()->attach($job->id);
                            }
                        }
                        
                  

                    }
                    $his = new JobHistory();
                    $his->action = "Job Updated";
                    $his->guppa_job_id = $job->id;
                    $his->action_by = $dto->client_id;
                    $his->description = GeneralHelper::UserDetail($dto->client_id)->last_name . " Updated Job";
                    $his->save();
                    return new ApiResponseDto(true, "Job updated", HttpStatusCode::OK);

                }else{
                    return new ApiResponseDto(false, "Job not Found", HttpStatusCode::NOT_FOUND);
                }
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    private function calcBid($jobId){
        //get job 
        $job = GuppaJob::findOrFail($jobId);
        if($job != null){
             // Start with base points
            $points = self::BASE_POINTS;

            // Adjust points based on job complexity (e.g., description length)
            $points += $this->calculateComplexityPoints($job);

            // Adjust points based on job category
            $points += $this->calculateCategoryPoints($job->category);

            // Adjust points based on client's history
            $points += $this->calculateClientHistoryPoints($job->user_id);

            // Adjust points based on expected demand
            $points += $this->calculateDemandPoints($job);

            // Ensure points fall within defined limits
            $points = max(self::MIN_POINTS, min(self::MAX_POINTS, $points));

            return $points;
        }
        return 0;
    }

    public function getAllJobs()
    {
        try {
            if (Gate::denies('viewAny_job',  $this->_currentUser)) {
                return new ApiResponseDto(false, "You don't have permission to perform this action.", HttpStatusCode::UNAUTHORIZED);
            }

            $jobs = GuppaJob::with('tags')->orderBy("created_at", 'desc')->get();
            $dto = $jobs->map(function ($job) {
                $jobEntity = new GuppaJobEntity($job);
                return new GuppaJobResponseDto($jobEntity);
            });

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    private function calculateComplexityPoints(GuppaJob $job): int
    {
        $descriptionLength = strlen($job->description);
        
        if ($descriptionLength > 1000) {
            return 5; // Add points for long, detailed descriptions
        } elseif ($descriptionLength < 900) {
            return 3; // Moderate points for medium descriptions
        }
        return 0; // No extra points for short descriptions
    }

    /**
     * Calculate points based on job category.
     */
    private function calculateCategoryPoints($category): int
    {
         // High demand or high complexity categories
    $highDemandCategories = [
        'Web Development',
        'Mobile App Development',
        'Software Engineering',
        'Data Science',
        'Cybersecurity',
        'Digital Marketing',
        'UI/UX Design',
        'Finance',
        'Consulting',
        'Healthcare Services'
    ];

    // Medium demand or moderate complexity categories
    $mediumDemandCategories = [
        'Desktop Application Development',
        'Network Administration',
        'Graphic Design',
        'Content Writing',
        'Sales and Business Development',
        'Project Management',
        'Accounting',
        'Human Resources',
        'Customer Service',
        'Education and Tutoring',
        'Logistics and Supply Chain',
        'Manufacturing',
        'Quality Assurance',
        'Legal Services'
    ];

    // Low demand or less complex categories
    $lowDemandCategories = [
        'Administrative Support',
        'Real Estate',
        'Construction',
        'Plumbing',
        'Electrician Services',
        'Carpentry',
        'Automotive Repair',
        'Environmental Services',
        'Photography',
        'Video Production',
        'IT Support',
        'Data Entry',
        'Market Research',
        'Event Planning',
        'Fitness Training',
        'Translation Services',
        'Sales and Retail',
        'Food and Beverage Services',
        'Cleaning Services',
        'Landscaping',
        'Legal Consulting',
        'Personal Assistant Services'
    ];

    // Calculate points based on the category
        if (in_array($category, $highDemandCategories)) {
            return 8; // Higher points for high-demand categories
        } elseif (in_array($category, $mediumDemandCategories)) {
            return 5; // Medium points for medium-demand categories
        } elseif (in_array($category, $lowDemandCategories)) {
            return 2; // Lower points for low-demand categories
        } else {
            return 1; // Default points if the category doesn't match any array
        }
 }

    /**
     * Calculate points based on client history.
     */
    private function calculateClientHistoryPoints($client): int
    {
        // Example: Add points if client has high spending history
        $trans = GuppaTransaction::where("user_id", $client)->sum('amount');
        return $trans > 10000 ? 5 : 0;
    }

    /**
     * Calculate points based on expected job demand.
     */
    private function calculateDemandPoints(GuppaJob $job): int
    {
        // Example: Adjust points based on number of proposals typically received
        $similarJobs = GuppaJob::where('category', $job->category)
                          ->where('title', 'like', '%' . $job->title . '%')
                          ->count();

        return $similarJobs > 50 ? 5 : 0; // Add points if similar jobs are in high demand
    }
  
    public function getJobById(int $id)
    {
        try {
            $jobs = GuppaJob::with('tags')->where('id', $id)->first();
            if(Gate::denies('view_job',  $jobs)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($jobs != null) {

                $jobEntity = new GuppaJobEntity($jobs);
                $dto = new GuppaJobResponseDto($jobEntity);
                $jobs->views +=1;
                $jobs->save();
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getJobBySlug(string $slug)
    {
        try {
            $jobs = GuppaJob::with('tags')->where('slug', $slug)->first();
            if(Gate::denies('view_job',  $jobs)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($jobs != null) {

                $jobEntity = new GuppaJobEntity($jobs);
                $dto = new GuppaJobResponseDto($jobEntity);
                $jobs->views +=1;
                $jobs->save();
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyJobs()
    {
        if(Gate::denies('view_my_jobs', $this->_currentUser)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }

        try {
            $jobs = GuppaJob::where('user_id', $this->_currentUser->id)->orderBy("created_at", 'desc')->get();
            if($jobs != null) {
                $dto = $jobs->map(function ($job) {
                    $jobEntity = new GuppaJobEntity($job);
                    return new GuppaJobResponseDto($jobEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());

            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAvailableJobs(Request $request)
    {
        try {
            $filters = [
                'search' => $request->search,
                'most_recent' => $request->most_recent,
                'most_relevant' => $request->most_relevant,
                'job_type' => $request->job_type,
                'experience' =>  $request->experience != null ? explode('-',$request->experience) : "",
            ];

            $onMonthBack = Carbon::today()->subMonths(2);
            $queries = GuppaJob::with('tags')->available();
             $queries->when($filters['search'],
              fn($query, $search) => $query->where('title', 'like', '%'.$search.'%')
              ->orWhere('description', 'like', '%'.$search.'%')
                  ->orWhere('amount', 'like', '%'.$search.'%'))
              ->when($filters['most_recent'], fn($query) => $query->where('created_at', '>=', $onMonthBack)->orderBy('created_at','desc'))
              ->when($filters['most_relevant'], fn($query, $field) => $query->orderBy('relevance_score', 'desc'))
              ->when($filters['job_type'], fn($query, $field) => $query->where('project_type', $field));

            if ($request->has('experience')) {
                $queries->whereIn('experience_level', $filters['experience']);
             }


            $jobs = $queries->orderBy('created_at', 'desc')->get();
                if($filters['search'] != null){
                    $keywords = explode(' ', $filters['search']);
                    foreach($keywords as $keyword){
                        GuppaKeyword::updateOrCreate([
                            'keyword' => Str::lower($keyword)
                        ]);
                    }
                }

            if($jobs != null) {
                $dto = $jobs->map(function ($job) {
                    $jobEntity = new GuppaJobEntity($job);
                    return new GuppaJobResponseDto($jobEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());

            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getJobForMe()
    {
        if(Gate::denies('view_recommended_jobs', $this->_currentUser)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        $user = User::with(['on_boarded'])->where('id', $this->_currentUser->id)->first();
        Log::info(" user with on boarding ", [$user]);
        $onBoarding = $user->on_boarded;
        $skills = $onBoarding->skills; // required_skills
        $experience_level = $onBoarding->years_of_experience;  //experience_level
        $looking_for = $onBoarding->looking_for; //project_type

        $skills = explode(',', $skills);


        $queries = GuppaJob::where('experience_level', $experience_level)->orWhere('project_type', $looking_for)
            ->orWhere(function  ($query) use ($skills) {
                $query->whereIn('required_skills', $skills);
            })->available()->orderBy('created_at', 'desc')->limit(5);

        $queries =  $queries->get();
        if($queries != null) {
            $dto = $queries->map(function ($job) {
                $jobEntity = new GuppaJobEntity($job);
                return new RecommendedJobResponseDto($jobEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());

        }else{
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }
    }

    public function apply(Request $request)
    {
        if(Gate::denies('can_apply', $this->_currentUser)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        try {
            $validator = Validator::make($request->all(), [
                'guppa_job_id' => 'required|integer|exists:guppa_jobs,id',
                'user_id' => 'required|integer|exists:users,id',
                'project_timeline' => 'required|date',
                'cover_letter_file' => 'required|string',
                'cover_letter' => 'required|string',
                'payment_type' => 'required',
                'project_price' => 'required_if:payment_type,project',
                'milestone_description' => 'required_if:payment_type,milestone|array',
                'milestone_description.*' => 'required_if:payment_type,milestone|string',
                'milestone_amount' => 'required_if:payment_type,milestone|array',
                'milestone_amount.*' => 'required_if:payment_type,milestone|numeric|min:0',
                'total_milestone_price' => 'required_if:payment_type,milestone',
                'service_charge' => 'required',
                'total_payable' => 'required',
            ]);


            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ",HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();

            $data = [
                'job_id' => $validated['guppa_job_id'],
                'user_id' => $validated['user_id'],
                'project_timeline' => $validated['project_timeline'],
                'cover_letter_file' => $validated['cover_letter_file'],
                'cover_letter' => $validated['cover_letter'],
                'payment_type' => $validated['payment_type'],
                'milestone_description' => $validated['milestone_description'],
                'milestone_amount' => $validated['milestone_amount'],
                'total_milestone_price' => $validated['total_milestone_price'],
                'project_price' => $validated['project_price'],
                'service_charge' => $validated['service_charge'],
                'total_payable' => $validated['total_payable'],

             ];
             $job = GuppaJob::where('id', $validated['guppa_job_id'])->first();

             $bid = Bid::where("user_id", $validated['user_id'])->first();
             if($bid == null){
                return new ApiResponseDto(false, "You don't have bid points to apply for this job", HttpStatusCode::BAD_REQUEST);

             }else{
                if($bid->bid > 0 && $bid->bid < $job->bid_points){
                    return new ApiResponseDto(false, "You don't have enough bid points to apply for this job", HttpStatusCode::BAD_REQUEST);
                }
             }

            $dto = new ApplyJobRequestDto($data);
             Log::info("milestones description", [$dto->milestone_description]);
             Log::info("milestones ", [$dto->milestone_amount]);

            if($dto->project_price != 0.0){
                $service_charge = $dto->ServiceCharge($dto->project_price);
                $dto->total_payable = $dto->calculateTotalPayable($dto->project_price, $service_charge['service_charge']);
                $dto->service_charge = $service_charge['amount'];
            }
            if($dto->total_milestone_price != 0.0){
                $dto->total_milestone_price = $dto->calculateTotalMilestonePrice();
                $service_charge = $dto->ServiceCharge($dto->total_milestone_price);
                $dto->total_payable = $dto->calculateTotalPayable($dto->total_milestone_price, $service_charge['service_charge']);
                $dto->service_charge = $service_charge['amount'];
            }

            if($job != null) {
                $applied = new AppliedJob();
                $applied->user_id = $dto->user_id;
                $applied->guppa_job_id = $dto->job_id;
                $applied->project_timeline = $dto->project_timeline;
                $applied->cover_letter_file = $dto->cover_letter_file;
                $applied->cover_letter = $dto->cover_letter;
                $applied->payment_type = $dto->payment_type;
                $applied->project_price = $dto->project_price;
                $applied->total_milestone_price = $dto->total_milestone_price;
                $applied->total_amount_payable = $dto->total_payable;
                $applied->service_charge = $dto->service_charge;
                $applied->bid_point = $job->bid_points;
                $applied->status = "awaiting";
                if($applied->save()){
                    if ($dto->milestone_description != [] && $dto->milestone_amount != []) {
                        foreach ($dto->milestone_description as $index => $description) {
                            $milestone = new Milestone();
                            $milestone->applied_job_id = $applied->id;
                            $milestone->milestone_description = $description;
                            $milestone->milestone_amount = $dto->milestone_amount[$index];
                            $milestone->save();
                        }
                    }

                    $bid->bid -= $job->bid_points;
                    $bid->save();

                    $his = new JobHistory();
                    $his->action = "Applied for job";
                    $his->action_by = $dto->user_id;
                    $his->guppa_job_id = $dto->job_id;
                    $his->description = GeneralHelper::UserDetail($dto->user_id)->last_name . " Applied for a Job";
                    $his->save();

                    $job->applications += 1;
                    $job->save();
                    // send email to user and client
                    $freelancer = User::where('id', $dto->user_id)->first();
                    $client = User::where('id', $job->user_id)->first();

                    $notification = new ModelsNotification();
                    $notification->user_id = $client->id;
                    $notification->title = "Job Application";
                    $notification->message = $freelancer->first_name ." have applied for job with title ". $job->title;
                    $notification->created_at = Carbon::now();
                    $notification->isRead = false;
                    $notification->save();
                    event(new JobAppliedEvent($job, $freelancer, $client, $applied->id));
                    //end send email to user and client
                    return new ApiResponseDto(true, "Successful", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Job Application Failed", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function extractText(Request $request)
    {
      try {
        $filePath = str_replace(asset('storage/app/public/uploads/'), '', $request->file);
        $file = storage_path('uploads/' . $filePath);
        if (Storage::disk('public')->exists("uploads/".$filePath)) {
            // Get the file from storage
            // $file  = Storage::disk('public')->url("uploads/".$filePath);
             //check if the file exists
        if(explode('.', $request->file)[1] != 'pdf'){
            return new ApiResponseDto(false, "File type not supported, only pdf is required!", HttpStatusCode::BAD_REQUEST);
        }
            $content =  GeneralHelper::extractText($file);
            $dto = new ExtractedTextResponseDto($content);
            return new ApiResponseDto(true, "content", HttpStatusCode::OK, $dto);

        }else{
            return new ApiResponseDto(false, "File Not Found", HttpStatusCode::NOT_FOUND);

        }

      } catch (\Exception $e) {
         //return server error
         return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
      }
    }

    public function getAppliedJobs($jobId){
        try {
            if(Gate::denies('viewAny_appliedJob', $this->_currentUser)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            $applications = AppliedJob::with('milestones')->where(['guppa_job_id' => $jobId])->get();

            if($applications->isNotEmpty()){
                $dto = $applications->map( function($app) {
                    $appEntity = new AppliedJobEntity($app);
                    return new AppliedJobResponseDto($appEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Applications Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function getClientAppliedJobs(int $jobId){
        try {
            if(Gate::denies('viewAny_client_appliedJob', $this->_currentUser)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            $applications = AppliedJob::with('milestones')->where(['guppa_job_id' => $jobId])->orderBy("created_at", 'desc')->get();
            if($applications->isNotEmpty()){
                $dto = $applications->map(function($app) {
                    $appEntity = new AppliedJobEntity($app);
                    return new ClientAppliedJobsResponseDto($appEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Applications Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function getAppliedJob(int $applied_id){
        try {
           
            $application = AppliedJob::with('milestones')->where(['id' => $applied_id])->first();
            if(Gate::denies('view_AppliedJob', $this->_currentUser)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($application != null){

                $appEntity = new AppliedJobEntity($application);
                $dto = new ShowAppliedJobsResponseDto($appEntity);

                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Application Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getFreelancerAppliedJobs(){
        try {
            
            $applications = AppliedJob::with('milestones')->where(['user_id' => $this->_currentUser->id])->orderBy("created_at", 'desc')->get();
            
            if($applications->isNotEmpty()){
                $dto = $applications->map(function($app) {
                    $appEntity = new AppliedJobEntity($app);
                    return new FreelancerAppliedJobsResponseDto($appEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Applications Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function getFreelancerAppliedJob(int $applied_id){
        try {

            $application = AppliedJob::with('milestones')->where(['id' => $applied_id])->first();
          
            if($application != null){

                $appEntity = new AppliedJobEntity($application);
                $dto = new ShowAppliedJobsResponseDto($appEntity);

                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Application Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function approveJob(int $applied_id){
        try{
            
            $application = AppliedJob::with('milestones')->where(['id' => $applied_id])->first();
            if($application != null){
                $job = GuppaJob::where('id', $application->guppa_job_id)->first();
                if(Gate::denies('approve_job', $job)){
                    return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
                }

                if($job->job_status == "taken"){
                    return new ApiResponseDto(false, "You have already approved this job for another freelancer!", HttpStatusCode::CONFLICT);

                }
                $application->status = "approved";
                $application->date_approved = Carbon::now();
                $application->save(); 

                $job->job_status = "taken";
                $job->save();

                $payment = new PendingApprovedJobPayment();
                $payment->client_id = $this->_currentUser->id;
                $payment->applied_id =  $application->id;
                $payment->job_title = $job->title;
                if($application->payment_type == "milestone"){
                    $payment->amount = $application->total_milestone_price;
                }else{
                    $payment->amount = $application->project_price;
                }
                $payment->status = "pending";
                $payment->created_at = Carbon::now();
                $payment->save();
               

                return new ApiResponseDto(true, "Job Accepted, Freelancer will be notified once you make payment to guppa", HttpStatusCode::ACCEPTED);

            }else{
                return new ApiResponseDto(false, "No Job Application Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function rejectJob(int $applied_id){
        try{
          
            $application = AppliedJob::with('milestones')->where(['id' => $applied_id])->first();
            if(Gate::denies('reject_job', $application)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($application != null){
                $job = GuppaJob::where('id', $application->guppa_job_id)->first();

                $application->status = "rejected";
                $application->updated_at = Carbon::now();
                $application->save();

                
                $notification = new ModelsNotification();
                $notification->user_id = $application->user_id;
                $notification->title = "Job Rejected";
                $notification->message = "Your Job application ". $job->title ." have been rejected";
                $notification->created_at = Carbon::now();
                $notification->isRead = false;
                $notification->save();
                return new ApiResponseDto(true, "Job Rejected", HttpStatusCode::OK);

            }else{
                return new ApiResponseDto(false, "No Job Application Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteJob(int $jobId){
        try{
            $job = GuppaJob::where(['id' => $jobId])->first();
            if(Gate::denies('delete_job',  $job)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($job != null){
                $job->delete();
                return new ApiResponseDto(true, "Job Deleted", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "No Job  Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function deleteAppliedJob(int $applied_id){
        try{
            $job = AppliedJob::where(['id' => $applied_id])->first();
            if(Gate::denies('delete_job', $job)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($job != null){
                $job->delete();
                return new ApiResponseDto(true, "Job Application Deleted", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "No Job Application Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getContracts(){
        try {
           
            $contracts = MyJob::where(['user_id' => $this->_currentUser->id])->orderBy("created_at", 'desc')->get();
            if(Gate::denies('view_contracts', $contracts)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($contracts->isNotEmpty()){
                $dto = $contracts->map(function($app) {
                    $appEntity = new ContractEntity($app);
                    return new ContractResponseDto($appEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Applications Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function getContract(int $id){
        try {
            $contract = MyJob::where(['id' => $id])->first();
            if(Gate::denies('view_contract', $contract)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($contract != null){
                $totalEarnings = GeneralHelper::CalTotalEarning($contract->applied_job_id);
                $contract->total_earnings = $totalEarnings;
                $contract->save();
                $appEntity = new ContractEntity($contract);
                $dto =  new ContractResponseDto($appEntity);

                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Contract Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getContractsForClient(){
        try {
            $contracts = MyJob::where(['client_id' => $this->_currentUser->id])->orderBy("created_at", 'desc')->get();
            if(Gate::denies('view_client_contracts', $contracts)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($contracts->isNotEmpty()){
                $dto = $contracts->map(function($app) {
                    $appEntity = new ContractEntity($app);
                    return new ClientContractResponseDto($appEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Job Applications Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function getContractForClient(int $id){
        try {
            $contract = MyJob::where(['id' => $id])->first();
            if(Gate::denies('view_client_contract', $contract)){
                return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
            }
            if($contract != null){
                $totalEarnings = GeneralHelper::CalTotalEarning($contract->applied_job_id);
                Log::info("total earnings updated ", [$totalEarnings]);
                $contract->total_earnings = $totalEarnings;
                $contract->save();
                $appEntity = new ContractEntity($contract);
                $dto =  new ClientContractResponseDto($appEntity);

                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Contract Found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateFreelancerStatus(int $contract_id){
        $contract = MyJob::where(['id' => $contract_id])->first();
        if(Gate::denies('update_status', $contract)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        if($contract != null){
            $contract->status = "Awaiting Review";
            $contract->save();
            $client = User::where('id', $contract->client_id)->first();
            $user = User::where('id', $this->_currentUser->id)->first();
            $job = GuppaJob::findOrFail($contract->guppa_job_id);
            Notification::sendNow($client, new JobCompletion($job, $user));
            //return success
            $notification = new ModelsNotification();
            $notification->user_id = $client->id;
            $notification->title = "Job Status Updated";
            $notification->message = $user->first_name ." have updated status of job ". $job->title ." to awaiting review";
            $notification->created_at = Carbon::now();
            $notification->isRead = false;
            $notification->save();
            return new ApiResponseDto(true, "Contract updated", HttpStatusCode::OK);
        }else{
            return new ApiResponseDto(false, "No Contract Found", HttpStatusCode::NOT_FOUND);
        }
    }

    public function updateClientStatus(int $contract_id){
        $contract = MyJob::where(['id' => $contract_id])->first();
        if(Gate::denies('update_status', $contract)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        if($contract != null){
            $contract->status = "Done";
            $contract->save();
            $user = User::where('id', $contract->user_id)->first();
            //job
            $job = GuppaJob::findOrFail($contract->guppa_job_id);
            $notification = new ModelsNotification();
            $notification->user_id = $user->id;
            $notification->title = "Job Status Updated";
            $notification->message = $this->_currentUser->first_name ." have updated status of job ". $job->title ." to done. you will receive from guppa once verified";
            $notification->created_at = Carbon::now();
            $notification->isRead = false;
            $notification->save();
            //return success
            return new ApiResponseDto(true, "Contract updated", HttpStatusCode::OK);
        }else{
            return new ApiResponseDto(false, "No Contract Found", HttpStatusCode::NOT_FOUND);
        }
    }

    public function updateProgress(int $contract_id, int $progress){
        $contract = MyJob::where(['id' => $contract_id])->first();
        if(Gate::denies('update_progress', $contract)){
            return new ApiResponseDto(false, 'You are not authorized to perform this action', HttpStatusCode::UNAUTHORIZED);
        }
        if($contract != null){
            $contract->progress = $progress;
            $contract->save();
            //return success
            return new ApiResponseDto(true, "Contract progress updated", HttpStatusCode::OK);
        }else{
            return new ApiResponseDto(false, "No Contract Found", HttpStatusCode::NOT_FOUND);
        }
    }

    public function updateMilestoneProgress(int $milestone_id, string $progress){
        $milestone = Milestone::where(['id' => $milestone_id])->first();
       
        if($milestone != null){
            $milestone->status = $progress;
            $milestone->save();

            //return success
            return new ApiResponseDto(true, "Milestone progress updated", HttpStatusCode::OK);
        }else{
            return new ApiResponseDto(false, "No milestone Found", HttpStatusCode::NOT_FOUND);
        }
    }
}
