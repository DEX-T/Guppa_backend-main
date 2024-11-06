<?php

namespace App\Http\Controllers\Analytics;

use App\Domain\Interfaces\Analytics\IAnalyticsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    private IAnalyticsService $_analyticService;

    function __construct(IAnalyticsService $analyticsService)
    {
        $this->_analyticService = $analyticsService;
    }

    /**
     * @OA\Get(
     *     path="/api/analytics/user-growth",
     *     operationId="getUserGrowth",
     *     tags={"Analytics"},
     *     summary="Get user growth",
     *     description="Retrieves user growth data over time.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User growth data retrieved",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getUserGrowth(): \Illuminate\Http\JsonResponse
    {
        $data = $this->_analyticService->getUserGrowth();
        return response()->json([
            'success' => true,
            'message' => "growth analysis",
            'data' => $data
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/analytics/user-demographics",
     *     operationId="getUserDemographics",
     *     tags={"Analytics"},
     *     summary="Get user demographics",
     *     description="Retrieves user demographics data by age group.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User demographics data retrieved"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getUserDemographics(): \Illuminate\Http\JsonResponse
    {
        $data = $this->_analyticService->getUserDemographics();
        return response()->json([
            'success' => true,
            'message' => "user demographics",
            'data' => $data
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/analytics/behavior-metrics",
     *     operationId="getBehaviorMetrics",
     *     tags={"Analytics"},
     *     summary="Get behavior metrics",
     *     description="Retrieves user behavior metrics including average time spent per activity.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Behavior metrics data retrieved"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getBehaviorMetrics(): \Illuminate\Http\JsonResponse
    {
        $data = $this->_analyticService->getBehaviorMetrics();
        return response()->json([
            'success' => true,
            'message' => "behavior metrics",
            'data' => $data
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/analytics/time-spent",
     *     operationId="updateTimeSpent",
     *     tags={"Analytics"},
     *     summary="Update time spent on activity",
     *     description="Updates the time spent by the user on a specific activity.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"time_spent", "activity"},
     *             @OA\Property(property="time_spent", type="number", format="float", description="Time spent on activity"),
     *             @OA\Property(property="activity", type="string", description="Activity name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Time spent updated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function updateTimeSpent(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_analyticService->updateTimeSpent($request);
        return response()->json([
            'success' => $response->status,
            'message' => $response->message,
        ],$response->code);
    }

    /**
     * @OA\Get(
     *     path="/api/analytics/project-status",
     *     operationId="getProjectStatus",
     *     tags={"Project Analytics"},
     *     summary="Get project status summary",
     *     description="Retrieves the total number of contracts and the number of contracts per status.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Project status summary retrieved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total_projects", type="integer", description="Total number of projects"),
     *             @OA\Property(property="done_projects", type="integer", description="Number of projects with status 'Done'"),
     *             @OA\Property(property="in_review_projects", type="integer", description="Number of projects with status 'In Review'"),
     *             @OA\Property(property="in_progress_projects", type="integer", description="Number of projects with status 'In Progress'")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getProjectStatus(): \Illuminate\Http\JsonResponse
    {
        $data = $this->_analyticService->getProjectStatus();
        return response()->json([
            'success' => true,
            'message' => "project status",
            'data' => $data
        ],200);

    }


    /**
     * @OA\Get(
     *     path="/api/analytics/project-types",
     *     operationId="getProjectTypes",
     *     tags={"Project Analytics"},
     *     summary="Get project types summary",
     *     description="Retrieves the total number of job types.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Job types summary retrieved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total_projects", type="integer", description="Total number of projects"),
     *             @OA\Property(property="contracts", type="integer", description="Number of contract jobs "),
     *             @OA\Property(property="hourly", type="integer", description="Number of hourly jobs"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getProjectTypes(): \Illuminate\Http\JsonResponse
    {
        $data = $this->_analyticService->getProjectTypesCount();
        return response()->json([
            'success' => true,
            'message' => "project types     ",
            'data' => $data
        ],200);

    }

}
