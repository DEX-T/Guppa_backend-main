<?php

namespace App\Http\Controllers\Settings;

use App\Domain\Interfaces\Notification\INotificationService;
use App\Domain\Interfaces\Settings\ISettingsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    private ISettingsService $_settingsService;
    private INotificationService $_notificationService;

    public function __construct(ISettingsService $settingsService, INotificationService $notificationService)
    {
        $this->_settingsService = $settingsService;
        $this->_notificationService = $notificationService;
    }


  /**
     * @OA\PUT(
     *     path="/api/setting/email-notifications",
     *     operationId="updateEmailNotifications",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Email Notifications",
     *     description="update Email Notifications",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateEmailNotifications()
    {
       $status =  $this->_settingsService->updateEmailNotifications();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/push-notifications",
     *     operationId="updatePushNotifications",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Push Notifications",
     *     description="update Push Notifications",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updatePushNotifications()
    {
       $status =  $this->_settingsService->updatePushNotifications();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/sms-notifications",
     *     operationId="updateSmsNotifications",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Sms Notifications",
     *     description="update Sms Notifications",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateSmsNotifications()
    {
       $status =  $this->_settingsService->updateSmsNotifications();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/in-app-notifications",
     *     operationId="updateInAppNotification",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update In App Notifications",
     *     description="update In App Notifications",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateInAppNotifications()
    {
       $status = $this->_settingsService->updateInAppNotifications();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

    /**
     * @OA\PUT(
     *     path="/api/setting/profile-visibility",
     *     operationId="updateProfileVisibility",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Profile Notifications",
     *     description="update Profile Notifications",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateProfileVisibility()
    {
        $status = $this->_settingsService->updateProfileVisibility();
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
           ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/search-visibility",
     *     operationId="updateSearchVisibility",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Search Visibility",
     *     description="update Search Visibility",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateSearchVisibility()
    {
      $status =   $this->_settingsService->updateSearchVisibility();
      return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/data-sharing",
     *     operationId="updateDataSharing",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Data Sharing",
     *     description="update Data Sharing",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateDataSharing()
    {
       $status =  $this->_settingsService->updateDataSharing();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/location-settings",
     *     operationId="updateLocation",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Location Setting",
     *     description="update Location Setting",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateLocationSettings()
    {
       $status = $this->_settingsService->updateLocationSettings();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/ad-preferences",
     *     operationId="updateAdPreferences",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Ad Preference",
     *     description="update Ad Preference",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateAdPreferences()
    {
       $status= $this->_settingsService->updateAdPreferences();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

     /**
     * @OA\PUT(
     *     path="/api/setting/activity-status",
     *     operationId="updateActivityStatus",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="update Activity Status",
     *     description="update Activity Status",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function updateActivityStatus()
    {
      $status =  $this->_settingsService->updateActivityStatus();
      return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

 /**
     * @OA\GET(
     *     path="/api/setting/settings",
     *     operationId="getSettings",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="get setting",
     *     description="get setting",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     )
     * )
     */
    public function getSettings()
    {
       $data = $this->_settingsService->Settings();
       return response()->json($data);
    }

   /**
     * @OA\GET(
     *     path="/api/setting/request-data",
     *     operationId="requestAccountData",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="request account data",
     *     description="request account data",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function requestAccountData()
    {
        $status = $this->_settingsService->requestAccountData();
        
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
           ], $status->code);
    }

     /**
     * @OA\DELETE(
     *     path="/api/setting/delete-account",
     *     operationId="deleteAccountPermanently",
     *     tags={"Setting"},
     *      security={{"sanctum":{}}},
     *     summary="delete my account permanently",
     *     description="delete my account permanently",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *
     * )
     */
    public function deleteAccountPermanently()
    {
       $status = $this->_settingsService->deleteAccountPermanently();
       return response()->json([
        'success' => $status->status,
        'message' => $status->message,
       ], $status->code);
    }

    /**
     * @OA\Get(
     *     path="/api/notification/notifications",
     *     summary="Get all notifications for the current user",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notifications"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Welcome"),
     *                     @OA\Property(property="message", type="string", example="Welcome to our service!"),
     *                     @OA\Property(property="isRead", type="string", example="no"),
     *                     @OA\Property(property="date", type="string", example="2 hours ago")
     *                 )
     *             )
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

    public function getAllNotification(): \Illuminate\Http\JsonResponse
    {
        $status = $this->_notificationService->getAllNotification();
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
        ], $status->code);
    }

    /**
     * @OA\Get(
     *     path="/api/notification/notification/{id}",
     *     summary="Get a specific notification by ID",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Notification ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Welcome"),
     *                 @OA\Property(property="message", type="string", example="Welcome to our service!"),
     *                 @OA\Property(property="isRead", type="string", example="no"),
     *                 @OA\Property(property="date", type="string", example="2 hours ago")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found")
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
    public function getNotificationById(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_notificationService->getNotificationById($request->id);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
            'data' => $status->data
        ], $status->code);
    }

    /**
     * @OA\Put(
     *     path="/api/notification/read/{id}",
     *     summary="Mark a notification as read",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Notification ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Read")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found")
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
    public function readNotification(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $this->_notificationService->readNotification($request->id);
        return response()->json([
            'success' => $status->status,
            'message' => $status->message,
        ], $status->code);
    }

    
    /**
     * @OA\Get(
     *     path="/api/setting/is-2fa-verified",
     *     summary="check if current user activated 2fa and is verified",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notifications"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Welcome"),
     *                     @OA\Property(property="message", type="string", example="Welcome to our service!"),
     *                     @OA\Property(property="isRead", type="string", example="no"),
     *                     @OA\Property(property="date", type="string", example="2 hours ago")
     *                 )
     *             )
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

     public function Is2FaVerified(): \Illuminate\Http\JsonResponse
     {
         $status = $this->_settingsService->Is2FaVerified();
         return response()->json([
            'data' => $status
         ]);
     }
 

}
