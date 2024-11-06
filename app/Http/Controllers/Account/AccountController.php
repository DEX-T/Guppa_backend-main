<?php

namespace App\Http\Controllers\Account;

use App\Domain\Interfaces\Account\IUserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    private  IUserService $_userService;
    protected ?\Illuminate\Contracts\Auth\Authenticatable $_currentUser;
    
    function __construct(IUserService $userService)
    {
        $this->_userService = $userService;
        $this->_currentUser = Auth::user();
    }

   /**
     * @OA\Get(
     *     path="/api/user/user_check_keys",
     *     operationId="checkListUser",
     *     tags={"Users"},
     *     summary="Get user check list for 2Fa, Email Verified and is onboard or verified for client",
     *     description="check list",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function checkUser(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getUserCheckList();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }

   /**
     * @OA\Get(
     *     path="/api/user/freelancers",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of freelancers",
     *     description="Returns list of freelancers",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function getAllUsers(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getAllUsers();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/user/admins",
     *     operationId="getAdminsList",
     *     tags={"Users"},
     *     summary="Get list of admins",
     *     description="Returns list of admins",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function getAllAdmins(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getAllAdmins();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }


     /**
     * @OA\Get(
     *     path="/api/user/freelancer/{userId}",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     summary="Get user information",
     *     description="Returns user data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    //get user by Id
    public function getUserById(Request $request): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getUserById($request->userId);
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/user/clients",
     *     operationId="getClientsList",
     *     tags={"Users"},
     *     summary="Get list of clients",
     *     description="Returns list of clients",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function getClients(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getAllClients();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }

         /**
     * @OA\Get(
     *     path="/api/user/client/{clientId}",
     *     operationId="getClientById",
     *     tags={"Users"},
     *     summary="Get Client information",
     *     description="Returns Client data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found"
     *     )
     * )
     */
    //get user by Id
    public function getClientById(Request $request): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getClientById($request->clientId);
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }


        /**
     * @OA\Get(
     *     path="/api/user/freelancer-profile/{user_id}",
     *     operationId="getUserProfileById",
     *     tags={"Users"},
     *     summary="After user is on boarded, get the user profile details",
     *     description="Returns user data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    //get user by Id
    public function getFreelancerProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getFreelancerProfile($request->user_id);
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }

        /**
     * @OA\Get(
     *     path="/api/user/freelancer-public-profile/{user_id}",
     *     operationId="getUserPublicProfileById",
     *     tags={"Users"},
     *     summary="Get Freelancer public profile",
     *     description="Returns freelancer data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    //get user by Id
    public function getFreelancerPublicProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getFreelancerPublicProfile($request->user_id);
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }


     /**
     * @OA\Post(
     *     path="/api/user/upload-profile",
     *     operationId="uploadProfile",
     *     tags={"Users"},
     *     summary="upload profile photo",
     *     description="upload profile photo",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"image_path"},
     *             @OA\Property(property="image_path", type="string", example="0000/file.png")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function upload_profile(Request $request)
    {

        $photo = $this->_userService->UploadProfile($request);
        if ($photo->status) {
            return response()->json([
                'success' => true,
                'message' => $photo->message,
            ], $photo->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $photo->message,
                'error' => $photo->data
            ], $photo->code);


        }
    }

     /**
     * @OA\Post(
     *     path="/api/user/forgot-password",
     *     operationId="forgotPassword",
     *     tags={"Users"},
     *     summary="password reset link",
     *     description="request for password reset link!",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="string@example.com")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function forgot_password(Request $request)
    {

        $password = $this->_userService->ForgotPassword($request);
        if ($password->status) {
            return response()->json([
                'success' => true,
                'message' => $password->message,
            ], $password->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $password->message,
                'error' => $password->data
            ], $password->code);


        }
    }


     /**
     * @OA\Post(
     *     path="/api/user/reset-password/{token}",
     *     operationId="resetPassword",
     *     tags={"Users"},
     *     summary="reset password",
     *     description="reset password",
     *  @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *      ),
     
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="Str12@#"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function reset_password(Request $request)
    {

        $password = $this->_userService->ResetPassword($request);
        if ($password->status) {
            return response()->json([
                'success' => true,
                'message' => $password->message,
            ], $password->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $password->message,
                'error' => $password->data
            ], $password->code);


        }
    }


    /**
     * @OA\Get(
     *     path="/api/user/freelancer_bid",
     *     operationId="getFreelancerBid",
     *     tags={"Users"},
     *     summary="Get freelancer bid",
     *     description="Returns freelancer bid",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function getFreelancerBid(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getBids();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }

    /**
     * @OA\Get(
     *     path="/api/user/freelancer_bids",
     *     operationId="getFreelancerBids",
     *     tags={"Users"},
     *     summary="Get all freelancer bids",
     *     description="Returns list freelancer bids",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All bids
    public function getFreelancerBids(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getUserBids();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }

 #region role
  /**
     * @OA\Post(
     *     path="/api/user/create_update_portfolio",
     *     operationId="upsertPortfolio",
     *     tags={"Users"},
     *      security={{"sanctum":{}}},
     *     summary="Create or update portfolio",
     *     description="Create or update portfolio",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"file_path", "description", "portfolio_id"},
     *             @OA\Property(property="file_path", type="string", example="string"),
     *             @OA\Property(property="description", type="string", example="description"),
     *             @OA\Property(property="portfolio_id", type="int", example="0")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function upsert_portfolio(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_userService->upsert_portfolio($request);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message
                ], $create->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $create->message,
                    'error' => $create->data
                ], $create->code);


            }

    }

  /**
     * @OA\Post(
     *     path="/api/user/generate_chatId",
     *     operationId="generateChatId",
     *     tags={"Users"},
     *      security={{"sanctum":{}}},
     *     summary="Generate chat Id for messaging",
     *     description="Generate chat Id for messaging",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function generate_chatId(): \Illuminate\Http\JsonResponse
    {
            $create = $this->_userService->generate_chatId();
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message,
                    "chat_Id" => $create->data
                ], $create->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $create->message,
                    'error' => $create->data
                ], $create->code);


            }

    }

  /**
     * @OA\DELETE(
     *     path="/api/user/portfolio/delete/{id}",
     *     operationId="deletePortfolio",
     *     tags={"Users"},
     *      security={{"sanctum":{}}},
     *     summary="Delete portfolio",
     *     description="delete portfolio",
    *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function delete_portfolio(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_userService->delete_portfolio($request->id);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message
                ], $create->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $create->message,
                    'error' => $create->data
                ], $create->code);


            }

    }


    /**
     * @OA\PUT(
     *     path="/api/user/update-skills",
     *     operationId="updateSkills",
     *     tags={"Users"},
     *     summary="update skills",
     *     description="update skills",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"skills"},
     *             @OA\Property(property="skills", type="string", example="string,string,string")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function updateSkills(Request $request): \Illuminate\Http\JsonResponse
    {

        $response = $this->_userService->updateSkills($request);
            return response()->json([
                'success' => $response->status,
                'message' => $response->message,
            ], $response->code);

    }


    /**
     * @OA\PUT(
     *     path="/api/user/update-hourly-rate",
     *     operationId="updateHourlyRate",
     *     tags={"Users"},
     *     summary="update hourly rate",
     *     description="update hourly rate",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hourly_rate"},
     *             @OA\Property(property="hourly_rate", type="number", example=0)
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function updateHourlyRate(Request $request): \Illuminate\Http\JsonResponse
    {

        $response = $this->_userService->updateHourlyRate($request);
        return response()->json([
            'success' => $response->status,
            'message' => $response->message,
        ], $response->code);

    }


    /**
     * @OA\PUT(
     *     path="/api/user/update-short-bio",
     *     operationId="updateShortBio",
     *     tags={"Users"},
     *     summary="update short bio",
     *     description="update short bio",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"short_bio"},
     *             @OA\Property(property="short_bio", type="string", example="I am...")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     * )
     */
    public function updateShortBio(Request $request): \Illuminate\Http\JsonResponse
    {

        $response = $this->_userService->updateShortBio($request);
        return response()->json([
            'success' => $response->status,
            'message' => $response->message,
        ], $response->code);

    }


    /**
     * @OA\PUT(
     *     path="/api/user/update-language",
     *     operationId="updateLanguage",
     *     tags={"Users"},
     *     summary="update language",
     *     description="update language",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"language"},
     *             @OA\Property(property="language", type="string", example="english")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function updateLanguage(Request $request): \Illuminate\Http\JsonResponse
    {

        $response = $this->_userService->updateLanguage($request);
        return response()->json([
            'success' => $response->status,
            'message' => $response->message,
        ], $response->code);

    }


    /**
     * @OA\PUT(
     *     path="/api/user/update-looking-for",
     *     operationId="updateLookingFor",
     *     tags={"Users"},
     *     summary="update looking for",
     *     description="update looking for",
     *     security={{"sanctum":{}}},
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"looking_for"},
     *             @OA\Property(property="looking_for", type="string", example="contract")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function updateLookingFor(Request $request): \Illuminate\Http\JsonResponse
    {

        $response = $this->_userService->updateLookingFor($request);
        return response()->json([
            'success' => $response->status,
            'message' => $response->message,
        ], $response->code);

    }


    /**
     * @OA\Put(
     *     path="/api/user/activate/{userId}",
     *     summary="Activate User",
     *      description="Activate user",
     *      tags={"Users"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User activated",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function activateUser(Request $request)
    {
        $application = $this->_userService->activateUser($request->userId);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }

       /**
     * @OA\Put(
     *     path="/api/user/deactivate/{userId}",
     *     summary="Deactivate User",
     *      description="Deactivate user",
     *      tags={"Users"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User activated",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function deactivateUser(Request $request)
    {
        $application = $this->_userService->deactivateUser($request->userId);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }

    /**
     * @OA\Get(
     *     path="/api/user/track-profile",
     *     operationId="trackProfileCompletion",
     *     tags={"Users"},
     *     summary="Track profile completion",
     *     description="Track Freelancer profile completion",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function trackProfile(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->trackUserProfile();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }

    /**
     * @OA\Get(
     *     path="/api/user/current_user",
     *     operationId="currentLoggedInUser",
     *     tags={"Users"},
     *     summary="Get Current Logged in user details",
     *     description="Get Current Logged in user details",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All users
    public function getCurrentUser(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_userService->getCurrentUser();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ], $userDto->code);
    }

      /**
     * @OA\Delete(
     *     path="/api/user/delete_user/{userId}",
     *     summary="delete User",
     *      description="delete user",
     *      tags={"Users"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function deleteUser(Request $request)
    {
        $application = $this->_userService->deleteUser($request->userId);

        return response()->json([
            'success' => $application->status,
            'message' => $application->message,
        ], $application->code);
    }




    /**
     * @OA\Put(
     *     path="/api/user/update-details",
     *     summary="Update user details",
     *     description="Update the authenticated user's details.",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "phone_no", "country"},
     *             @OA\Property(property="first_name", type="string", maxLength=255, example="John"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, example="Doe"),
     *             @OA\Property(property="phone_no", type="string", maxLength=15, example="+1234567890"),
     *             @OA\Property(property="country", type="string", maxLength=255, example="USA"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female"}, nullable=true, example="male"),
     *             @OA\Property(
     *                 property="age_group",
     *                 type="string",
     *                 enum={"18_25", "25_29", "30_45", "46_50", "50above"},
     *                 nullable=true,
     *                 example="25_29"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User details updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="first_name", type="array", @OA\Items(type="string", example="First name is required"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while updating user details")
     *         )
     *     )
     * )
     */
    public function updateUserDetail(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_userService->updateUserDetails($request);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message
                ], $create->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $create->message,
                    'error' => $create->data
                ], $create->code);


            }

    }

    /**
     * @OA\Put(
     *     path="/api/user/change_password",
     *     summary="Change user password",
     *     description="Endpoint to change the authenticated user's password. The user must provide their old password, a new password, and a confirmation for the new password.",
     *     operationId="changePassword",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password", "password", "password_confirmation"},
     *             @OA\Property(property="old_password", type="string", description="The current password of the user", example="current_password123"),
     *             @OA\Property(property="password", type="string", description="The new password for the user (Minimum 10 characters, must contain letters, mixed case, numbers, symbols)", example="NewPassw0rd!"),
     *             @OA\Property(property="password_confirmation", type="string", description="Must match the new password", example="NewPassw0rd!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"old_password": {"The old password is incorrect."}, "password": {"The new password must be at least 10 characters."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $update = $this->_userService->changePassword($request);
        if ($update->status) {
            return response()->json([
                'success' => true,
                'message' => $update->message
            ], $update->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $update->message,
                'error' => $update->data
            ], $update->code);


        }
        
    }


}
