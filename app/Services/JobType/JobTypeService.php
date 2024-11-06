<?php

namespace App\Services\JobType;

use App\Domain\DTOs\Request\JobType\JobTypeRequestDto;
use App\Domain\DTOs\Request\JobType\UpdateJobTypeRequestDto;
use App\Domain\DTOs\Response\JobType\JobTypeResponseDto;
use App\Domain\Entities\JobType\JobTypeEntity;
use App\Domain\Interfaces\JobType\IJobTypeService;
use App\Models\JobTypeList;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Validator;

class JobTypeService implements IJobTypeService
{

    public function createJobType(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'type' => ['required'],
                'description' => ['required'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $dto = new JobTypeRequestDto($validated['type'], $validated['description']);
            $jobtype = new JobTypeList();
            $jobtype->type = $dto->type;
            $jobtype->description = $dto->description;
            $jobtype->status = "active";
            if ($jobtype->save()) {
                //return response
                return new ApiResponseDto(true, "Job Type created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Error creating JobType", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllJobType()
    {
        try {
            $jobtype = JobTypeList::all();

            if ($jobtype->isEmpty()) {
                return new ApiResponseDto(false, "No Job Type found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $jobtype->map(function($gigList){
                $jobtypeEntity = new JobTypeEntity($gigList);
                return new JobTypeResponseDto($jobtypeEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getJobTypebyId(int $id)
    {
        try {
            $jobtype = JobTypeList::findOrFail($id);

            if ($jobtype == null) {
                return new ApiResponseDto(false, "Job Type not found", HttpStatusCode::NOT_FOUND);
            }
            $jobtypeEntity = new JobTypeEntity($jobtype);
            $dto = new JobTypeResponseDto($jobtypeEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateJobType(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateJobTypeRequestDto(
                $validatedData['id'],
                $validatedData['type'],
                $validatedData['description'],

            );

            // 4. Find the JobType item by ID (from the DTO)
            $jobtype = JobTypeList::findOrFail($dto->id);

            if (!$jobtype) {
                return new ApiResponseDto(false, "JobType not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the Job Type
            $jobtype->update([
                'type' => $dto->type,
                'description' => $dto->description,
            ]);

            // 6. Prepare the response DTO
            $jobtypeEntity = new JobTypeEntity($jobtype);
            $jobtypeResponse = new JobTypeResponseDto($jobtypeEntity);

            return new ApiResponseDto(true, "Job Type updated successfully", HttpStatusCode::OK,  $jobtypeResponse->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteJobType(int $id)
    {
        try {
            $jobtype = JobTypeList::findOrFail($id);
            if ($jobtype) {
                $jobtype->delete();
                return new ApiResponseDto(true, "Gig deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Gig not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
