<?php

namespace App\Services\Skill;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\DTOs\Request\Skill\SkillRequestDto;
use App\Domain\DTOs\Response\Skill\SkillResponseDto;
use App\Domain\Entities\SkillEntity;
use App\Domain\Interfaces\Skill\ISkillService;
use App\enums\HttpStatusCode;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SkillService implements ISkillService
{

    public function upsertSkill(array $data): ApiResponseDto
    {
        try {
            $dto = new SkillRequestDto($data);
            $skills = explode(',', $dto->skill);
            Log::info("skills", [$skills]);
            if ($dto->skill_id == 0):
                Log::info("skills", [$skills]);
                foreach($skills as $skill){
                    $existingSkill = Skill::where('skill', $skill)->first();
                    // If the skill does not exist, create it
                    if (!$existingSkill) {
                        Skill::create([
                            "skill" => $skill,
                            "category_id" => $dto->category_id,
                            "created_at" => Carbon::now(),
                            "status" => "active"
                        ]);
                    }
                }
                $message = "Skill created";
            else:
                $Skill = Skill::where('id', $dto->skill_id)->first();
                $Skill->skill = $dto->skill;
                $Skill->category_id = $dto->category_id;
                $Skill->updated_at = Carbon::now();
                $Skill->save();
                $message = "Skill updated";
            endif;
            return new ApiResponseDto(true, $message, HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function deleteSkill(int $catId): ApiResponseDto
    {
        try {
            $Skill = Skill::where('id', $catId)->first();
            if($Skill != null){
                $Skill->delete();
                return new ApiResponseDto(true, "Skill deleted", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function activateSkill(int $catId): ApiResponseDto
    {
        try {
            $Skill = Skill::where('id', $catId)->first();
            if($Skill != null){
                $Skill->setStatus("active");
                $Skill->save();
                return new ApiResponseDto(true, "Skill activated", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deactivateSkill(int $catId): ApiResponseDto
    {
        try {
            $Skill = Skill::where('id', $catId)->first();
            if($Skill != null){
                $Skill->setStatus("inactive");
                $Skill->save();
                return new ApiResponseDto(true, "Skill deactivated", HttpStatusCode::OK);

            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getAllSkill(): ApiResponseDto
    {
        try {
            $skills = Skill::all();
            if($skills->isNotEmpty()){
                $dto = $skills->map(function ($Skill){
                    $SkillEntity = new SkillEntity($Skill);
                    return new SkillResponseDto($SkillEntity);
                });
                return new ApiResponseDto(true, "Skills fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
   
    public function getAllSkills(int $category_id): ApiResponseDto
    {
        try {
            $skills = Skill::where(['category_id' => $category_id, 'status'=> 'active'])->get();
            if($skills->isNotEmpty()){
                $dto = $skills->map(function ($Skill){
                    $SkillEntity = new SkillEntity($Skill);
                    return new SkillResponseDto($SkillEntity);
                });
                return new ApiResponseDto(true, "Skills fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


    public function getSkill(int $catId): ApiResponseDto
    {
        try {
            $Skill = Skill::where('id', $catId)->first();
            if($Skill != null){
                $SkillEntity = new SkillEntity($Skill);
                $dto = new SkillResponseDto($SkillEntity);
                return new ApiResponseDto(true, "Skill fetched", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);

        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ". $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
}
