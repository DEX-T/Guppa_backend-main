<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Domain\Interfaces\Reports\IReportService;

class ReportsController extends Controller
{
    private IReportService $_reportService;

    function __construct(IReportService $reportService)
    {
        $this->_reportService = $reportService;

    }

    /**
     * @OA\POST(
     *     path="/api/report/users-report",
     *     summary="Get users report data with filters",
     *     tags={"Reports"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=false,
     *      description="Filter by status (either 'active' or 'inactive')",
     *          @OA\Schema(
     *          type="string",
     *          enum={"active", "inactive"},
     *          )
     *      ),
     *     @OA\Parameter(
     *       name="role",
     *       in="query",
     *       required=false,
     *       description="Filter by role (either 'freelancer' or 'client')",
     *           @OA\Schema(
     *           type="string",
     *           enum={"freelancer", "client"},
     *           )
     *       ),
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         required=false,
     *         description="Filter by country",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getUsersReport(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->_reportService->getUsersReport($request);
       return response()->json([
           'success' => $data->status,
           'message' => $data->message,
           'data' => $data->data
       ], $data->code);
    }


    /**
     * @OA\POST(
     *     path="/api/report/jobs-report",
     *     summary="Get jobs report data with filters",
     *     tags={"Reports"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=false,
     *      description="Filter by status (either 'active' or 'inactive')",
     *          @OA\Schema(
     *          type="string",
     *          enum={"active", "inactive"},
     *          )
     *      ),
     *     @OA\Parameter(
     *       name="job_status",
     *       in="query",
     *       required=false,
     *       description="Filter by job status (either 'available' or 'taken')",
     *           @OA\Schema(
     *           type="string",
     *           enum={"available", "taken"},
     *           )
     *       ),
     *     @OA\Parameter(
     *         name="job_visibility",
     *         in="query",
     *         required=false,
     *         description="Filter by job visibility (either 'public' or 'invite')",
     *            @OA\Schema(
     *            type="string",
     *            enum={"public", "invite"},
     *            )
     *        ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getJobsReport(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->_reportService->getJobsReport($request);
        return response()->json([
            'success' => $data->status,
            'message' => $data->message,
            'data' => $data->data
        ], $data->code);
    }


    /**
     * @OA\POST(
     *     path="/api/report/applied-jobs-reports",
     *     summary="Get applied jobs report data with filters",
     *     tags={"Reports"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=false,
     *      description="Filter by status (either 'awaiting', 'approved' or 'rejected')",
     *          @OA\Schema(
     *          type="string",
     *          enum={"awaiting", "approved", "rejected"},
     *          )
     *      ),
     *     @OA\Parameter(
     *       name="payment_type",
     *       in="query",
     *       required=false,
     *       description="Filter by payment type (either 'project' or 'milestone')",
     *           @OA\Schema(
     *           type="string",
     *           enum={"project", "milestone"},
     *           )
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getAppliedJobsReport(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $this->_reportService->getAppliedJobsReport($request);
        return response()->json([
            'success' => $data->status,
            'message' => $data->message,
            'data' => $data->data
        ], $data->code);
    }


    /**
     * @OA\POST(
     *     path="/api/report/contracts-reports",
     *     summary="Get contracts  report data with filters",
     *     tags={"Reports"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=false,
     *      description="Filter by status (either 'Awaiting Review', 'In Progress' or 'Done')",
     *          @OA\Schema(
     *          type="string",
     *          enum={"Awaiting Review", "In Progress", "Done"},
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getContractsReport(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->_reportService->getContractsReport($request);
        return response()->json([
            'success' => $data->status,
            'message' => $data->message,
            'data' => $data->data
        ], $data->code);
    }

    /**
     * @OA\POST(
     *     path="/api/report/transaction-reports",
     *     summary="Get transactions report data with filters",
     *     tags={"Reports"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getTransactionReport(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->_reportService->getTransactionReport($request);
        return response()->json([
            'success' => $data->status,
            'message' => $data->message,
            'data' => $data->data
        ], $data->code);
    }



}
