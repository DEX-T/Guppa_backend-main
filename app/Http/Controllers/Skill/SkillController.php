<?php

namespace App\Http\Controllers\Skill;

use App\Domain\Interfaces\Skill\ISkillService;
use App\Http\Requests\SkillRequest;
use Illuminate\Http\Request;

class SkillController
{
    private  ISkillService $_skillService;

    function __construct(ISkillService $skillService)
    {
        $this->_skillService = $skillService;
    }

    /**
     * @OA\Post(
     *     path="/api/skill/upsert-skill",
     *     operationId="upsertSkill",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Create or update a skill",
     *     description="Creates a new skill or updates an existing one based on the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"skill_id", "skill","category_id"},
     *             @OA\Property(property="skill_id", type="integer", example=0),
     *             @OA\Property(property="skill", type="string", example="Web developer, deskstop application", description="skills separeted by comma  "),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function upsertSkill(SkillRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $response = $this->_skillService->upsertSkill($data);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Delete(
     *     path="/api/skill/delete/{id}",
     *     operationId="deleteSkill",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Delete a skill",
     *     description="Deletes a skill by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the skill to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="skill deleted",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="skill not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteSkill($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->deleteSkill($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Put(
     *     path="/api/skill/activate/{id}",
     *     operationId="activateSkill",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Activate a skill",
     *     description="Activates a skill by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the skill to activate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="skill activated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="skill not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function activateSkill($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->activateSkill($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }

    /**
     * @OA\Put(
     *     path="/api/skill/deactivate/{id}",
     *     operationId="deactivateSkill",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Deactivate a skill",
     *     description="Deactivates a skill by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the skill to deactivate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="skill deactivated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="skill not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deactivateSkill($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->deactivateSkill($id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message
        ], $response->code);
    }


    /**
     * @OA\Get(
     *     path="/api/skill/get_skill/{id}",
     *     operationId="getskillById",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Get a skill by ID",
     *     description="Fetches a skill by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the skill to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="skill retrieved",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="skill not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getSkillById(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->getSkill($request->id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }

   
    /**
     * @OA\Get(
     *     path="/api/skill/skills/{category_id}",
     *     operationId="getAllSkills",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Get all skills",
     *     description="Fetches all skills.",
     * @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         description="Category ID  to retrieve skills",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skills retrieved"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllSkills(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->getAllSkills($request->category_id);
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }



    /**
     * @OA\Get(
     *     path="/api/skill/get_skills_admin",
     *     operationId="getAllSkillsForAdmin",
     *     tags={"Skill"},
     *     security={{"sanctum":{}}},
     *     summary="Get all skills for admin",
     *     description="Fetches all skills admin.",
     *     @OA\Response(
     *         response=200,
     *         description="Skills retrieved",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllSkillsAdmin(): \Illuminate\Http\JsonResponse
    {
        $response = $this->_skillService->getAllskill();
        return response()->json( [
            'success' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ], $response->code);
    }
}
