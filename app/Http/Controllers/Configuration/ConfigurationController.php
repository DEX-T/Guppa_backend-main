<?php

namespace App\Http\Controllers\Configuration;

use App\Domain\Interfaces\DocumentConfig\IDocumentConfigService;
use App\enums\UserRoles;
use App\Http\Requests\DocumentTypeConfigurationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Domain\Interfaces\Configuration\IConfigurationService;

class ConfigurationController extends Controller
{
    private IConfigurationService $_configService;
    private IDocumentConfigService $_docTypeService;
    protected ?\Illuminate\Contracts\Auth\Authenticatable $_currentUser;
    public function __construct(IConfigurationService $configService, IDocumentConfigService $docTypeService)
    {
        $this->_configService = $configService;
        $this->_docTypeService = $docTypeService;
        $this->_currentUser = Auth::user();

    }

    #region role
  /**
     * @OA\Post(
     *     path="/api/configuration/create-role",
     *     operationId="createRole",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new role",
     *     description="create a new role",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="string")
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
    public function create_role(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->createRole($request);
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
     * @OA\Get(
     *     path="/api/configuration/roles",
     *     operationId="getRolesList",
     *     tags={"Configuration"},
     *     summary="Get list of roles",
     *     description="Returns list of roles",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllRoles(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_configService->getRoles();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

     /**
     * @OA\Get(
     *     path="/api/configuration/role/{id}",
     *     operationId="getRoleById",
     *     tags={"Configuration"},
     *     summary="Get role information",
     *     description="Returns role data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Role not found"
     *     )
     * )
     */

    //Get  role by id
    public function getRoleById(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_configService->getRole($request->id);
       if($roleDto->status){
            return response()->json([
                'success' => $roleDto->status,
                'message' => $roleDto->message,
                'data' => $roleDto->data
            ], $roleDto->code);
       }else{
            return response()->json([
                'success' => $roleDto->status,
                'message' => $roleDto->message,
                'data' => $roleDto->data
            ], $roleDto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/role/update",
     *     operationId="updateRole",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new role",
     *     description="update a new role",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role", "role_id"},
     *             @OA\Property(property="role", type="string", example="string"),
     *             @OA\Property(property="role_id", type="int", example="0")
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
    public function update_role(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->updateRole($request);
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
     * @OA\DELETE(
     *     path="/api/configuration/role/delete/{id}",
     *     operationId="deleteRole",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete role",
     *     description="delete role",
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
    public function delete_role(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->deleteRole($request->id);
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
    #endregion role

    #region ability
      /**
     * @OA\Post(
     *     path="/api/configuration/create-ability",
     *     operationId="createAbility",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new ability",
     *     description="create a new ability",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ability", "role_id",},
     *             @OA\Property(property="ability", type="string", example="string"),
     *             @OA\Property(property="role_id", type="int", example="0")
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_ability(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createAbility($request);

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
     * @OA\Get(
     *     path="/api/configuration/abilities",
     *     operationId="getAbilityList",
     *     tags={"Configuration"},
     *     summary="Get list of abilities",
     *     description="Returns list of abilities",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllAbilities(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_configService->getAbilities();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/ability/{id}",
     *     operationId="getAbilityById",
     *     tags={"Configuration"},
     *     summary="Get ability information",
     *     description="Returns ability data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Ability not found"
     *     )
     * )
     */

    //Get  role by id
    public function getAbilityById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getAbility($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/ability/update",
     *     operationId="updateAbility",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new ability",
     *     description="update a new ability",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ability", "role_id", "ability_id"},
     *             @OA\Property(property="ability", type="string", example="string"),
     *             @OA\Property(property="role_id", type="int", example="0"),
     *             @OA\Property(property="ability_id", type="int", example="0"),
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
    public function update_ability(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updateAbility($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/ability/delete/{id}",
     *     operationId="deleteAbility",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete ability",
     *     description="delete ability",
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
    public function delete_ability(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deleteAbility($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion ability

    #region prefix
      /**
     * @OA\Post(
     *     path="/api/configuration/create-prefix",
     *     operationId="createPrefix",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new prefix",
     *     description="create a new prefix",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"prefix"},
     *             @OA\Property(property="prefix", type="string", example="string"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_prefix(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createPrefix($request);

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
     * @OA\Get(
     *     path="/api/configuration/prefixes",
     *     operationId="getPrefixList",
     *     tags={"Configuration"},
     *     summary="Get list of prefixes",
     *     description="Returns list of prefixes",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllPrefixes(): \Illuminate\Http\JsonResponse
    {
        $userDto = $this->_configService->getPrefixes();
        return response()->json([
            'success' => $userDto->status,
            'message' => $userDto->message,
            'data' => $userDto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/prefix/{id}",
     *     operationId="getPrefixById",
     *     tags={"Configuration"},
     *     summary="Get prefix information",
     *     description="Returns prefix data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Prefix not found"
     *     )
     * )
     */

    //Get  role by id
    public function getPrefixById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getPrefix($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/prefix/update",
     *     operationId="updatePrefix",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new prefix",
     *     description="update a new prefix",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"prefix", "prefix_id"},
     *             @OA\Property(property="prefix", type="string", example="string"),
     *             @OA\Property(property="prefix_id", type="int", example="0"),
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
    public function update_prefix(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updatePrefix($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/prefix/delete/{id}",
     *     operationId="deletePrefix",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete prefix",
     *     description="delete prefix",
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
    public function delete_prefix(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deletePrefix($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion prefix

    #region general middleware

    /**
     * @OA\Post(
     *     path="/api/configuration/create-middleware",
     *     operationId="createMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new middleware",
     *     description="create a new middleware",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key", "value", "prefix_id"},
     *             @OA\Property(property="key", type="string", example="string"),
     *             @OA\Property(property="value", type="string", example="string"),
     *             @OA\Property(property="prefix_id", type="int", example="0"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_middleware(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createGeneralMiddleware($request);

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
     * @OA\Get(
     *     path="/api/configuration/middlewares",
     *     operationId="getMiddlewareList",
     *     tags={"Configuration"},
     *     summary="Get list of middlewares",
     *     description="Returns list of middlewares",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllMiddlewares(): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getGeneralMiddlewares();
        return response()->json([
            'success' => $dto->status,
            'message' => $dto->message,
            'data' => $dto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/middleware/{id}",
     *     operationId="getMiddlewareById",
     *     tags={"Configuration"},
     *     summary="Get middleware information",
     *     description="Returns middleware data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Middleware not found"
     *     )
     * )
     */

    //Get  role by id
    public function getMiddlewareById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getGeneralMiddleware($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/middleware/update",
     *     operationId="updateMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new middleware",
     *     description="update a new middleware",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key", "value", "prefix_id", "middleware_id"},
     *             @OA\Property(property="key", type="string", example="string"),
     *             @OA\Property(property="value", type="string", example="string"),
     *             @OA\Property(property="prefix_id", type="int", example="0"),
     *            @OA\Property(property="middleware_id", type="int", example="0"),
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
    public function update_middleware(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updateGeneralMiddleware($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/middleware/delete/{id}",
     *     operationId="deleteMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete middleware",
     *     description="delete middleware",
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
    public function delete_middleware(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deleteGeneralMiddleware($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion general middleware

    #region controllers

      /**
     * @OA\Post(
     *     path="/api/configuration/create-controller",
     *     operationId="createController",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new controller",
     *     description="create a new controller",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"prefix_id", "middleware_id", "controller"},
     *             @OA\Property(property="prefix_id", type="int", example="0"),
     *             @OA\Property(property="middleware_id", type="int", example="0"),
     *             @OA\Property(property="controller", type="string", example="string"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_controller(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createController($request);

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
     * @OA\Get(
     *     path="/api/configuration/controllers",
     *     operationId="getControllerList",
     *     tags={"Configuration"},
     *     summary="Get list of controllers",
     *     description="Returns list of controllers",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllControllers(): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getControllers();
        return response()->json([
            'success' => $dto->status,
            'message' => $dto->message,
            'data' => $dto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/controller/{id}",
     *     operationId="getControllerById",
     *     tags={"Configuration"},
     *     summary="Get controller information",
     *     description="Returns controller data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Controller not found"
     *     )
     * )
     */

    //Get  role by id
    public function getControllerById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getController($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/controller/update",
     *     operationId="updateController",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new controller",
     *     description="update a new controller",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"prefix_id", "middleware_id", "controller", "controller_id"},
     *             @OA\Property(property="prefix_id", type="int", example="0"),
     *             @OA\Property(property="middleware_id", type="int", example="0"),
     *             @OA\Property(property="controller", type="string", example="string"),
     *            @OA\Property(property="controller_id", type="int", example="0"),
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
    public function update_controller(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updateController($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/controller/delete/{id}",
     *     operationId="deleteController",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete controller",
     *     description="delete controller",
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
    public function delete_controller(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deleteController($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion controllers


    #region routes

      /**
     * @OA\Post(
     *     path="/api/configuration/create-route",
     *     operationId="createRoute",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new route",
     *     description="create a new route",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"controller_id", "method", "action", "url", "name"},
     *             @OA\Property(property="controller_id", type="int", example="0"),
     *             @OA\Property(property="method", type="string", example="string"),
     *             @OA\Property(property="action", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="string"),
     *             @OA\Property(property="name", type="string", example="string"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_route(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createRoute($request);

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
     * @OA\Get(
     *     path="/api/configuration/routes",
     *     operationId="getRoutesList",
     *     tags={"Configuration"},
     *     summary="Get list of routes",
     *     description="Returns list of routes",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllRoutes(): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getRoutes();
        return response()->json([
            'success' => $dto->status,
            'message' => $dto->message,
            'data' => $dto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/route/{id}",
     *     operationId="getRouteById",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Get route information",
     *     description="Returns route data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="route not found"
     *     )
     * )
     */

    //Get  role by id
    public function getRouteById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getRoute($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/route/update",
     *     operationId="updateRoute",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new route",
     *     description="update a new route",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"controller_id", "method", "action", "url", "name", "route_id"},
     *             @OA\Property(property="controller_id", type="int", example="0"),
     *             @OA\Property(property="method", type="string", example="string"),
     *             @OA\Property(property="action", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="string"),
     *             @OA\Property(property="name", type="string", example="string"),
     *             @OA\Property(property="route_id", type="int", example="0"),
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
    public function update_route(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updateRoute($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/route/delete/{id}",
     *     operationId="deleteRoute",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete route",
     *     description="delete route",
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
    public function delete_route(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deleteRoute($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion routes

    #region sub middleware

    /**
     * @OA\Post(
     *     path="/api/configuration/create-submiddleware",
     *     operationId="createSubMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new sub middleware",
     *     description="create a new sub middleware",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value"},
     *             @OA\Property(property="value", type="string", example="string"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function create_SubMiddleware(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->createSubMiddleware($request);

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
     * @OA\Get(
     *     path="/api/configuration/sub-middlewares",
     *     operationId="getSubMiddlewareList",
     *     tags={"Configuration"},
     *     summary="Get list of sub middlewares",
     *     description="Returns list of sub middlewares",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */
    //Get All abilities
    public function getAllSubMiddlewares(): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getSubMiddlewares();
        return response()->json([
            'success' => $dto->status,
            'message' => $dto->message,
            'data' => $dto->data
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/configuration/sub-middleware/{id}",
     *     operationId="getSubMiddlewareById",
     *     tags={"Configuration"},
     *     summary="Get sub_middleware information",
     *     description="Returns sub_middleware data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="sub_middleware not found"
     *     )
     * )
     */

    //Get  role by id
    public function getSubMiddlewareById(Request $request): \Illuminate\Http\JsonResponse
    {
        $dto = $this->_configService->getSubMiddleware($request->id);
       if($dto->status){
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }else{
            return response()->json([
                'success' => $dto->status,
                'message' => $dto->message,
                'data' => $dto->data
            ], $dto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/sub-middleware/update",
     *     operationId="updateSubMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new sub_middleware",
     *     description="update a new sub_middleware",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value",  "middleware_id"},
     *             @OA\Property(property="value", type="string", example="string"),
     *            @OA\Property(property="middleware_id", type="int", example="0"),
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
    public function update_SubMiddleware(Request $request): \Illuminate\Http\JsonResponse
    {
            $update = $this->_configService->updateSubMiddleware($request);
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

      /**
     * @OA\DELETE(
     *     path="/api/configuration/sub-middleware/delete/{id}",
     *     operationId="deleteSubMiddleware",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete sub_middleware",
     *     description="delete sub_middleware",
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
    public function delete_SubMiddleware(Request $request): \Illuminate\Http\JsonResponse
    {
            $delete = $this->_configService->deleteSubMiddleware($request->id);
            if ($delete->status) {
                return response()->json([
                    'success' => true,
                    'message' => $delete->message
                ], $delete->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $delete->message,
                    'error' => $delete->data
                ], $delete->code);


            }

    }

    #endregion sub middleware




   #region country
  /**
     * @OA\Post(
     *     path="/api/configuration/create-country",
     *     operationId="createCountry",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new country",
     *     description="create a new country",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country", "short_code"},
     *             @OA\Property(property="country", type="string", example="string"),
     *             @OA\Property(property="short_code", type="string", example="string")
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
    public function create_country(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->createCountry($request);
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
     * @OA\Get(
     *     path="/api/configuration/countries",
     *     operationId="getCountryList",
     *     tags={"Configuration"},
     *     summary="Get list of countries",
     *     description="Returns list of countries",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllCountries(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_configService->getCountries();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

     /**
     * @OA\Get(
     *     path="/api/configuration/country/{id}",
     *     operationId="getCountryById",
     *     tags={"Configuration"},
     *     summary="Get country information",
     *     description="Returns country data",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *         description="Country not found"
     *     )
     * )
     */

    //Get  role by id
    public function getCountryById(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_configService->getCountry($request->id);
       if($roleDto->status){
            return response()->json([
                'success' => $roleDto->status,
                'message' => $roleDto->message,
                'data' => $roleDto->data
            ], $roleDto->code);
       }else{
            return response()->json([
                'success' => $roleDto->status,
                'message' => $roleDto->message,
                'data' => $roleDto->data
            ], $roleDto->code);
       }

    }

  /**
     * @OA\PUT(
     *     path="/api/configuration/country/update",
     *     operationId="updateCountry",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Update new country",
     *     description="update a new country",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country", "short_code", "country_id"},
     *             @OA\Property(property="country", type="string", example="string"),
     *             @OA\Property(property="short_code", type="string", example="string"),
     *             @OA\Property(property="country_id", type="int", example="0")
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
    public function update_country(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->updateCountry($request);
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
     * @OA\DELETE(
     *     path="/api/configuration/country/delete/{id}",
     *     operationId="deleteCountry",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete country",
     *     description="delete country",
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
    public function delete_country(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->deleteCountry($request->id);
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
    #endregion country


    #region tokens



       /**
     * @OA\Get(
     *     path="/api/configuration/tokens",
     *     operationId="getTokensList",
     *     tags={"Configuration"},
     *     summary="Get list of Tokens",
     *     description="Returns list of Tokens",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllTokens(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_configService->getTokens();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }
        /**
     * @OA\DELETE(
     *     path="/api/configuration/token/delete/{id}",
     *     operationId="deleteToken",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete  Token",
     *     description="delete Token",
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
    public function delete_token(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_configService->deleteToken($request->id);
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
    #endregion tokens


    #region set timezone
    /**
     * @OA\Post(
     *     path="/api/configuration/set-timezone",
     *     operationId="setTimeZone",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Set Time Zone",
     *     description="set Time zone",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"latitude", "longitude"},
     *             @OA\Property(property="latitude", type="string", example="string"),
     *             @OA\Property(property="longitude", type="string", example="string"),
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function set_time_zone(Request $request): \Illuminate\Http\JsonResponse
    {

            $create = $this->_configService->setTimezone($request);

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
    #endregion  timezone

    #region document type configurations
    /**
     * @OA\Post(
     *     path="/api/configuration/create-documentType",
     *     operationId="createDocType",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Create new document type",
     *     description="create a new document type",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Drivers licence"),
     *             @OA\Property(property="description", type="string", example="drivers licence")
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
    public function create_docType(DocumentTypeConfigurationRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $create = $this->_docTypeService->addDocumentType($validated);
        return response()->json([
            'success' => true,
            'message' => $create->message
        ], $create->code);


    }


    /**
     * @OA\Get(
     *     path="/api/configuration/doctypes",
     *     operationId="getAllDocTypes",
     *     tags={"Configuration"},
     *     summary="Get list of document types",
     *     description="Returns list of document types",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllDocTypes(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_docTypeService->getAllDocumentTypes();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\DELETE(
     *     path="/api/configuration/docType/delete/{id}",
     *     operationId="deleteDocType",
     *     tags={"Configuration"},
     *      security={{"sanctum":{}}},
     *     summary="Delete document type",
     *     description="delete document type",
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
    public function delete_docType(Request $request): \Illuminate\Http\JsonResponse
    {
        $create = $this->_docTypeService->removeDocumentType($request->id);
            return response()->json([
                'success' => true,
                'message' => $create->message
            ], $create->code);


    }

}
