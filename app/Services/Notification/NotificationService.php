<?php

namespace App\Services\Notification;


use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Interfaces\Notification\INotificationService;
use App\enums\HttpStatusCode;
use App\Helpers\GeneralHelper;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationService implements INotificationService
{

    protected  $_currentUser;

    function __construct()
    {
        $this->_currentUser = Auth::user();
    }
    public function getAllNotification(): ApiResponseDto
    {
        // get all current user notifications $this->_currentUser->id
        try {
            $notifications = Notification::where('user_id', $this->_currentUser->id)->get();
            if($notifications->isNotEmpty()){
                $dto = $notifications->map(function ($notification){
                   return [
                       'id' => $notification->id,
                       'title' => $notification->title,
                       'message' => $notification->message,
                       'isRead' => $notification->isRead,
                       'date' => GeneralHelper::timeAgo($notification->created_at)
                   ];
                });
                return  new ApiResponseDto(true, "Notifications ", HttpStatusCode::OK, $dto);
            }
            return  new ApiResponseDto(true, "Not found ", HttpStatusCode::OK);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getNotificationById(int $id): ApiResponseDto
    {
        try {
            $notification = Notification::find($id);
            if($notification != null){
                    $dto =  [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'isRead' => $notification->isRead,
                        'date' => GeneralHelper::timeAgo($notification->created_at)
                    ];
                return  new ApiResponseDto(true, "Notification ", HttpStatusCode::OK, $dto);
            }
            return  new ApiResponseDto(true, "Not found ", HttpStatusCode::OK);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function readNotification(int $id): ApiResponseDto
    {
        try {
            $notification = Notification::find($id);
            if($notification != null){
               $notification->isRead = true;
               $notification->save();
                return  new ApiResponseDto(true, "Read", HttpStatusCode::OK);
            }
            return  new ApiResponseDto(true, "Not found ", HttpStatusCode::OK);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
