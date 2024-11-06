<?php

namespace App\Services\Authentication;

use App\Domain\DTOs\Request\CreateUserRequestDto;
use App\Events\CreateUserEvent;
use App\Notifications\NewUserRegistered;
use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\TwoFaTracker;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Helpers\UserRoleHelper;
use App\Mail\TwoFactorCodeEmail;
use App\Models\VerificationCode;
use App\Events\TwoFactorCodeEvent;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\UserEntity;
use Illuminate\Support\Facades\Log;
use App\Events\AccountCreationEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\EmailVerificationEvent;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\RateLimiter;
use App\Domain\DTOs\Request\LoginRequestDto;
use App\Domain\DTOs\Response\LoginResponseDto;
use App\Domain\DTOs\Request\RegisterRequestDto;
use App\Domain\DTOs\Request\TwoFA\TwoFARequestDto;
use App\Domain\Interfaces\Authentication\IAuthService;
use App\Domain\Interfaces\Configuration\IConfigurationService;

class AuthService implements IAuthService
{
    // Implement your service methods here
    private IConfigurationService $_configService;
    protected $_currentUser;

    public function __construct(IConfigurationService $configService) {
        $this->_configService = $configService;
        $this->_currentUser =  Auth::user();

    }

    public function updateUserRole(string $role, string $userEmail): array
    {
         // Get the current authenticated user's ID
        try {
            $user = Auth::id();

            if (!$user) {
                return new ApiResponseDto(false, "User is not authenticated", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            // Check if a user with the given ID and email exists
        $user = User::where('id', $userId)->where('email', $userEmail)->first();

        if (!$user) {
            return new ApiResponseDto(false, "User Email not found", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
        }
        // Update the user's role
            $user->update([
                'role' => $validated['role']
            ]);
            return  new ApiResponseDto(true, "User Role created successfully",  HttpStatusCode::OK);

        }catch (\Exception $e) {
        return new ApiResponseDto(false, "Server error " .$e->getMessage(),  HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}

    // public function updateUserRole(string $role, string $userEmail): array
    // // {
    //     // Get the current authenticated user's ID
    //     $userId = Auth::id();

    //     if (!$userId) {
    //         return [
    //             'status' => 403,
    //             'message' => 'User is not authenticated.'
    //         ];
    //     }

    //     // Check if a user with the given ID and email exists
    //     $user = User::where('id', $userId)->where('email', $userEmail)->first();

    //     if (!$user) {
    //         return [
    //             'status' => 404,
    //             'message' => 'User not found or email does not match.'
    //         ];
    //     }

    //     // Update the user's role
    //     $user->role = $role;
    //     $user->save();

    //     return [
    //         'status' => 200,
    //         'message' => 'User role updated successfully.',
    //         'data' => [
    //             'user_id' => $user->id,
    //             'role' => $user->role,
    //         ]
    //     ];
    // }


    public function Register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'min:3', 'max:150'],
                'last_name' => ['required', 'string', 'min:3', 'max:150'],
                'email' => ['required', 'email', 'unique:' . User::class],
                'phone_no' => ['phone:INTERNATIONAL,NG', 'required', 'unique:' . User::class],
                'country' => ['required'],
                'password' => ['required', Password::min(10)->letters()->mixedCase()->symbols()->numbers()->uncompromised()],
                'password_confirmation' => ['required', 'same:password'],
                'account_type' => ['required', 'min:1', 'max:1'],
                'agreement_policy' => ['required', 'boolean']
            ]);


            if ($validator->fails()) {
                return new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());

            }else {
                $validated = $validator->validated();
                $dto = new RegisterRequestDto(
                    $validated['first_name'],
                    $validated['last_name'],
                    $validated['phone_no'],
                    $validated['country'],
                    $validated['email'],
                    $validated['password'],
                    $validated['account_type'],
                    $validated['agreement_policy']
                );

                $user = new User();
                $user->first_name = $dto->first_name;
                $user->last_name = $dto->last_name;
                $user->phone_no = $dto->phone_no;
                $user->email = $dto->email;
                $user->country = $dto->country;
                $user->password = Hash::make($dto->password);
                if(Str::upper($dto->account_type) == "F"){
                    $user->role = Str::upper("Freelancer");
                }else if(Str::upper($dto->account_type) == 'C'){
                     $user->role = Str::upper("Client");
                }elseif(Str::upper($dto->account_type) == '@'){
                    $user->role = Str::upper('superuser');
                }

                $user->agreement_policy = true;
                $user->profile_photo = "default.png";
                if($user->save()){
                    //send welcome email
                    $code = rand(111111, 999999);
                     //generate new code
                     $code = rand(111111, 999999);
                     VerificationCode::create([
                        'code' => $code,
                        'user_id' => $user->id,
                        'expiry' => Carbon::now()->addMinutes(5)
                    ]);
                     //trigger email event
                    event(new AccountCreationEvent($user, $code));
                    $user->notify(new  NewUserRegistered($user));
                    return new ApiResponseDto(true, "User created successfully",  HttpStatusCode::OK);
                    //return user entity
                }else {
                    return new ApiResponseDto(false, "Error Creating user! try again",  HttpStatusCode::BAD_REQUEST);
                }
            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error " .$e->getMessage(),  HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function CreateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'min:3', 'max:150'],
                'last_name' => ['required', 'string', 'min:3', 'max:150'],
                'email' => ['required', 'email', 'unique:' . User::class],
                'phone_no' => ['phone:INTERNATIONAL,NG', 'required', 'unique:' . User::class],
                'country' => ['required', 'string'],
                'role' => ['required', 'string']
            ], [
                "validation.phone" => "The phone number entered is not in a correct format!"
            ]);


            if($validator->fails()) {
                return new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());

            }else {
                $validated = $validator->validated();
                $dto = new CreateUserRequestDto(
                    $validated['first_name'],
                    $validated['last_name'],
                    $validated['phone_no'],
                    $validated['country'],
                    $validated['email'],
                    $validated['role'],
                );

                $user = new User();
                $user->first_name = $dto->first_name;
                $user->last_name = $dto->last_name;
                $user->phone_no = $dto->phone_no;
                $user->email = $dto->email;
                $user->country = $dto->country;
                $user->password = Hash::make(Str::password(10));
                $user->role = Str::upper($dto->role);
                $user->profile_photo = "default.png";
                if($user->save()){
                    $key = "guppa-secret";
                    Crypt::generateKey($key);
                    $encryptEmail = Crypt::encryptString($user->email);
                    Log::info("Encrypted email ", [$encryptEmail]);
                    $url = "https://globalservicesguppa.com/on_board/administrator/".urlencode($encryptEmail);
                    event(new CreateUserEvent($user, $url));
                    $user->notify(new  NewUserRegistered($user));
                    return new ApiResponseDto(true, "User created successfully",  HttpStatusCode::OK);
                    //return user entity
                }else {
                    return new ApiResponseDto(false, "Error Creating user! try again",  HttpStatusCode::BAD_REQUEST);
                }
            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error " .$e->getMessage(),  HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function Login(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                    'email' => ['required', 'email', 'exists:users,email'],
                    'password' => ['required'],
                    // 'remember_me' => ['boolean']
            ],[
                'email.exists' => 'User not found'
            ]);

            if($validator->fails()){
                Log::error("validation failed ");
                return new ApiResponseDto(false, "validation error",  HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }else{
                $validate = $validator->validated();
                $ip = $request->ip();
                $throttleKey = 'login_attempts' . $ip;
                Log::info("validation successful ");
                Log::info("Login IP ".  $throttleKey);

                if(RateLimiter::tooManyAttempts($throttleKey, 3)){
                    $seconds = RateLimiter::availableIn($throttleKey);
                    Log::error("login limit ". $seconds);
                    return new ApiResponseDto(false, "Too many attempts please try again in " .$seconds, HttpStatusCode::TOO_MANY_ATTEMPTS);
                }

                $loginDto = new LoginRequestDto($validate['email'], $validate['password']);
                Log::info("DTO ");

                $user = User::where(['email'=> $loginDto->getEmail(), 'status' => 'active'])->first();
                Log::info("User ". $user);
                if($user && Hash::check($loginDto->getPassword(), $user->password))
                {
                    Log::info("User found, password correct ". $user);
                    if($user->email_verified_at == null){
                        Log::info("Email not verified ". $user->email_verified_at);
                        //generate new code
                        $code = rand(111111, 999999);
                        //check if code exist and has expired
                        $checkCode = VerificationCode::where(['user_id' => $user->id])->first();
                        if($checkCode !=null && !Carbon::parse($checkCode->expiry)->isFuture()){
                            $checkCode->delete();
                             VerificationCode::create([
                            'code' => $code,
                            'user_id' => $user->id,
                            'expiry' => Carbon::now()->addMinutes(15)
                        ]);
                        Log::info("Verification Code ");
                        //trigger email event
                        event(new EmailVerificationEvent($code, $user));
                        }
                    }
                        //clear throttle limiter
                        RateLimiter::clear($throttleKey);
                        Auth::login($user);
                        //check tokens and delete them
                        Log::info("User logged in ". $user);


                        Log::info("User role ". $user->role);

                        $role = Role::where('role', $user->role)->first();
                        Log::info("User role ". $role);

                         $abilities =$this->_configService->getByRoleId($role->id);

                         Log::info("Abilities ");
                        if($user->tokens()->where(['tokenable_id' => $user->id, 'name' => 'auth_token'])->exists()){
                            $user->tokens()->delete();
                        }
                        $token = $user->createToken(
                            'auth_token', $abilities, now()->addWeek()
                        )->plainTextToken;


                        $userEntity = new UserEntity($user);
                        $loginResponse = new LoginResponseDto($userEntity, $token);
                        Log::info("User Entity ");
                        Log::info("User Login Response ");
                     $user->last_login = Carbon::today();
                     $user->save();

                     if($user->is_2fa_enabled){
                        $code = rand(100000, 999999);
                        $user->two_factor_code = $code;
                        $user->two_factor_expires_at = Carbon::now()->addMinutes(10);
                        $user->save();
                        event(new TwoFactorCodeEvent($user, $code));
                     }
                     if($user->chatId == null){
                        $chatId = "@".Str::random(5). rand(11,990);
                        Log::info("User Chat Id generated" .$chatId);
                        $user->chatId = $chatId;
                        $user->save();
                     }
                return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());

                }else if(!$user){
                    Log::info("User not found ");
                    RateLimiter::hit($throttleKey, 30);
                    return new ApiResponseDto(false, " It's either Email Provided is invalid or you have been deactivated from the system, please contact guppa support if your email is correct.", HttpStatusCode::NOT_FOUND);
                }else{
                    Log::info("Password wrong ");
                    RateLimiter::hit($throttleKey, 30);
                    return new ApiResponseDto(false, "Invalid password", HttpStatusCode::BAD_REQUEST);
                }

            }

        } catch (\Exception $e) {
            Log::info("Server error ". $e->getMessage());
            return new ApiResponseDto(false, "Server error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


    public function redirectToFacebook()
    {
        $url =  Socialite::driver('facebook')->redirect()->getTargetUrl();
        return new ApiResponseDto(true, "Redirect URL to facebook", HttpStatusCode::ACCEPTED, $url);
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return new ApiResponseDto(true, "Unable to login using Facebook. Please try again. " .$e->getMessage(), HttpStatusCode::BAD_REQUEST);
        }
        $userExist = User::where('email', $facebookUser->email)->first();
        if ($userExist) {
            $userExist->update([
                'facebook_id' => $facebookUser->getId(),
            ]);

            Auth::login($userExist);
            if($userExist->tokens()->where(['tokenable_id' => $userExist->id, 'name' => 'auth_token'])->exists()){
                $userExist->tokens()->delete();
            }

            $token = $userExist->createToken(
                'auth_token', ['temp'], now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($userExist);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());
        }

        $facebook = User::where('facebook_id', $facebookUser->getId())->first();
        $authUser = "";
        if ($facebook == null) {
            $user = new User();
            $splitName = explode(' ', $facebookUser->name);

            $user->first_name = $splitName[0];
            $user->last_name = $splitName[1] ?? " Update";
            $user->phone_no = "0800000000";
            $user->email =  $facebookUser->email;
            $user->country = "NigUpdate";
            $user->password = Hash::make("password");
            $user->role = Str::upper("None");
            $user->facebook_id = $facebookUser->getId();
            $user->save();

            $authUser = $user;
            Auth::login($authUser);
            if($authUser->tokens()->where(['tokenable_id' => $authUser->id, 'name' => 'auth_token'])->exists()){
                $authUser->tokens()->delete();
            }

            $token = $authUser->createToken(
                'auth_token', ['temp'], now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($authUser);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());
        }else {
            $authUser = $facebook;
            Auth::login($authUser);
            //check tokens and delete them
            if($authUser->tokens()->where(['tokenable_id' => $authUser->id, 'name' => 'auth_token'])->exists()){
                $authUser->tokens()->delete();
            }
            $role = Role::query()->where('role', $authUser->role)->first()->id;

             $abilities =$this->_configService->getByRoleId($role);


            $token = $authUser->createToken(
                'auth_token', $abilities, now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($authUser);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());

        }

    }

    public function redirectToGoogle()
    {
        $url =  Socialite::driver('google')->redirect()->getTargetUrl();
        return new ApiResponseDto(true, "Redirect URL to google", HttpStatusCode::ACCEPTED, $url);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return new ApiResponseDto(true, "Unable to login using google. Please try again. " .$e->getMessage(), HttpStatusCode::BAD_REQUEST);
        }
        $userExist = User::where('email', $googleUser->email)->first();
        if ($userExist) {
            $userExist->update([
                'google_id' => $googleUser->getId(),
            ]);

            Auth::login($userExist);
            if($userExist->tokens()->where(['tokenable_id' => $userExist->id, 'name' => 'auth_token'])->exists()){
                $userExist->tokens()->delete();
            }

            $token = $userExist->createToken(
                'auth_token', ['temp'], now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($userExist);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());
        }

        $google = User::where('google_id', $googleUser->getId())->first();
        $authUser = "";
        if ($google == null) {
            $user = new User();
            $splitName = explode(' ', $googleUser->name);

            $user->first_name = $splitName[0];
            $user->last_name = $splitName[1] ?? " Update";
            $user->phone_no = "08000000000";
            $user->email =  $googleUser->email;
            $user->country = "NigUpdate";
            $user->password = Hash::make("password");
            $user->role = Str::upper("None");
            $user->email_verified_at = Carbon::now();
            $user->google_id = $googleUser->getId();
            $user->save();

            $authUser = $user;
            Auth::login($authUser);
            if($authUser->tokens()->where(['tokenable_id' => $authUser->id, 'name' => 'auth_token'])->exists()){
                $authUser->tokens()->delete();
            }

            $token = $authUser->createToken(
                'auth_token', ['temp'], now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($authUser);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());
        }else {
            $authUser = $google;
            $authUser->email_verified_at = Carbon::now();
            $authUser->save();
            Auth::login($authUser);
            //check tokens and delete them
            if($authUser->tokens()->where(['tokenable_id' => $authUser->id, 'name' => 'auth_token'])->exists()){
                $authUser->tokens()->delete();
            }
            $role = Role::query()->where('role', $authUser->role)->first()->id;

             $abilities =$this->_configService->getByRoleId($role);


            $token = $authUser->createToken(
                'auth_token', $abilities, now()->addWeek()
            )->plainTextToken;
            $userEntity = new UserEntity($authUser);
            $loginResponse = new LoginResponseDto($userEntity, $token);
            return new ApiResponseDto(true, "User successfully logged in", HttpStatusCode::OK, $loginResponse->toArray());

        }

    }

    public function enable2fa()
    {

       try {
            if(auth()->check()){
                $user = Auth::user();
                $code = rand(100000, 999999);
                $userRecord = User::findOrFail($user->id);
                $userRecord->two_factor_code = $code;
                $userRecord->two_factor_expires_at = Carbon::now()->addMinutes(10);
                $userRecord->save();
                event(new TwoFactorCodeEvent($userRecord, $code));
                return new ApiResponseDto(true, "Two factor code sent successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (Exception $e) {
            return new ApiResponseDto(false, "Server Error", HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }

    public function disable2fa()
    {

       try {
            if(auth()->check()){
                $user = Auth::user();
                $userRecord = User::findOrFail($user->id);
                $userRecord->two_factor_code = null;
                $userRecord->two_factor_expires_at = null;
                $userRecord->is_2fa_enabled = false;
                $userRecord->save();

                $fa = TwoFaTracker::where('user_id', $userRecord->id)->first();
                        if($fa != null){
                            $fa->delete();
                        }

                return new ApiResponseDto(true, "Two factor disabled successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (Exception $e) {
            return new ApiResponseDto(false, "Server Error", HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }


    public function resendCode()
    {

       try {
            if(auth()->check()){
                $user = Auth::user();
                $code = rand(100000, 999999);
                $userRecord = User::findOrFail($user->id);
                $userRecord->two_factor_code = $code;
                $userRecord->two_factor_expires_at = Carbon::now()->addMinutes(10);
                $userRecord->save();
                event(new TwoFactorCodeEvent($userRecord, $code));
                return new ApiResponseDto(true, "Two factor code sent successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (Exception $e) {
            return new ApiResponseDto(false, "Server Error", HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }

    public function verify2fa(Request $request)
    {
       try {
            if(auth()->check()){

                $validator = Validator::make($request->all(), [
                    'code' => 'required',
                ]);

                if($validator->fails()){
                    return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
                }else{
                    $validate = $validator->validated();
                    $user = Auth::user();
                    $userRecord = User::findOrFail($user->id);
                    $dto = new TwoFARequestDto($validate['code']);
                    Log::info("incoming code ", [$dto->code]);
                    Log::info("db code ", [$userRecord->two_factor_code]);
                    Log::info("incoming code date ", [$userRecord->two_factor_expires_at]);
                    if ($userRecord->two_factor_code == (string)$dto->code && Carbon::parse($userRecord->two_factor_expires_at)->isFuture()) {
                        $userRecord->is_2fa_enabled = true;
                        $userRecord->two_factor_code = null;
                        $userRecord->two_factor_expires_at = null;
                        $userRecord->save();

                        $fa = TwoFaTracker::where('user_id', $userRecord->id)->first();
                        if($fa != null){
                            $fa->update([
                                'name' => "2fa_verified",
                                'is_verified' => true
                            ]);
                        }else{
                            $userRecord->twofa()->create([
                                'name' => "2fa_verified",
                                'is_verified' => true
                            ]);
                        }

                        return new ApiResponseDto(true, "2FA has been enabled successfully.", HttpStatusCode::OK);
                    } else {
                        return new ApiResponseDto(false, "Invalid Code supplied or Code has expired", HttpStatusCode::BAD_REQUEST);
                    }
             }

            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }

    public function prompt()
    {
        return new ApiResponseDto(false, "2FA is enabled please verify your login", HttpStatusCode::TWO_FA_REQUIRED);
    }

    public function verify(Request $request)
    {
        try {
            if(auth()->check()){

                $validator = Validator::make($request->all(), [
                    'code' => 'required',
                ]);

                if($validator->fails()){
                    return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
                }else{
                    $validate = $validator->validated();
                    $user = Auth::user();
                    $userRecord = User::findOrFail($user->id);
                    $dto = new TwoFARequestDto($validate['code']);
                    Log::info("incoming code ", [$dto->code]);
                    Log::info("db code ", [$userRecord->two_factor_code]);
                    Log::info("incoming code date ", [$userRecord->two_factor_expires_at]);
                    if ((string)$dto->code ===  $userRecord->two_factor_code && Carbon::parse($userRecord->two_factor_expires_at)->isFuture()) {
                        $fa = TwoFaTracker::where('user_id', $userRecord->id)->first();
                        if($fa != null){
                            $fa->update([
                                'is_verified' => true
                            ]);
                        }
                        $userRecord->two_factor_code = null;
                        $userRecord->two_factor_expires_at = null;
                        $userRecord->save();


                        return new ApiResponseDto(true, "Login verified successfully.", HttpStatusCode::OK);
                    } else {
                        return new ApiResponseDto(false, "Unable to verify, Invalid or expired Code supplied", HttpStatusCode::BAD_REQUEST);
                    }
             }

            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }


    public function resendEmailCode()
    {

       try {
            if(auth()->check()){
               Log::info("Login User ". $this->_currentUser);
                $code = rand(111111, 999999);
                Log::info("Code generated ");

                //check if code exist and has expired
                $checkCode = VerificationCode::where(['user_id' => $this->_currentUser->id])->first();
                if($checkCode !=null){
                    $checkCode->delete();
                    Log::info("Existing code deleted ");

                }

                VerificationCode::create([
                    'code' => $code,
                    'user_id' => $this->_currentUser->id,
                    'expiry' => Carbon::now()->addMinutes(10)
                ]);
                Log::info("New Code Generated ");

                //trigger email event
                event(new EmailVerificationEvent($code, $this->_currentUser));
                Log::info("Code sent via email ");
                return new ApiResponseDto(true, "Email Verification code have been resent successfully", HttpStatusCode::OK);
            }else{
                Log::error("User not logged ");
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (Exception $e) {
             Log::error("Server Error " . $e->getMessage());
            return new ApiResponseDto(false, "Server Error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }


    public function prompt_email()
    {
        return new ApiResponseDto(false, "Please verify your email address", HttpStatusCode::BAD_REQUEST);
    }

    public function verify_email(Request $request)
    {
        try {
            if(auth()->check()){

                $validator = Validator::make($request->all(), [
                    'code' => 'required|numeric',
                ]);

                if($validator->fails()){
                    return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
                }else{
                    $validate = $validator->validated();
                    $user = Auth::user();
                    $userRecord = User::findOrFail($user->id);
                   $code = VerificationCode::where(['user_id' => $user->id])->first();

                    if ($code->code == $validate['code'] && Carbon::parse($code->expiry)->isFuture()) {
                        $userRecord->email_verified_at = Carbon::now();
                        $userRecord->save();

                        $code->delete();
                        return new ApiResponseDto(true, "Email verified successfully.", HttpStatusCode::OK);
                    } else {
                        return new ApiResponseDto(false, "Invalid Code supplied or Code has expired, request for a new code", HttpStatusCode::BAD_REQUEST);
                    }
             }

            }else{
                return new ApiResponseDto(false, "User not logged in", HttpStatusCode::UNAUTHORIZED);
            }
       } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }

    public function Logout(){
        $user = Auth::user();
        $userRecord = User::findOrFail($user->id);
        $userRecord->tokens()->delete();
        $userRecord->twofa()->update([
            'is_verified' => false
        ]);
        return new ApiResponseDto(false, "User logged out", HttpStatusCode::OK);
    }

    public function Onboard(Request $request): ApiResponseDto
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'email' => ['required'],
                'password' => ['required', Password::min(10)->letters()->mixedCase()->symbols()->numbers()->uncompromised()],
                'password_confirmation' => ['required', 'same:password']
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "validation error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $encryptedEmail = urldecode($validated['email']);
            $decryptEmail = Crypt::decryptString($encryptedEmail);
            Log::info("Decrypted email : ", [$decryptEmail]);
            $user = User::where("email",$decryptEmail)->first();
            if(!$user){
                return new ApiResponseDto(false, "Not Found ", HttpStatusCode::NOT_FOUND);
            }
            if($user->IsActivated){
                return new ApiResponseDto(true, "Activation completed", HttpStatusCode::OK);
            }
            $user->password = Hash::make($validated['password']);
            $user->status = "active";
            $user->email_verified_at = Carbon::now();
            $user->IsActivated = true;
            $user->save();
            return new ApiResponseDto(true, "Activation completed", HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
