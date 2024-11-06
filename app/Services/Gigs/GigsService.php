<?php

namespace App\Services\Gigs;

use App\Models\GigList;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Domain\Interfaces\Gigs\IGigsService;
use App\Domain\DTOs\Request\Gigs\GigsRequestDto;
use App\Domain\DTOs\Request\Gigs\UpdateGigsRequestDto;
use App\Domain\DTOs\Response\Gigs\GigsResponseDto;
use App\Domain\Entities\Gigs\GigsEntity;

class GigsService implements IGigsService
{
    protected $_currentUser;
    public function __construct() {
       $this->_currentUser =  Auth::user();
      
    }

    public function createGigs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'description' => ['required'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $dto = new GigsRequestDto($validated['name'], $validated['description']);
            $gigsList = new GigList();
            $gigsList->name = $dto->name;
            $gigsList->description = $dto->description;
            $gigsList->status = "active";
            if ($gigsList->save()) {
                //return response
                return new ApiResponseDto(true, "Gigs created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Error creating Gigs", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllGigs()
    {
        try {
            $gigslist = GigList::all();

            if ($gigslist->isEmpty()) {
                return new ApiResponseDto(false, "No Gigs found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $gigslist->map(function($gigList){
                $gigsEntity = new GigsEntity($gigList);
                return new GigsResponseDto($gigsEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getGigsbyId(int $id)
    {
        try {
            $gigsList = GigList::findOrFail($id);

            if ($gigsList == null) {
                return new ApiResponseDto(false, "Gigs not found", HttpStatusCode::NOT_FOUND);
            }
            $gigsEntity = new GigsEntity($gigsList);
            $dto = new GigsResponseDto($gigsEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateGigs(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'name' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateGigsRequestDto(
                $validatedData['id'],
                $validatedData['name'],
                $validatedData['description'],

            );

            // 4. Find the Gigs item by ID (from the DTO)
            $gigslist = GigList::findOrFail($dto->id);

            if (!$gigslist) {
                return new ApiResponseDto(false, "Gigs not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the menu item
            $gigslist->update([
                'name' => $dto->name,
                'description' => $dto->description,
            ]);

            // 6. Prepare the response DTO
            $gigsEntity = new GigsEntity($gigslist);
            $gigsResponseDto = new GigsResponseDto($gigsEntity);

            return new ApiResponseDto(true, "Gigs updated successfully", HttpStatusCode::OK,  $gigsResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteGigs(int $id)
    {
        try {
            
            $gigslist = GigList::findOrFail($id);
            if ($gigslist) {
                $gigslist->delete();
                return new ApiResponseDto(true, "Gig deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Gig not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
