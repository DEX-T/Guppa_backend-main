<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Domain\Entities\UserEntity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Domain\DTOs\Request\RegisterRequestDto;
use App\Domain\Interfaces\Authentication\IAuthService;
use App\enums\HttpStatusCode;

class AuthenticationController extends Controller
{
    public IAuthService $_authService;
    public function __construct(IAuthService $authService)
    {
        $this->_authService = $authService;
    }
    /**
     * @OA\Put(
     *     path="/api/user/update_role",
     *     operationId="updateuserrole",
     *     tags={"Authentication"},
     *     summary="Update userRole",
     *     description="update_role",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role", "userEmail"},
     *             @OA\Property(property="userEmail", type="string", format="userEmail", example="string@example.com"),
     *             @OA\Property(property="role", type="string", format="role", example="string"),
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

    public function updateUserRole(Request $request): JsonResponse
    {
        $role = $request->input('role');
        $userEmail = $request->input('userEmail');

        $userRole = $this->authService->updateUserRole($role, $userEmail);

        if ($userRole->status) {
            return response()->json([
                'success' => true,
                'message' => $userRole->message
            ], $userRole->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $userRole->message,
                'error' => $userRole->data
            ], $userRole->code);


        }
    }

  /**
     * @OA\Post(
     *     path="/api/user/create-user",
     *     operationId="createNewUser",
     *     tags={"Authentication"},
     *     summary="Create a new user",
     *     description="create a new user (admin or support)",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","email","phone_no","country","role"},
     *             @OA\Property(property="first_name", type="string", example="string"),
     *             @OA\Property(property="last_name", type="string", example="string"),
     *             @OA\Property(property="email", type="string", format="email", example="string@example.com"),
     *             @OA\Property(property="phone_no", type="string", format="tel", example="tel"),
     *             @OA\Property(property="country", type="string",  example="string"),
     *             @OA\Property(property="role", type="string",  example="admin")
     *
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
    public function create_user(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_authService->CreateUser($request);
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
     *     path="/api/user/admin-onboard",
     *     summary="Onboard an administrator by setting a password and activating their account",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", example="encrypted-email-string"),
     *             @OA\Property(property="password", type="string", example="StrongPassword123!"),
     *             @OA\Property(property="password_confirmation", type="string", example="StrongPassword123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Activation completed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Activation completed")
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
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="validation error"),
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
    public function onboard_administrator(Request $request): \Illuminate\Http\JsonResponse
    {

        $create = $this->_authService->Onboard($request);
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
     *     path="/api/user/register",
     *     operationId="registerNewUser",
     *     tags={"Authentication"},
     *     summary="Register new user",
     *     description="create a new user in the system",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","email","phone_no","country", "password", "password_confirmation", "account_type", "agreement_policy"},
     *             @OA\Property(property="first_name", type="string", example="string"),
     *             @OA\Property(property="last_name", type="string", example="string"),
     *             @OA\Property(property="email", type="string", format="email", example="string@example.com"),
     *             @OA\Property(property="phone_no", type="string", format="tel", example="tel"),
     *             @OA\Property(property="country", type="string",  example="string"),
     *             @OA\Property(property="password", type="string", format="password", example="string"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="string"),
     *             @OA\Property(property="account_type", type="string",  example="F"),
     *             @OA\Property(property="agreement_policy", type="boolean",  example="true")
     *
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
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $create = $this->_authService->Register($request);
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
     *     path="/api/user/login",
     *     operationId="loginNewUser",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="login",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="string@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="string"),
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
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $login = $this->_authService->Login($request);
        if ($login->status) {
            return response()->json([
                'success' => true,
                'message' => $login->message,
                'data' => $login->data
            ], $login->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $login->message,
                'error' => $login->data
            ], $login->code);


        }
    }

 /**
     * @OA\Get(
     *     path="/api/user/login",
     *     operationId="loginRedirect",
     *     tags={"Authentication"},
     *     summary="Login Redirect",
     *     description="redirect login",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function redirectLogin(){
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: Please login',

        ], HttpStatusCode::UNAUTHORIZED);
    }
     /**
     * @OA\Post(
     *     path="/api/user/2fa/enable2fa",
     *     operationId="enable2FA",
     *     tags={"Authentication"},
     *     summary="Enable Two auth",
     *     description="enable2fa",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function enable2fa(){
        $enable = $this->_authService->enable2fa();
        if ($enable->status) {
            return response()->json([
                'success' => true,
                'message' => $enable->message
            ], $enable->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $enable->message,
                'error' => $enable->data
            ], $enable->code);


        }
    }

        /**
     * @OA\Post(
     *     path="/api/user/2fa/disable2fa",
     *     operationId="disable2fa",
     *     tags={"Authentication"},
     *     summary="Disable Two auth",
     *     description="disable2fa",

     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function disable2fa(){
        $disable = $this->_authService->disable2fa();
        if ($disable->status) {
            return response()->json([
                'success' => true,
                'message' => $disable->message
            ], $disable->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $disable->message,
                'error' => $disable->data
            ], $disable->code);


        }
    }

        /**
     * @OA\Post(
     *     path="/api/user/2fa/resend-code",
     *     operationId="resendCode",
     *     tags={"Authentication"},
     *     summary="resend Code",
     *     description="resend 2fa code",

     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function resendCode(){
        $resend = $this->_authService->resendCode();
        if ($resend->status) {
            return response()->json([
                'success' => true,
                'message' => $resend->message
            ], $resend->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $resend->message,
                'error' => $resend->data
            ], $resend->code);


        }
    }

 /**
     * @OA\Post(
     *     path="/api/user/2fa/verify2fa",
     *     operationId="verify2fa",
     *     tags={"Authentication"},
     *     summary="Verify  Two factor auth",
     *     description="verify",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code"},
     *
     *             @OA\Property(property="code", type="int", format="number", example="000000"),
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
    public function verify2fa(Request $request){
        $verify2fa = $this->_authService->verify2fa($request);
        if ($verify2fa->status) {
            return response()->json([
                'success' => true,
                'message' => $verify2fa->message
            ], $verify2fa->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $verify2fa->message,
                'error' => $verify2fa->data
            ], $verify2fa->code);


        }
    }

         /**
     * @OA\Post(
     *     path="/api/user/2fa/prompt",
     *     operationId="prompt",
     *     tags={"Authentication"},
     *     summary="Prompt for 2fa verification",
     *     description="prompt",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
     public function prompt(){
        $prompt = $this->_authService->prompt();
        if ($prompt->status) {
            return response()->json([
                'success' => true,
                'message' => $prompt->message
            ], $prompt->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $prompt->message,
                'error' => $prompt->data
            ], $prompt->code);


        }
     }

    /**
     * @OA\Post(
     *     path="/api/user/2fa/verify",
     *     operationId="verify",
     *     tags={"Authentication"},
     *     summary="Verify  Two factor auth",
     *     description="verify",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code"},
     *
     *             @OA\Property(property="code", type="int", format="number", example="000000"),
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
    public function verify(Request $request){
        $verify2fa = $this->_authService->verify($request);
        if ($verify2fa->status) {
            return response()->json([
                'success' => true,
                'message' => $verify2fa->message
            ], $verify2fa->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $verify2fa->message,
                'error' => $verify2fa->data
            ], $verify2fa->code);


        }
    }

  /**
     * @OA\Get(
     *     path="/api/user/facebook_login",
     *     operationId="facebookLogin",
     *     tags={"Authentication"},
     *     summary="Login With Facebook",
     *     description="facebook_login",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function facebook_login()
    {
        $url = $this->_authService->redirectToFacebook();
        if ($url->status) {
            return response()->json([
                'success' => true,
                'message' => $url->message,
                'url' => $url->data
            ], $url->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $url->message,
                'error' => $url->data
            ], $url->code);

        }
    }

  /**
     * @OA\Get(
     *     path="/api/user/facebook_callback",
     *     operationId="facebookCallback",
     *     tags={"Authentication"},
     *     summary="Login With Facebook",
     *     description="facebook_login",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function facebook_callback()
    {
        try {
            $authUser = $this->_authService->handleFacebookCallback();

            if ($authUser->status) {
                return response()->json([
                    'success' => true,
                    'message' => $authUser->message,
                    'data' => $authUser->data
                ], $authUser->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $authUser->message,
                    'error' => $authUser->data
                ], $authUser->code);


            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Server error" .$e->getMessage()
            ], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user/google_login",
     *     operationId="googleLogin",
     *     tags={"Authentication"},
     *     summary="Login With google",
     *     description="google_login",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     )
     *
     * )
     */
    public function google_login()
    {
        $url = $this->_authService->redirectToGoogle();
        if ($url->status) {
            return response()->json([
                'success' => true,
                'message' => $url->message,
                'url' => $url->data
            ], $url->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $url->message,
                'error' => $url->data
            ], $url->code);


        }
    }

  /**
     * @OA\Get(
     *     path="/api/user/google_callback",
     *     operationId="googleCallback",
     *     tags={"Authentication"},
     *     summary="Login With google",
     *     description="google_login",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     )
     *
     * )
     */
    public function google_callback()
    {
        try {
            $authUser = $this->_authService->handleGoogleCallback();

            if ($authUser->status) {
                return response()->json([
                    'success' => true,
                    'message' => $authUser->message,
                    'data' => $authUser->data
                ], $authUser->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $authUser->message,
                    'error' => $authUser->data
                ], $authUser->code);


            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Server error" .$e->getMessage()
            ], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



       /**
     * @OA\Post(
     *     path="/api/user/email/resend-code",
     *     operationId="resendEmailCode",
     *     tags={"Authentication"},
     *     summary="resend email Code",
     *     description="resend email code",

     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
    public function resendEmailCode(){
        $resend = $this->_authService->resendEmailCode();
        if ($resend->status) {
            return response()->json([
                'success' => true,
                'message' => $resend->message
            ], $resend->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $resend->message,
                'error' => $resend->data
            ], $resend->code);


        }
    }
    /**
     * @OA\Post(
     *     path="/api/user/email/prompt",
     *     operationId="promptEmail",
     *     tags={"Authentication"},
     *     summary="Prompt for email verification",
     *     description="prompt",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     *
     * )
     */
     public function prompt_email(){
        $prompt = $this->_authService->prompt_email();
        if ($prompt->status) {
            return response()->json([
                'success' => true,
                'message' => $prompt->message
            ], $prompt->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $prompt->message,
                'error' => $prompt->data
            ], $prompt->code);


        }
     }

    /**
     * @OA\Post(
     *     path="/api/user/email/verify",
     *     operationId="verifyEmail",
     *     tags={"Authentication"},
     *     summary="Verify  email ",
     *     description="verify email",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code"},
     *
     *             @OA\Property(property="code", type="int", format="number", example="000000"),
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
    public function verify_email(Request $request){
        $verify2fa = $this->_authService->verify_email($request);
        if ($verify2fa->status) {
            return response()->json([
                'success' => true,
                'message' => $verify2fa->message
            ], $verify2fa->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $verify2fa->message,
                'error' => $verify2fa->data
            ], $verify2fa->code);


        }
    }




   /**
     * @OA\Post(
     *     path="/api/user/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="Logout User",
     *     description="verify",
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
     public function Logout(){
        $logout = $this->_authService->logout();
        if ($logout->status) {
            return response()->json([
                'success' => true,
                'message' => $logout->message
            ], $logout->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $logout->message,
                'error' => $logout->data
            ], $logout->code);


        }
     }


}
