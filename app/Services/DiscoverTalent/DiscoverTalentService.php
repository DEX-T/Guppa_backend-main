<?php

namespace App\Services\DiscoverTalent;

use App\Models\Discover;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\DiscoverBackground;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\DiscoverTalent\DiscoverTalentEntity;
use App\Domain\Interfaces\DiscoverTalent\IDiscoverTalentService;
use App\Domain\DTOs\Request\DiscoverTalent\CreateDiscoverTalentDto;
use App\Domain\DTOs\Request\DiscoverTalent\UpdateDiscoverTalentDto;
use App\Domain\DTOs\Response\DiscoverTalent\DiscoverTalentResponseDto;
use App\Domain\Entities\DiscoverTalent\DiscoverTalentBackgroundEntity;
use App\Domain\DTOs\Response\DiscoverTalent\DiscoverTalentBackgroundResponseDto;
use App\Domain\DTOs\Request\DiscoverTalent\CreateDiscoverTalentBackgroundRequestDto;
use App\Domain\DTOs\Request\DiscoverTalent\UpdateDiscoverTalentBackgroundRequestDto;


class DiscoverTalentService implements IDiscoverTalentService
{
    protected $_currentUser;
    public function __construct() {
       $this->_currentUser =  Auth::user();
      
    }
    public function createDiscover(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'title' => ['required'],
                'description' => ['required', 'string'],
                'button_text' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new CreateDiscoverTalentDto($validated['title'], $validated['description'], $validated['button_text']);
            $discoverTalentExist = Discover::where('id', 1)->first();

            if($discoverTalentExist != null){
                $discoverTalentExist->title = $dto->title;
                $discoverTalentExist->description = $dto->description;
                $discoverTalentExist->button_text = $dto->button_text;
                $discoverTalentExist->save();
            }else{
                $DiscoverTalent = new Discover();
                $DiscoverTalent->title = $dto->title;
                $DiscoverTalent->description = $dto->description;
                $DiscoverTalent->button_text = $dto->button_text;
                $DiscoverTalent->save();
            return new ApiResponseDto(true, "Discover Talent saved successfully", HttpStatusCode::CREATED);
         }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function GetAllDiscover()
    {
        try {
            $DiscoverTalents = Discover::all();

            if ($DiscoverTalents->isEmpty()) {
                return new ApiResponseDto(false, "Not found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $DiscoverTalents->map(function ($discover) {
                $discoverEntity = new DiscoverTalentEntity($discover);
                return new DiscoverTalentResponseDto($discoverEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getDiscoverById(int $id)
    {
        try {
            $DiscoverTalent = Discover::findOrFail($id);

            if (!$DiscoverTalent) {
                return new ApiResponseDto(false, "Discover Talent not found", HttpStatusCode::NOT_FOUND);
            }
            $discoverEntity = new DiscoverTalentEntity($DiscoverTalent);
            $dto = new DiscoverTalentResponseDto($discoverEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getDiscoverTalent()
    {
        try {
            $DiscoverTalent = Discover::with('background')->where('id', 1)->first();

            if (!$DiscoverTalent) {
                return new ApiResponseDto(false, "Discover Talent not found", HttpStatusCode::NOT_FOUND);
            }
            $discoverEntity = new DiscoverTalentEntity($DiscoverTalent);
            $dto = new DiscoverTalentResponseDto($discoverEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDiscover(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'button_text' => ['required', 'string'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Creation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }


            $validatedData = $validator->validated();

            $dto = new UpdateDiscoverTalentDto(
                $validatedData['id'],
                $validatedData['title'],
                $validatedData['description'],
                $validatedData['button_text']
            );

            // 4. Find the NavbarMenu item by ID (from the DTO)
            $DiscoverTalent = Discover::findOrFail($dto->id);

            if (!$DiscoverTalent) {
                return new ApiResponseDto(false, "Discover Talent  not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the menu item
            $DiscoverTalent->update([
                'title' => $dto->title,
                'description' => $dto->description,
                'button_text' => $dto->button_text,

            ]);

            // 6. Prepare the response DTO
            $DiscoverTalentEntity = new DiscoverTalentEntity($DiscoverTalent);
            $DiscoverTalentResponseDto = new DiscoverTalentResponseDto($DiscoverTalentEntity);

            return new ApiResponseDto(true, "Discover Talent updated successfully", HttpStatusCode::OK, $DiscoverTalentResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDiscover(int $id)
    {
        try {
            $DiscoverTalent = Discover::findOrFail($id);

            if ($DiscoverTalent) {
                $DiscoverTalent->delete();
                return new ApiResponseDto(true, "Discover Talent deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Discover Talent not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function createDiscoverBackground(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'discover_id' => ['required', 'integer', 'exists:discovers,id'],
                'image_url' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();
            $dto = new CreateDiscoverTalentBackgroundRequestDto($validatedData['discover_id'], $validatedData['image_url']);
            $bgEx = DiscoverBackground::where('id', $dto->discover_id)->first();
            if($bgEx == null){
                $bg = new DiscoverBackground();
                $bg->image_url = $dto->image_url;
                $bg->discover_id = $dto->discover_id;
                $bg->save();
                return new ApiResponseDto(true, "Create Discover Background created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Background created already, update the existing one", HttpStatusCode::CONFLICT);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function GetAllDiscoverBackground()
    {
        try {
            $DiscoverTalentBackgrounds = DiscoverBackground::where('id', 1)->first();

            if ($DiscoverTalentBackgrounds == null) {
                return new ApiResponseDto(false, "Discover Background not found", HttpStatusCode::NOT_FOUND);
            }
                $discoverEntity = new DiscoverTalentBackgroundEntity($DiscoverTalentBackgrounds);
               $dto = new DiscoverTalentBackgroundResponseDto($discoverEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getDiscoverBackgroundById(int $id)
    {
        try {
            $DiscoverTalentBackground = DiscoverBackground::findOrFail($id);

            if (!$DiscoverTalentBackground) {
                return new ApiResponseDto(false, "Discover Background not found", HttpStatusCode::NOT_FOUND);
            }
                $discoverEntity = new DiscoverTalentEntity($DiscoverTalentBackground);
                $dto =  new DiscoverTalentResponseDto($discoverEntity);

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateDiscoverBackground(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer', 'exists:discover_backgrounds,id'],
                'discover_id' => ['required', 'integer'],
                'image_url' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }


            $validatedData = $validator->validated();

            $dto = new UpdateDiscoverTalentBackgroundRequestDto(
                $validatedData['id'],
                $validatedData['discover_id'],
                $validatedData['image_url'],
            );

            
            $DiscoverTalentBackground = DiscoverBackground::findOrFail($dto->id);

            if (!$DiscoverTalentBackground) {
                return new ApiResponseDto(false, "background not found", HttpStatusCode::NOT_FOUND);
            }

            $DiscoverTalentBackground->update([
                'discover_id' => $dto->discover_id,
                'image_url' => $dto->image_url
            ]);
            return new ApiResponseDto(true, "Discover Talent Background updated successfully", HttpStatusCode::OK);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteDiscoverBackground(int $id)
    {
        try {
            $DiscoverTalentBackground = DiscoverBackground::findOrFail($id);

            if ($DiscoverTalentBackground) {
                $DiscoverTalentBackground->delete();
                return new ApiResponseDto(true, "Discover Talent Background deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Discover Talent Background not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
