<?php

namespace App\Http\Controllers\Job;

use App\Domain\Interfaces\Invites\IInviteFreelancerService;
use App\Domain\Interfaces\Job\IGuppaJobService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class JobController extends Controller
{
    private IGuppaJobService $_jobService;
    private IInviteFreelancerService $_invite;

    function __construct(IGuppaJobService $jobService, IInviteFreelancerService $invite)
    {
        $this->_jobService = $jobService;
        $this->_invite = $invite;
    }


    /**
     * @OA\Post(
     *     path="/api/job/upsert-job",
     *     operationId="upsertJob",
     *     tags={"Job"},
     *     security={{"sanctum":{}}},
     *     summary="Create or Update Job",
     *     description="Create a new job or update an existing job, job_id is set to 0 for creating new job",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"job_id", "client_id", "title", "description", "amount", "time",  "project_type", "required_skills"},
     *             @OA\Property(property="job_id", type="integer", example=0, description="Job ID (use 0 for creating a new job)"),
     *             @OA\Property(property="client_id", type="integer", example=1, description="Client ID"),
     *             @OA\Property(property="title", type="string", example="Job Title", maxLength=255, description="Title of the job"),
     *             @OA\Property(property="description", type="string", example="Job description goes here", description="Description of the job"),
     *             @OA\Property(property="tags", type="string", example="ux/ui,backend,frontend", description="Tags related to the job"),
     *             @OA\Property(property="amount", type="number", format="float", example=100.00, minimum=0, description="Amount for the job"),
     *             @OA\Property(property="time", type="string", example="per hour", description="Time attached to the payment"),
     *             @OA\Property(property="project_type", type="string", example="hourly", enum={"hourly", "contract"}, description="Project type: hourly or contract"),
     *             @OA\Property(property="required_skills", type="string", example="javascript,php,laravel", description="Required Skills for the job"),
     *             @OA\Property(property="experience_level", type="string", example="2_3 years", description="experience level eg 2 to 3 years"),
     *             @OA\Property(property="total_hour", type="int", example="0", description="Set total hour the job should take")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Job created successfully",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     )
     * )
     */
    public function upsertJob(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_jobService->upsertJob($request);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'error' => $status->data
        ], $status->code);
    }


    /**
     * @OA\Get(
     *     path="/api/job/get_Job_by_slug/{slug}",
     *     operationId="getJobBySlug",
     *     tags={"Job"},
     *     summary="Get job by slug",
     *     description="Returns job by slug",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    //Get  navbar type by id
    public function getJobBySlug(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getJobBySlug($request->slug);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);

    }

   /**
     * @OA\Get(
     *     path="/api/job/get_job_by_id/{job_id}",
     *     operationId="getJobById",
     *     tags={"Job"},
     *     summary="Get job by job_id",
     *     description="Returns job by job_id",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="job_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getJobById(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getJobById($request->job_id);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);

    }



    /**
     * @OA\Get(
     *     path="/api/job/all_jobs",
     *     operationId="getAllJobs",
     *     tags={"Job"},
     *     summary="Get list of all jobs",
     *     description="Returns list of all jobs",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllJobs(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getAllJobs();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\Get(
     *     path="/api/job/available_jobs",
     *     operationId="getAllAvailableJobs",
     *     tags={"Job"},
     *     summary="Get list of all available jobs",
     *     description="Returns list of available jobs",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search term to filter jobs by title or description"
     *     ),
     *     @OA\Parameter(
     *         name="most_recent",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Flag to sort jobs by most recent"
     *     ),
     *     @OA\Parameter(
     *         name="most_relevant",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Flag to sort jobs by most relevant"
     *     ),
     *     @OA\Parameter(
     *          name="experience",
     *          in="query",
     *          description="Comma-separated list of experience levels",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="job_type",
     *          in="query",
     *          description="Comma-separated list of job types",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *         )
     *     )
     * )
     */
    //Get All jobs
    public function getAllAvailableJobs(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getAvailableJobs($request);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }


    /**
     * @OA\Get(
     *     path="/api/job/my_jobs",
     *     operationId="getMyJobs",
     *     tags={"Job"},
     *     summary="Get client list of jobs",
     *     description="Returns list of client jobs",
     *     security={{"sanctum":{}}},
     
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getMyJobs(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getMyJobs();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }


    

    /**
     * @OA\Get(
     *     path="/api/job/recommended_jobs",
     *     operationId="recommendedJobs",
     *     tags={"Job"},
     *     summary="Get recommended jobs for a logged in freelancer",
     *     description="Returns list of recommended jobs for a logged in freelancer",
     *     security={{"sanctum":{}}},
     
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Recommended Jobs found"
     *     )
     * )
     */

    public function getRecommendedJobs(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_jobService->getJobForMe();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    } 

    
    /**
     * @OA\Post(
     *     path="/api/job/apply",
     *     tags={"Job"},
     *     summary="Apply for a job",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"guppa_job_id", "user_id", "project_timeline", "cover_letter_file", "cover_letter", "payment_type", "service_charge", "total_payable"},
     *             @OA\Property(property="guppa_job_id", type="integer", description="ID of the job", example=0),
     *             @OA\Property(property="user_id", type="integer", description="ID of the user", example=0),
     *             @OA\Property(property="project_timeline", type="string", format="date", description="Project timeline", example="2023-06-30"),
     *             @OA\Property(property="cover_letter_file", type="string", format="binary", description="Cover letter file"),
     *             @OA\Property(property="cover_letter", type="string", description="Cover letter text", example="I am interested in this job because..."),
     *             @OA\Property(property="payment_type", type="string", description="Payment type (project or milestone)", example="project"),
     *             @OA\Property(property="project_price", type="number", description="Project price, required if payment type is project", example=500),
     *             @OA\Property(property="service_charge", type="number", description="Guppa Service Charge", example=50),
     *             @OA\Property(property="total_payable", type="number", description="Total payable", example=450),
     *             @OA\Property(
     *                 property="milestone_description",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 description="Milestone descriptions, required if payment type is milestone",
     *                 example={"Design phase", "Development phase"}
     *             ),
     *             @OA\Property(
     *                 property="milestone_amount",
     *                 type="array",
     *                 @OA\Items(type="number", format="float"),
     *                 description="Milestone amounts, required if payment type is milestone",
     *                 example={100, 400}
     *             ),
     *             @OA\Property(property="total_milestone_price", type="number", description="Total milestone price, required if payment type is milestone", example=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Application submitted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="success", type="boolean"), @OA\Property(property="message", type="string"), @OA\Property(property="data", type="object"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(type="object", @OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function apply(Request $request)
    {
        $application = $this->_jobService->apply($request);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }


    /**
     * @OA\Get(
     *     path="/api/job/applied-jobs/{jobId}",
     *     summary="Get applied jobs for a specific job",
     *      tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="jobId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getAppliedJobs(Request $request)
    {
        $application = $this->_jobService->getAppliedJobs($request->jobId);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Get(
     *     path="/api/job/client-applied-jobs/{jobId}",
     *     summary="Get client applied jobs for a specific job",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="jobId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getClientAppliedJobs(Request $request)
    {
        $application = $this->_jobService->getClientAppliedJobs($request->jobId);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

     /**
     * @OA\Get(
     *     path="/api/job/freelancer-applied-jobs",
     *     summary="Get freelancer applications for a specific job",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getFreelancerAppliedJobs(Request $request)
    {
        $application = $this->_jobService->getFreelancerAppliedJobs();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }



    /**
     * @OA\Get(
     *     path="/api/job/applied-job/{applied_id}",
     *     summary="Get a specific applied job",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="applied_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the applied job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getAppliedJob(Request $request)
    {
        $application = $this->_jobService->getAppliedJob($request->applied_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Get(
     *     path="/api/job/freelancer-applied-job/{applied_id}",
     *     summary="Get a specific applied job",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="applied_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the applied job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getFreelancerAppliedJob(Request $request)
    {
        $application = $this->_jobService->getFreelancerAppliedJob($request->applied_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }



    /**
     * @OA\Post(
     *     path="/api/job/approve-job/{applied_id}",
     *     summary="Accept Freelance job proposal",
     *      description="Accept Freelancer Proposal",
     *      tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="applied_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the applied job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job Approved",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function approveJob(Request $request)
    {
        $application = $this->_jobService->approveJob($request->applied_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }

    /**
     * @OA\Post(
     *     path="/api/job/reject-job/{applied_id}",
     *     summary="Reject Job proposal",
     *     description="Reject Freelancer Proposal",
     *      tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="applied_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the applied job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job Rejected"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function rejectJob(Request $request)
    {
        $application = $this->_jobService->rejectJob($request->applied_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }

     /**
     * @OA\Delete(
     *     path="/api/job/delete-job/{job_id}",
     *     summary="Delete  job",
     *      tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="job_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the job"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job Deleted"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function deleteJob(Request $request)
    {
        $application = $this->_jobService->deleteJob($request->job_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }

     /**
     * @OA\Delete(
     *     path="/api/job/delete-application/{applied_id}",
     *     summary="Delete job Application",
     *      tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="applied_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the job application"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job Application Deleted"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function deleteApplicationJob(Request $request)
    {
        $application = $this->_jobService->deleteAppliedJob($request->applied_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }



    /**
     * @OA\Get(
     *     path="/api/job/contracts",
     *     summary="Get list of  contracts",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
    
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function contracts(Request $request)
    {
        $application = $this->_jobService->getContracts();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    
    /**
     * @OA\Get(
     *     path="/api/job/contract/{contract_id}",
     *     summary="Get contract detail",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="contract_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the contract"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function contract(Request $request)
    {
        $application = $this->_jobService->getContract($request->contract_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Get(
     *     path="/api/job/contracts_for_client",
     *     summary="Get list of  contracts for client",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function contractsForClient(Request $request)
    {
        $application = $this->_jobService->getContractsForClient();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    
    /**
     * @OA\Get(
     *     path="/api/job/contract_for_client/{contract_id}",
     *     summary="Get contract detail for client",
     *     tags={"Job"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="contract_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the contract"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function contractForClient(Request $request)
    {
        $application = $this->_jobService->getContractForClient($request->contract_id);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

/**
 * @OA\Put(
 *     path="/api/job/contract/update-freelancer/{contract_id}",
 *     operationId="updateContractStatus",
 *     tags={"Job"},
 *     security={{"sanctum":{}}},
 *     summary="Freelancer Update contract status",
 *     description="Update contract status",
 *      @OA\Parameter(
 *         name="contract_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the contract"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateContractStatusFreelancer(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_jobService->updateFreelancerStatus($request->contract_id);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message
        ], $update->code);
    
}


   /**
 * @OA\Put(
 *     path="/api/job/contract/update-client/{contract_id}",
 *     operationId="updateClientContractStatus",
 *     tags={"Job"},
 *     security={{"sanctum":{}}},
 *     summary="Client Update contract status",
 *     description="Update contract status",
 *      @OA\Parameter(
 *         name="contract_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the contract"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateContractStatusClient(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_jobService->updateClientStatus($request->contract_id);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message
        ], $update->code);
    
}

  /**
 * @OA\Put(
 *     path="/api/job/update-progress/{contract_id}/{progress}",
 *     operationId="updateContractProgress",
 *     tags={"Job"},
 *     security={{"sanctum":{}}},
 *     summary="Client Update contract progress",
 *     description="Update contract progress",
 *      @OA\Parameter(
 *         name="contract_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the contract"
 *     ),
 *      @OA\Parameter(
 *         name="progress",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Progress of the contract, eg 10 or 20 or 30 or 40 or 50 to 100"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateProgress(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_jobService->updateProgress($request->contract_id, $request->progress);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message
        ], $update->code);
    
}


  /**
 * @OA\Put(
 *     path="/api/job/update-milestone-progress/{milestone_id}/{progress}",
 *     operationId="updateMilestoneProgress",
 *     tags={"Job"},
 *     security={{"sanctum":{}}},
 *     summary="Update milestone progress",
 *     description="Update milestone progress",
 *      @OA\Parameter(
 *         name="milestone_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the milestone"
 *     ),
 *      @OA\Parameter(
 *         name="progress",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Progress of the contract, eg completed or in progress"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateMilestoneProgress(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_jobService->updateMilestoneProgress($request->milestone_id, $request->progress);
        return response()->json([
            'success' => $update->status,
            'message' => $update->message
        ], $update->code);
    
}

    /**
     * @OA\Get(
     *     path="/api/job/extract_cover_letter",
     *     operationId="extractCoverLetter",
     *     tags={"Job"},
     *     summary="Extract text from uploaded cover letter",
     *     description="text cover letter content",
     *     security={{"sanctum":{}}},
     *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"file"},
     *             @OA\Property(property="file", type="string", description="file path", example="string")
     *          )
     *      ),   
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

     public function extractText(Request $request): \Illuminate\Http\JsonResponse
     {
         $roleDto = $this->_jobService->extractText($request);
         return response()->json([
             'success' => $roleDto->status,
             'message' => $roleDto->message,
             'data' => $roleDto->data
         ]);
 
     }

     
  /**
     * @OA\Post(
     *     path="/api/invites/search-freelancer",
     *     operationId="searchForFreelancer",
     *     tags={"Invites"},
     *     security={{"sanctum":{}}},
     *     summary="Search for freelancers",
     *     description="Search for freelancers and then invite any for job bidding",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"job_id", "skills", "ratings", "experience"},
     *                 @OA\Property(
     *                     property="job_id",
     *                     type="integer",
     *                     example=0,
     *                     description="Job ID"
     *                 ),
     *                 @OA\Property(
     *                     property="skills",
     *                     type="string",
     *                     example="javascript,laravel or tailor,fashion designer",
     *                     description="skills, separated by comma"
     *                 ),
     *                 @OA\Property(
     *                     property="ratings",
     *                     type="integer",
     *                     example=1,
     *                     description="ratings by numeric",
     *                     maximum=5
     *                 ),
     *                 @OA\Property(
     *                     property="experience",
     *                     type="string",
     *                     example="2_3years,4_5years"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="freelancer found",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="freelancer not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     )
     * )
     */
    public function search_freelancer(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_invite->searchFreelancer($request);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
        ], $status->code);
    }


      /**
     * @OA\Get(
     *     path="/api/invites/only-invites-jobs",
     *     operationId="onlyInvitesJobs",
     *     summary="Get Jobs that are only for invites",
     *     description="Return lists of client jobs that it's visibility is invites only",
     *     tags={"Invites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function onlyInvites()
    {
        $application = $this->_invite->invitesOnlyJobs();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Post(
     *     path="/api/invites/invite-freelancer",
     *     operationId="inviteForFreelancer",
     *     tags={"Invites"},
     *     security={{"sanctum":{}}},
     *     summary="Invite freelancer to bid for job",
     *     description="Invite freelancer to bid for job",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"freelancer_id", "job_id", "description"},
     *             @OA\Property(property="freelancer_id", type="integer", example=0, description="Freelancer Id"),
     *             @OA\Property(property="job_id", type="int", example=0, description="job id"),
     *             @OA\Property(property="description", type="string", example="description")
     *         )
     *     ),
     *    
     *     @OA\Response(
     *         response=200,
     *         description="invite sent",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     )
     * )
     */
    public function invite_freelancer(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_invite->inviteFreelancer($request);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'error' => $status->data
        ], $status->code);
    }


    /**
     * @OA\Get(
     *     path="/api/invites/search-history",
     *     operationId="searchHistory",
     *     summary="Get invites search history",
     *     description="Return all searches made why searching for freelancer",
     *     tags={"Invites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function searchHistory()
    {
        $application = $this->_invite->searchHistory();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

     /**
     * @OA\Get(
     *     path="/api/invites/invites-sent",
     *     operationId="invitesSent",
     *     summary="Get all invites that was sent",
     *     description="Return all list of invites sent",
     *     tags={"Invites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function InvitesSent()
    {
        $application = $this->_invite->InvitesSent();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

      /**
     * @OA\Get(
     *     path="/api/invites/my-invites",
     *     operationId="myInvites",
     *     summary="Get all my invites",
     *     description="Return all list of invites sent to me",
     *     tags={"Invites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function MyInvites()
    {
        $application = $this->_invite->MyInvites();

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
            'data' => $application->data,
        ], $application->code);
    }

    /**
     * @OA\Put(
     *     path="/api/invites/accept_invite/{invite_id}",
     *     operationId="acceptInvite",
     *     tags={"Invites"},
     *     security={{"sanctum":{}}},
     *     summary="Accept Invitation",
     *     description="Accept Invitation",
     *      @OA\Parameter(
     *         name="invite_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the invitation"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     ),
     * )
     */
    public function acceptInvite(Request $request): \Illuminate\Http\JsonResponse
    {
        $update = $this->_invite->acceptInvite($request->invite_id);
            return response()->json([
                'success' => $update->status,
                'message' => $update->message
            ], $update->code);
        
    }

    /**
     * @OA\Put(
     *     path="/api/invites/decline_invite/{invite_id}",
     *     operationId="declineInvite",
     *     tags={"Invites"},
     *     security={{"sanctum":{}}},
     *     summary="Decline Invitation",
     *     description="Decline Invitation",
     *      @OA\Parameter(
     *         name="invite_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the invitation"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     ),
     * )
     */
    public function declineInvite(Request $request): \Illuminate\Http\JsonResponse
    {
        $update = $this->_invite->declineInvite($request->invite_id);
            return response()->json([
                'success' => $update->status,
                'message' => $update->message
            ], $update->code);
        
    }


}
