<?php

namespace App\Services\Monitor;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Response\Monitor\ApiUsageResponseDto;
use App\Domain\Interfaces\Monitor\IMonitorService;
use App\enums\HttpStatusCode;
use App\Models\APILog;
use App\Models\AuditLog;

class MonitorService implements IMonitorService
{

    public function getAllApiUsage(): ApiResponseDto
    {
        try {
            $usage = APILog::all();
            if($usage->isNotEmpty()){
                $dto = $usage->map(function ($log) {
                    return new ApiUsageResponseDto($log);
                });
                return new ApiResponseDto(true, "Api usage", HttpStatusCode::OK, $dto);
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function getApiUsage(int $id): ApiResponseDto
    {
        try {
            $usage = APILog::findOrFail($id);
            if($usage != null){
                $dto = new ApiUsageResponseDto($usage);
                return new ApiResponseDto(true, "Api usage", HttpStatusCode::OK, $dto);
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getAuditLogs(): ApiResponseDto
    {
        try {
            $audits = AuditLog::with('user', 'target')->orderBy('created_at', 'desc')->get();
            if($audits->isNotEmpty()){
                $dto = $audits->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'user_id' => $log->user_id,
                        'action' => $log->action,
                        'visited_route' => $log->visited_route,
                        'controller_method' => $log->controller_method,
                        'details' => json_decode($log->details),
                        'created_at' => $log->created_at,
                        'request_headers' => json_decode($log->request_headers),
                        'user' => [
                            'id' => $log->user->id,
                            'name' => $log->user->first_name,
                            'email' => $log->user->email,
                            'profile_photo' => asset('storage/app/public/uploads/'.$log->user->profile_photo)
                        ]
                    ];
                });
                return new ApiResponseDto(true, "Audit logs", HttpStatusCode::OK, $dto);
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function getAuditLog(int $id): ApiResponseDto
    {
        try {
            $log = AuditLog::with('user', 'target')->where('id', $id)->orderBy('created_at', 'desc')->first();

            if($log != null){
                $dto =  [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'action' => $log->action,
                    'visited_route' => $log->visited_route,
                    'controller_method' => $log->controller_method,
                    'details' => json_decode($log->details),
                    'created_at' => $log->created_at,
                    'request_headers' => json_decode($log->request_headers),
                    'user' => [
                        'id' => $log->user->id,
                        'name' => $log->user->first_name,
                        'email' => $log->user->email,
                        'profile_photo' => asset('storage/app/public/uploads/'.$log->user->profile_photo)
                    ]
                ];
                return new ApiResponseDto(true, "Audit Log", HttpStatusCode::OK, $dto);
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
}
