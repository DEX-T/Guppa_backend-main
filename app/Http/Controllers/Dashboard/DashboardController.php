<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Domain\Interfaces\Dashboard\IDashboardService;

class DashboardController extends Controller
{
    private IDashboardService $_dashboardService;
    public function __construct(DashboardService $dashboardService) {
        $this->_dashboardService = $dashboardService;
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/client-tables",
     *     summary="Get client tables",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Client tables fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getClientTables(): JsonResponse
    {
        $data = $this->_dashboardService->getClientTables();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/client-statistics",
     *     summary="Get client statistics",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Client statistics fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getStatistics(): JsonResponse
    {
        $data = $this->_dashboardService->ClientStatistics();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }



    /**
     * @OA\Get(
     *     path="/api/dashboard/admin-tables",
     *     summary="Get admin tables",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Client tables fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getAdminTables(): JsonResponse
    {
        $data = $this->_dashboardService->getAdminTables();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/admin-statistics",
     *     summary="Get admin statistics",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="admin statistics fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getAdminStatistics(): JsonResponse
    {
        $data = $this->_dashboardService->GetAdminStatistics();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }


//     GetCounters
   /**
     * @OA\Get(
     *     path="/api/dashboard/dashboard-counters",
     *     summary="Get dashboard counters",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="admin statistics fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getCounters(): JsonResponse
    {
        $data = $this->_dashboardService->GetCounters();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }

// GetLatestSupportTickets
/**
     * @OA\Get(
     *     path="/api/dashboard/latest-tickets",
     *     summary="Get latest tickets from support",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="admin statistics fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getLatestSupportTickets(): JsonResponse
    {
        $data = $this->_dashboardService->GetLatestSupportTickets();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }

// GetLatestUsers
/**
     * @OA\Get(
     *     path="/api/dashboard/latest-users",
     *     summary="Get latest users",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="admin statistics fetched successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function getLatestUsers(): JsonResponse
    {
        $data = $this->_dashboardService->GetLatestUsers();
        return response()->json([
            'success' => $data->status,
            'data' => $data,
            'message' => $data->message
        ], $data->code);
    }

// GetLatestUsers
}
