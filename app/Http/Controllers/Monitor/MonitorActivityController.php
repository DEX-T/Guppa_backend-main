<?php

namespace App\Http\Controllers\Monitor;

use App\Domain\Interfaces\Monitor\IMonitorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitorActivityController extends Controller
{
    private  IMonitorService $_monitorService;

    function __construct(IMonitorService $monitorService)
    {
        $this->_monitorService = $monitorService;
    }


    /**
     * @OA\Get(
     *     path="/api/monitor/all-api-usage",
     *     summary="Get All API usage log",
     *     tags={"Monitor"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Api usage")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getAllApiUsage(): \Illuminate\Http\JsonResponse
    {
        $response = $this->_monitorService->getAllApiUsage();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/monitor/api-usage/{id}",
     *     summary="Get API usage log by ID",
     *     tags={"Monitor"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="API log ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Api usage")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getApiUsage(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_monitorService->getApiUsage($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }



    /**
     * @OA\Get(
     *     path="/api/monitor/audit-logs",
     *     summary="Get All Audit logs",
     *     tags={"Monitor"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Api usage")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getAllAuditLogs(): \Illuminate\Http\JsonResponse
    {
        $response = $this->_monitorService->getAuditLogs();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/monitor/audit-log/{id}",
     *     summary="Get Audit log by ID",
     *     tags={"Monitor"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Audit log ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Audit log")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error occurred.")
     *         )
     *     )
     * )
     */
    public function getAuditLog(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_monitorService->getAuditLog($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }
}
