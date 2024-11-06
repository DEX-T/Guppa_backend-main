<?php

namespace App\Services\Account;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\FreelancerPortfolio\PortfolioRequestDto;
use App\Domain\DTOs\Request\PasswordReset\PasswordResetRequestDto;
use App\Domain\DTOs\Request\ProfilePhoto\ProfileUploadRequestDto;
use App\Domain\DTOs\Response\Account\FreelancerProfileResponseDto;
use App\Domain\DTOs\Response\Account\FreelancerPublicProfileResponseDto;
use App\Domain\DTOs\Response\Bid\BidResponseDto;
use App\Domain\DTOs\Response\Bid\FreelancerBidResponseDto;
use App\Domain\DTOs\Response\LoginResponseDto;
use App\Domain\DTOs\Response\Users\ClientResponseDto;
use App\Domain\DTOs\Response\Users\UserResponseDto;
use App\Domain\Entities\BidEntity;
use App\Domain\Entities\FreelancerOnBoardingEntity;
use App\Domain\Entities\FreelancerProfileEntity;
use App\Domain\Entities\UserEntity;
use App\Domain\Interfaces\Account\IUserService;
use App\enums\HttpStatusCode;
use App\enums\UserRoles;
use App\Events\ResetPasswordEvent;
use App\Helpers\GeneralHelper;
use App\Helpers\UserRoleHelper;
use App\Models\Bid;
use App\Models\BidPaymentConfig;
use App\Models\FreelancerOnBoarding;
use App\Models\FreelancerPortfolio;
use App\Models\GuppaPasswordRestToken;
use App\Models\TwoFaTracker;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserService implements IUserService
{
    // Implement your service methods here
    protected $_currentUser;
    public function __construct() {
       $this->_currentUser = Auth::user();
    }

    

    public function getUserCheckList(): ApiResponseDto
    {
       $user = User::findOrFail($this->_currentUser->id);
       if($user != null){
          $onBoarded = FreelancerOnBoarding::where('user_id', $user->id)->first();
          $verified = Verification::where(['user_id' => $user->id, 'status' => 'approved'])->first();
          $tracker = TwoFaTracker::where('user_id', $user->id)->first();
        // if($tracker && $tracker->is_verified){
          $dto =  [
                "IsEmailVerified"=> $user->email_verified_at != null ? true : false,
                "IsOnBoarded"=> $user->role == UserRoles::FREELANCER && $onBoarded != null ? true: false,
                "is_client_verified"=>  $user->role == UserRoles::CLIENT && $verified != null ? true: false,
                "Is2FaActive"=> $user->is_2fa_enabled == 1 ? true : false,
                "Is2FaVerified"=> $tracker !=null && $tracker->is_verified ? true: false,
           ];
           return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto);
       }else{
           return new ApiResponseDto(false, "User not found", HttpStatusCode::NOT_FOUND);
       }

    }

    public function ForgotPassword(Request $request): ApiResponseDto
    {

        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
             ]);

             if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
             }
             $validate = $validator->validated();
             $dto = new PasswordResetRequestDto($validate['email']);

             //check if email supplied is registerd on the db
            //  using the whereEmail
            $user = User::whereEmail($dto->email)->first();
            if (!$user) {
                return new ApiResponseDto(false, "Email not registered", HttpStatusCode::NOT_FOUND);
            }

            //check if user email is in the password reset db and delete it
            $token = GuppaPasswordRestToken::whereEmail($dto->email)->first();

            if ($token) {
                $token->delete();
            }

             $token = md5(GeneralHelper::generateRandomString(32));
         
                // $url = app()->environment('local') ? "http://localhost:3000/reset_password/"
                // : "https://guppa-ftend.vercel.app/reset_password/";
             $url = "http://localhost:3000/reset_password/";
               
            
              $url = $url.$token;
             GuppaPasswordRestToken::create([
                'email' => $dto->email,
                'token' => $token,
                'expiry' => Carbon::now()->addMinutes(15)
             ]);
             $data = [
                'email' => $dto->email,
                'token' => $token,
                'url' => $url,
                'user_name' => $user->last_name
             ];
             event(new ResetPasswordEvent($data));

             return new ApiResponseDto(true, "Reset password link sent successfully", HttpStatusCode::OK);

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function ResetPassword(Request $request): ApiResponseDto
    {
      try {
            $validator = Validator::make($request->all(),
            [
                'password' => ['required', Password::min(10)->letters()->mixedCase()->symbols()->numbers()->uncompromised()]
            ]);
            
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();
            Log::info("incoming token ", [$request->token]);
            //check if token hash check is in the table of GuppaPasswordResetTokens
            $token = GuppaPasswordRestToken::where("token", $request->token)->first();
            //check if token exist then check if it has expired
            if ($token == null || $token->expiry < Carbon::now()) {
                return new ApiResponseDto(false, "Invalid or expired token. please make a new request", HttpStatusCode::NOT_FOUND);
            }else{
                //update password
                $user = User::whereEmail($token->email)->first();
                Log::info("incoming token exists", [$token]);
                if (!$user) {
                    Log::info("user token does not exists");
                    return new ApiResponseDto(false, "User not found", HttpStatusCode::NOT_FOUND);
                }else{
                    
                    $user->password = Hash::make($validate['password']);
                    $user->save();
                    Log::info("user password changed");
                }
                //delete token
                $token->delete();
                return new ApiResponseDto(true, "Password reset successfully", HttpStatusCode::OK);
            }


      } catch (\Exception $e) {
        //return server error
        return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
      }

    }

    public function UploadProfile(Request $request): ApiResponseDto
    {
       try {

            $dbUser = User::findOrFail($this->_currentUser->id);

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'image_path' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();
            $dto = new ProfileUploadRequestDto($validate['image_path']);

            $dbUser->profile_photo = $dto->image_path;
            if($dbUser->save()){
                 return new ApiResponseDto(true, "Profile Photo uploaded successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error uploading profile photo", HttpStatusCode::BAD_REQUEST);
            }
       } catch (\Exception $e) {
             return new ApiResponseDto(false, "Server Error ", HttpStatusCode::INTERNAL_SERVER_ERROR);

       }

    }

    public function getUserById(int $id): ApiResponseDto
    {
       try {
            if(Gate::denies('view_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $user = User::where(['id' => $id, 'status' => 'active'])->first();
            if($user != null){
                $userEntity = new UserEntity($user);
                $userDto =  new UserResponseDto($userEntity);

                return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
            }else{
                return new ApiResponseDto(false, "User Not Found", 404);
            }
       } catch (\Exception $e) {
        return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);


       }

    }

    public function getAllUsers(): ApiResponseDto
    {
        if(Gate::denies('viewAny_user', $this->_currentUser)){
            return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
        }

        $users = User::where(['role' => UserRoles::FREELANCER])->get();
        if(count($users) > 0){
            $userDto = $users->map(function ($users) {
                $userEntity = new UserEntity($users);
                return new UserResponseDto($userEntity);
            });
            return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
        }else{
            return new ApiResponseDto(false, "Not Found", 404);
        }
    }

    public function getFreelancerProfile(int $userId): ApiResponseDto
    {
        $freelancer = User::with('on_boarded')->where(['id' => $userId])->first();
        if(Gate::denies('view_profile', $freelancer)){
            return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
        }
        if($freelancer != null){
            //return response
            $freelancerEntity = new FreelancerProfileEntity($freelancer);
            $dto = new FreelancerProfileResponseDto($freelancerEntity);
            return new ApiResponseDto(true, 'User', HttpStatusCode::OK, $dto->toArray());

        }else{
            return new ApiResponseDto(false, 'User Not Found', HttpStatusCode::NOT_FOUND);
        }
    }


    
    public function getFreelancerPublicProfile(int $userId): ApiResponseDto
    {
        $freelancer = User::with('on_boarded')->where(['id' => $userId])->first();
        
        if($freelancer != null){
            //return response
            $freelancerEntity = new FreelancerProfileEntity($freelancer);
            $dto = new FreelancerPublicProfileResponseDto($freelancerEntity);
            return new ApiResponseDto(true, 'Freelancer', HttpStatusCode::OK, $dto->toArray());

        }else{
            return new ApiResponseDto(false, 'Freelancer Not Found', HttpStatusCode::NOT_FOUND);
        }
    }


    public function getClientById(int $id): ApiResponseDto
    {
        if(Gate::denies('view_user', $this->_currentUser)){
            return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
        }
       $user = User::where(['id' => $id, 'status' => 'active'])->first();
       if($user != null){
           $userEntity = new UserEntity($user);
           $userDto =  new UserResponseDto($userEntity);

           return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
       }else{
           return new ApiResponseDto(false, "User Not Found", 404);
       }

    }

    public function getAllClients(): ApiResponseDto
    {
        if(Gate::denies('viewAny_user', $this->_currentUser)){
            return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
        }
        $users = User::where(['role' => UserRoles::CLIENT])->get();
        if(count($users) > 0){
            $userDto = $users->map(function ($users) {
                $userEntity = new UserEntity($users);
                return new UserResponseDto($userEntity);
            });
            return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
        }else{
            return new ApiResponseDto(false, "Not Found", 404);
        }
    }

    public function getAllAdmins(): ApiResponseDto
    {
        if(Gate::denies('viewAny_user', $this->_currentUser)){
            return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
        }
        $users = User::where('role', '!=', UserRoles::FREELANCER)->where('role', '!=', UserRoles::CLIENT)->get();
        if(count($users) > 0){
            $userDto = $users->map(function ($users) {
                $userEntity = new UserEntity($users);
                return new UserResponseDto($userEntity);
            });
            return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
        }else{
            return new ApiResponseDto(false, "Not Found", 404);
        }
    }



    public function getBids(): ApiResponseDto
    {
        try {

            $bid = Bid::where('user_id', $this->_currentUser->id)->first();
          
            if($bid != null){
                $bidEntity = new BidEntity($bid);
                $bidDto = new FreelancerBidResponseDto($bidEntity);
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $bidDto->toArray());

            }else{
                $charge = BidPaymentConfig::findOrFail(1);
                $chargeDto = [
                    'unit_price' =>  floor($charge->amount)
                ];
                return new ApiResponseDto(true, "You do not have bid yet", HttpStatusCode::OK, $chargeDto);
            }


        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserBids(): ApiResponseDto
    {
        try {
            if(Gate::denies('IsAdmin', $this->_currentUser)){
                return new ApiResponseDto(false, "Unauthorized", HttpStatusCode::UNAUTHORIZED);
            }
            $bids = Bid::all();
            if($bids->isNotEmpty()){
                $dto = $bids->map(function($bid){
                    $bidEntity = new BidEntity($bid);
                    return new BidResponseDto($bidEntity);
                });
                return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function upsert_portfolio(Request $request): ApiResponseDto
    {
        if(Gate::denies("upsert_portfolio", $this->_currentUser)){
            return new ApiResponseDto(false, "Unauthorized", HttpStatusCode::UNAUTHORIZED);
        }
        $validator = Validator::make($request->all(), [
                'file_path' => ['required', 'string'],
                'description' => ['required', 'string'],
                'portfolio_id' => ['int']
        ]);

        if($validator->fails()){
            return new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
        }

        $validated = $validator->validated();
        $dto = new PortfolioRequestDto($validated['file_path'], $validated['description'], $validated['portfolio_id']);
        if($dto->portfolio_id != 0){
            $portfolio = FreelancerPortfolio::find($dto->portfolio_id);
            if($portfolio){
                $portfolio->file_path = $dto->file_path;
                $portfolio->description = $dto->description;
                $portfolio->user_id = $this->_currentUser->id;
                $portfolio->save();
                return new ApiResponseDto(true, "Portfolio updated successfully", HttpStatusCode::OK);
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }else{
            $portfolio = new FreelancerPortfolio();
            $portfolio->file_path = $dto->file_path;
            $portfolio->description = $dto->description;
            $portfolio->user_id = $this->_currentUser->id;
            $portfolio->save();
            return new ApiResponseDto(true, "Portfolio created successfully", HttpStatusCode::CREATED);
        }
    }

    public function delete_portfolio(int $id): ApiResponseDto
    {
        $portfolio = FreelancerPortfolio::find($id);
        if(Gate::denies("delete_portfolio", $this->_currentUser, $portfolio)){
            return new ApiResponseDto(false, "Unauthorized", HttpStatusCode::UNAUTHORIZED);
        }
        if($portfolio){
            $portfolio->delete();
            return new ApiResponseDto(true, "Portfolio deleted successfully", HttpStatusCode::OK);
        }else{
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }
    }

    public function generate_chatId(): ApiResponseDto
    {

        if(!$this->_currentUser->chatId){
            $chatId = "@".Str::random(5). rand(11,990);
            Log::info("User Chat Id generated" .$chatId);
            $user = User::findOrFail($this->_currentUser->id);
            $user->chatId = $chatId;
            $user->save();

            return new ApiResponseDto(true, "Chat Id generated", HttpStatusCode::OK, $user->chatId);
        }else{
            return new ApiResponseDto(true, "Chat Id already generated", HttpStatusCode::OK, $this->_currentUser->chatId);
        }
    }

    public function updateSkills(Request $request): ApiResponseDto
    {
        try {

            $dbUser = $this->getOnBoarding();

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'skills' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();

            $dbUser->skills = $validate['skills'];
            if($dbUser->save()){
                return new ApiResponseDto(true, "Skills updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating skills", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e, HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    public function updateHourlyRate(Request $request)
    {
        try {

            $dbUser = $this->getOnBoarding();

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'hourly_rate' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();

            $dbUser->hourly_rate = $validate['hourly_rate'];
            if($dbUser->save()){
                return new ApiResponseDto(true, "Hourly Rate updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Hourly rate", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateShortBio(Request $request): ApiResponseDto
    {
        try {

            $dbUser = $this->getOnBoarding();

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'short_bio' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();

            $dbUser->short_bio = $validate['short_bio'];
            if($dbUser->save()){
                return new ApiResponseDto(true, "Short bio updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating short bio", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e, HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateLanguage(Request $request): ApiResponseDto
    {
        try {
            $dbUser = $this->getOnBoarding();

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'language' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();

            $dbUser->language = $validate['language'];
            if($dbUser->save()){
                return new ApiResponseDto(true, "Language updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating language", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateLookingFor(Request $request): ApiResponseDto
    {
        try {

            $dbUser = $this->getOnBoarding();

            if(Gate::denies('update_user', $this->_currentUser)){
                return new ApiResponseDto(false, "You are not authorized to perform this action", HttpStatusCode::UNAUTHORIZED);
            }

            $validator = Validator::make($request->all(), [
                'looking_for' => 'required|string',
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validate = $validator->validated();

            $dbUser->looking_for = $validate['looking_for'];
            if($dbUser->save()){
                return new ApiResponseDto(true, "Looking for updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating looking for", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteUser(int $userId)
    {
        $user = $this->getUser($userId);
        $user->delete();
        return new ApiResponseDto(true, "User deleted", HttpStatusCode::OK);
    }
    public function activateUser(int $userId)
    {
        $user = $this->getUser($userId);
        $user->status = 'active';
        $user->save();
        return new ApiResponseDto(true, "User reactivated", HttpStatusCode::OK);
    }

    public function deactivateUser(int $userId)
    {
        $user = $this->getUser($userId);
        $user->status = 'inactive';
        $user->tokens()->delete();
        $user->save();
        return new ApiResponseDto(true, "User Deactivated and can no longer login", HttpStatusCode::OK);
    }




    private function getUser($userId){
        return User::where('id', $userId)->first();
    }

    private function getOnBoarding(){
        return FreelancerOnBoarding::where('user_id', $this->_currentUser->id)->first();
    }


    public function trackUserProfile(): ApiResponseDto
    {
        try {
            $progress = 20;
            $dbUser =  $this->getOnBoarding();
            $toComplete = [];
            if($dbUser != null){
                $progress +=20;
            }
            if($this->getUser($this->_currentUser->id)->profile_photo != "0000/default.png"){
                $progress+=20;
            }else{
                $toComplete[] = "profile_photo";
            }
            if($this->getUser($this->_currentUser->id)->gender){
                $progress+=20;
            }else{
                $toComplete[] = "gender";
            }
            if($this->getUser($this->_currentUser->id)->age_group){
                $progress+=20;
            }else{
                $toComplete[] = "age_group";
            }
            $data = [
                'to_complete' => $toComplete,
                'progress' => $progress
            ];
                return new ApiResponseDto(true, "Progress", HttpStatusCode::OK, $data);

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }


     /**
     * Update user details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function updateUserDetails(Request $request)
    {
        // Validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone_no'   => 'required|string|max:15',
            'country'    => 'required|string|max:255',
            'gender'     => 'nullable|in:male,female',
            'age_group'   => 'nullable'
        ];

        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return new ApiResponseDto(false, "Validation failed ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->all());
            
        }

        try {
            // Find the authenticated user
            $user = User::findOrFail($this->_currentUser->id);
            // Update user details
            $user->update($request->only(['first_name', 'last_name', 'phone_no', 'country', 'gender','age_group']));
            return new ApiResponseDto(true, "Details updated successfully", HttpStatusCode::OK);
            
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    
    public function getCurrentUser(): ApiResponseDto
    {
       try {
            $user = User::findOrFail($this->_currentUser->id);
            if($user != null){
                $userEntity = new UserEntity($user);
                $userDto =  new LoginResponseDto($userEntity);
                return new ApiResponseDto(true, "Successful", 200, $userDto->toArray());
            }else{
                return new ApiResponseDto(false, "User Not Found", 404);
            }
       } catch (\Exception $e) {
        return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);


       }

    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => ['required', Password::min(10)->letters()->mixedCase()->symbols()->numbers()->uncompromised()],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        if($validator->fails()){
            return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
        }

        $validated = $validator->validated();
        $user = User::find($this->_currentUser->id);
        if (!$user || !Hash::check($validated['old_password'], $user->password)){
            return new ApiResponseDto(false, "Invalid old password", HttpStatusCode::BAD_REQUEST);
        }

        $newPassword = Hash::make($validated['password']);
        $user->password  = $newPassword;
        $user->save();
        return new ApiResponseDto(true, "Password changed successfully", HttpStatusCode::ACCEPTED);
    }

}
