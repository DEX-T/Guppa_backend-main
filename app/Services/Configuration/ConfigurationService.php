<?php

namespace App\Services\Configuration;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Prefix;
use GuzzleHttp\Client;
use App\Models\Ability;
use App\Models\Country;
use App\Models\GuppaRoute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\SubMiddleware;
use App\Helpers\GeneralHelper;
use Illuminate\Support\Carbon;
use App\Models\GuppaController;
use App\Models\GeneralMiddleware;
use Illuminate\Support\Facades\DB;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\RoleEntity;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Domain\Entities\PrefixEntity;
use App\Domain\Entities\AbilityEntity;
use App\Domain\Entities\CountryEntity;
use App\Domain\Entities\ControllerEntity;
use App\Domain\Entities\GuppaRouteEntity;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\SubMiddlewareEntity;
use App\Domain\DTOs\Request\Role\RoleRequestDto;
use App\Domain\Entities\GeneralMiddlewareEntity;
use App\Domain\DTOs\Response\Role\RoleResponseDto;
use App\Domain\DTOs\Request\Prefix\PrefixRequestDto;
use App\Domain\DTOs\Request\Country\CountryRequestDto;
use App\Domain\DTOs\Response\Prefix\PrefixResponseDto;
use App\Domain\DTOs\Request\TimeZone\TimeZoneRequestDto;
use App\Domain\DTOs\Response\Country\CountryResponseDto;
use App\Domain\DTOs\Request\Configuration\AbilityRequestDto;
use App\Domain\DTOs\Response\Tokens\AccessTokensResponseDto;
use App\Domain\DTOs\Request\GuppaRoutes\GuppaRouteRequestDto;
use App\Domain\DTOs\Response\Configuration\AbilityResponseDto;
use App\Domain\Interfaces\Configuration\IConfigurationService;
use App\Domain\DTOs\Response\GuppaRoutes\GuppaRouteResponseDto;
use App\Domain\DTOs\Request\GuppaControllers\ControllerRequestDto;
use App\Domain\DTOs\Request\SubMiddlewares\SubMiddlewareRequestDto;
use App\Domain\DTOs\Response\GuppaControllers\ControllerResponseDto;
use App\Domain\DTOs\Response\SubMiddlewares\SubMiddlewareResponseDto;
use App\Domain\DTOs\Request\GeneralMiddleware\GeneralMiddlewareRequestDto;
use App\Domain\DTOs\Response\GeneralMiddleware\GeneralMiddlewareResponseDto;

class ConfigurationService implements IConfigurationService
{
    protected $_currentUser;
    public function __construct() {
       $this->_currentUser =  Auth::user();
      
    }
#region roles
    public function getRoles()
    {
        try {
          
            $roles = Role::all();

            $dto = $roles->map(function($roles){
                $roleEntity = new RoleEntity($roles);
                return new RoleResponseDto($roleEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getRole(int $id)
    {
        try {
            $role = Role::findOrFail($id);
            if($role != null){
                $roleEntity = new RoleEntity($role);
                $dto = new RoleResponseDto($roleEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'role' => ['required', 'unique:'.Role::class]
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new RoleRequestDto($validate['role']);
                $role = new Role();
                $role->role = Str::upper($dto->getRole());
                $role->status = "active";

            if($role->save()){
                return new ApiResponseDto(true, "Role added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating role", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'role' => ['required'],
                    'role_id' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new RoleRequestDto($validate['role'], $validate['role_id']);

                $role =  Role::find($dto->getRoleId());
                if($role == null){
                    return new ApiResponseDto(false, 'Role does not exist', HttpStatusCode::NOT_FOUND);
                }
                $role->role = Str::upper($dto->getRole());
            if($role->save()){
                return new ApiResponseDto(true, "Role updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating role", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteRole(int $id)
    {
        try {
            $role = Role::find($id);
            if($role){
                if($role->delete()){
                    return new ApiResponseDto(true, "Role deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting role", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Role not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getByRoleId(int $roleId)
    {
        return Ability::where(['role_id' => $roleId, 'status' => 'active'])->pluck('ability')->all();
    }
  #endregion roles

#region abilities
    public function getAbilities()
    {
        try {
            $abilities = Ability::get();
            $dto = $abilities->map(function($ability) {
                $abilityEntity = new AbilityEntity($ability);
                return new AbilityResponseDto($abilityEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getAbility(int $id)
    {
        try {
            $ability =  Ability::findOrFail($id);
            if($ability != null){
                $abilityEntity = new AbilityEntity($ability);
                $dto = new AbilityResponseDto($abilityEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createAbility(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'role_id' => ['required', 'int', 'exists:roles,id'],
                    'ability' => ['required', 'string']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new AbilityRequestDto($validate['ability'], $validate['role_id']);
                $abilityExist = Ability::where(['role_id' => $dto->getRoleId(), 'ability' => $dto->getAbility()])->first();
                if($abilityExist != null){
                    return new ApiResponseDto(false, 'Ability already exist for that role', HttpStatusCode::CONFLICT);
                }

                $ability = new Ability();
                $ability->role_id = $dto->getRoleId();
                $ability->ability = $dto->getAbility();
                $ability->status = "active";
            if($ability->save()){
                return new ApiResponseDto(true, "Ability added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Ability", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateAbility(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'role_id' => ['required'],
                    'ability' => ['required'],
                    'ability_id' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new AbilityRequestDto($validate['ability'], $validate['role_id'], $validate['ability_id']);
                $abilityExist = Ability::where(['id' => $dto->getAbilityId()])->first();
                if($abilityExist == null){
                    return new ApiResponseDto(false, 'Ability does not exist', HttpStatusCode::NOT_FOUND);
                }
                $abilityExist->role_id = $dto->getRoleId();
                $abilityExist->ability = $dto->getAbility();
                $abilityExist->updated_At = Carbon::now();
            if($abilityExist->save()){
                return new ApiResponseDto(true, "Ability updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Ability", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteAbility(int $id)
    {
        try {
            $ability = Ability::find($id);
            if($ability){
                if($ability->delete()){
                    return new ApiResponseDto(true, "Ability deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting role", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Ability not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    #endregion abilities

#region prefix
    public function getPrefixes()
    {
        try {
            $prefixes = Prefix::get();
            $dto = $prefixes->map(function($prefix) {
                $prefixEntity = new PrefixEntity($prefix);
                return new PrefixResponseDto($prefixEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getPrefix(int $id)
    {
        try {
            $prefix =  Prefix::findOrFail($id);
            if($prefix != null){
                $prefixEntity = new PrefixEntity($prefix);
                $dto = new PrefixResponseDto($prefixEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createPrefix(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'prefix' => ['required', 'unique:'.Prefix::class],
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new PrefixRequestDto($validate['prefix']);
               
                $Prefix = new Prefix();
                $Prefix->prefix = $dto->prefix;
                $Prefix->status = "active";
            if($Prefix->save()){
                return new ApiResponseDto(true, "Prefix added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Prefix", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updatePrefix(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'prefix' => ['required'],
                    'prefix_id' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new PrefixRequestDto($validate['prefix'], $validate['prefix_id']);
                $prefixExist = Prefix::where(['id' => $dto->prefix_id])->first();
                if($prefixExist == null){
                    return new ApiResponseDto(false, 'Prefix does not exist', HttpStatusCode::NOT_FOUND);
                }
                $prefixExist->prefix = $dto->prefix;
            if($prefixExist->save()){
                return new ApiResponseDto(true, "Prefix updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Prefix", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deletePrefix(int $id)
    {
        try {
            $Prefix = Prefix::find($id);
            if($Prefix){
                if($Prefix->delete()){
                    return new ApiResponseDto(true, "Prefix deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting role", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Prefix not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


    #endregion prefix
    
#region general middleware
     public function getGeneralMiddlewares()
     {
         try {
             $middlewares = GeneralMiddleware::get();
             $dto = $middlewares->map(function($middleware) {
                 $middlewareEntity = new GeneralMiddlewareEntity($middleware);
                 return new GeneralMiddlewareResponseDto($middlewareEntity);
             });
             return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
         }catch (\Exception $e){
             return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
 
         }
     }
 
     public function getGeneralMiddleware(int $id)
     {
         try {
             $middleware =  GeneralMiddleware::findOrFail($id);
             if($middleware != null){
                 $middlewareEntity = new GeneralMiddlewareEntity($middleware);
                 $dto = new GeneralMiddlewareResponseDto($middlewareEntity);
                 return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
             }else{
                 return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);
 
             }
 
         }catch (\Exception $e){
             return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
 
         }
     }
 
     public function createGeneralMiddleware(Request $request)
     {
         try {
             $validator = Validator::make($request->all(), [
                     'key' => ['required'],
                     'value' => ['required'],
                     'prefix_id' => ['required'],
             ]);
             if($validator->fails()){
                 return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
             }

                 $validate = $validator->validated();
                 $dto = new GeneralMiddlewareRequestDto($validate['key'], $validate['value'], $validate['prefix_id']);
                // check if prefix eixst
                $prefix = Prefix::find($validate['prefix_id']);
                if($prefix == null){
                    return new ApiResponseDto(false, 'Prefix Not Found', HttpStatusCode::NOT_FOUND);
                }

                 $middleware = new GeneralMiddleware();
                 $middleware->key = $dto->key;
                 $middleware->value = $dto->value;
                 $middleware->prefix_id = $dto->prefix_id;
                 $middleware->status = "active";
             if($middleware->save()){
                 return new ApiResponseDto(true, "Middleware added successfully", HttpStatusCode::OK);
             }else{
                 return new ApiResponseDto(false, "Error creating Middleware", HttpStatusCode::BAD_REQUEST);
             }
 
         } catch (\Exception $e) {
             return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
 
         }
     }
 
     public function updateGeneralMiddleware(Request $request)
     {
        try {
            $validator = Validator::make($request->all(), [
                    'key' => ['required'],
                    'value' => ['required'],
                    'prefix_id' => ['required'],
                    'middleware_id' => ['required', 'int']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new GeneralMiddlewareRequestDto($validate['key'], $validate['value'], $validate['prefix_id'], $validate['middleware_id']);
                $middleware = GeneralMiddleware::where(['id' => $dto->middleware_id])->first();
                if($middleware == null){
                    return new ApiResponseDto(false, 'Middleware does not exist', HttpStatusCode::NOT_FOUND);
                }
                $middleware->key = $dto->key;
                $middleware->value = $dto->value;
                $middleware->prefix_id = $dto->prefix_id;
            if($middleware->save()){
                return new ApiResponseDto(true, "Middleware updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Middleware", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
     }
 
     public function deleteGeneralMiddleware(int $id)
     {
         try {
             $GeneralMiddleware = GeneralMiddleware::find($id);
             if($GeneralMiddleware){
                 if($GeneralMiddleware->delete()){
                     return new ApiResponseDto(true, "Middleware deleted successfully", HttpStatusCode::OK);
                 }else{
                     return new ApiResponseDto(false, "Error deleting middleware", HttpStatusCode::BAD_REQUEST);
                 }
             }else{
                 return new ApiResponseDto(false, "Middleware not found", HttpStatusCode::NOT_FOUND);
             }
 
         }catch(Exception $e){
             return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
 
         }
     }
 
 
     #endregion general middle

#region controllers
    public function getControllers()
    {
        try {
            $controllers = GuppaController::get();
            $dto = $controllers->map(function($controller) {
                $controllerEntity = new ControllerEntity($controller);
                return new ControllerResponseDto($controllerEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getController(int $id)
    {
        try {
            $controller =  GuppaController::with('guppa_routes')->where('id', $id)->first();
            if($controller != null){
                $controllerEntity = new ControllerEntity($controller);
                $dto = new ControllerResponseDto($controllerEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);
            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createController(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'prefix_id' => ['required'],
                    'middleware_id' => ['required'],
                    'controller' => ['required'],
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new ControllerRequestDto($validate['prefix_id'], $validate['middleware_id'], $validate['controller']);
                // check if prefix exist
                $prefix = Prefix::find($validate['prefix_id']);
                $middleware_id = GeneralMiddleware::find($validate['middleware_id']);
                
                if($prefix == null || $middleware_id == null){
                    return new ApiResponseDto(false, 'Prefix or General Middleware Not Found', HttpStatusCode::NOT_FOUND);
                }
               
                $word = "Controller";
                if(!GeneralHelper::containsWord($dto->controller, $word)){
                    return new ApiResponseDto(false, "Controller keyword is required for the controller name eg: TestController", HttpStatusCode::BAD_REQUEST);
                }
                //capitalize the word controller incase user did not do that
                $newWord = GeneralHelper::capitalize($dto->controller);
                $control =  $newWord."::class";
                //check if this new word existing in the controllers table
                $exist = GuppaController::where('controller', $control)->first();
                if($exist != null){
                    return new ApiResponseDto(false, "Controller already exist in the db", HttpStatusCode::CONFLICT);
                }

                $controller = new GuppaController();
                $controller->prefix_id = $dto->prefix_id;
                $controller->general_middleware_id = $dto->general_middleware_id;
                $controller->controller =  $newWord."::class";
                $controller->status = "active";
            if($controller->save()){
                return new ApiResponseDto(true, "Controller added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Controller", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateController(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'prefix_id' => ['required'],
                    'middleware_id' => ['required'],
                    'controller' => ['required'],
                    'controller_id' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new ControllerRequestDto($validate['prefix_id'], $validate['middleware_id'], $validate['controller'], $validate['controller_id']);
                // check if prefix exist
                $prefix = Prefix::find($validate['prefix_id']);
                $middleware_id = GeneralMiddleware::find($validate['middleware_id']);
                
                if($prefix == null || $middleware_id == null){
                    return new ApiResponseDto(false, 'Prefix or General Middleware Not Found', HttpStatusCode::NOT_FOUND);
                }

                $controller =  GuppaController::find($validate['controller_id']);

                if($controller == null){
                    return new ApiResponseDto(false, 'Controller Not Found', HttpStatusCode::NOT_FOUND);
                }
                $controller->prefix_id = $dto->prefix_id;
                $controller->general_middleware_id = $dto->general_middleware_id;
                $controller->controller = $dto->controller;

            if($controller->save()){
                return new ApiResponseDto(true, "Controller updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Controller", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteController(int $id)
    {
        try {
            $controller = GuppaController::find($id);
            if($controller){
                if($controller->delete()){
                    return new ApiResponseDto(true, "Controller deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting controller", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Controller not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
        
#endregion controller

#region routes
    public function getRoutes()
    {
        try {
            $routes = GuppaRoute::get();
            $dto = $routes->map(function($route) {
                $routeEntity = new GuppaRouteEntity($route);
                return new GuppaRouteResponseDto($routeEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getRoute(int $id)
    {
        try {
            $route =  GuppaRoute::findOrFail($id);
            if($route != null){
                $routeEntity = new GuppaRouteEntity($route);
                $dto = new GuppaRouteResponseDto($routeEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createRoute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'controller_id' => ['required'],
                    'method' => ['required'],
                    'action' => ['required'],
                    'url' => ['required', 'unique:'.GuppaRoute::class],
                    'name' => ['string']
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new GuppaRouteRequestDto($validate['controller_id'], $validate['method'], $validate['action'], $validate['url'], $validate['name']);
                // check if prefix exist
                $controller = GuppaController::find($validate['controller_id']);
                 
                if($controller == null){
                    return new ApiResponseDto(false, 'Controller Not Found', HttpStatusCode::NOT_FOUND);
                }

                $route = new GuppaRoute();
                $route->guppa_controller_id = $dto->controller_id;
                $route->method = $dto->method;
                $route->action = $dto->action;
                $route->url = $dto->url;
                $route->name = $dto->name;
                $route->status = "active";
            if($route->save()){
                return new ApiResponseDto(true, "Route added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Route", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateRoute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'controller_id' => ['required'],
                    'method' => ['required'],
                    'action' => ['required'],
                    'url' => ['required', 'unique:guppa_routes,url,'.$request->route_id],
                    'name' => ['string'],
                    'route_id' => ['required']
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new GuppaRouteRequestDto($validate['controller_id'], $validate['method'], $validate['action'], $validate['url'], $validate['name']);
                // check if prefix exist
                $controller = GuppaController::find($validate['controller_id']);
                 
                if($controller == null){
                    return new ApiResponseDto(false, 'Controller Not Found', HttpStatusCode::NOT_FOUND);
                }
                $route = GuppaRoute::find($validate['route_id']);
                 
                if($route == null){
                    return new ApiResponseDto(false, 'Route Not Found', HttpStatusCode::NOT_FOUND);
                }

                $route->guppa_controller_id = $dto->controller_id;
                $route->method = $dto->method;
                $route->action = $dto->action;
                $route->url = $dto->url;
                $route->name = $dto->name;
            if($route->save()){
                return new ApiResponseDto(true, "Route  updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Route", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteRoute(int $id)
    {
        try {
            $route = GuppaRoute::find($id);
            if($route){
                if($route->delete()){
                    return new ApiResponseDto(true, "Route deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting route", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "route not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
        
#endregion routes
   
#region sub middleware
    public function getSubMiddlewares()
    {
        try {
            $middlewares = SubMiddleware::get();
            $dto = $middlewares->map(function($middleware) {
                $middlewareEntity = new SubMiddlewareEntity($middleware);
                return new SubMiddlewareResponseDto($middlewareEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function getSubMiddleware(int $id)
    {
        try {
            $middleware =  SubMiddleware::findOrFail($id);
            if($middleware != null){
                $middlewareEntity = new SubMiddlewareEntity($middleware);
                $dto = new SubMiddlewareResponseDto($middlewareEntity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createSubMiddleware(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'value' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new SubMiddlewareRequestDto( $validate['value']);

                $middleware = new SubMiddleware();
                $middleware->key = "route";
                $middleware->value = $dto->value;
                $middleware->status = "active";
            if($middleware->save()){
                return new ApiResponseDto(true, "Middleware added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Middleware", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function updateSubMiddleware(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'value' => ['required'],
                    'middleware_id' => ['required', 'int']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                $validate = $validator->validated();
                $dto = new SubMiddlewareRequestDto($validate['value'],  $validate['middleware_id']);
                $middleware = SubMiddleware::where(['id' => $dto->middleware_id])->first();
                if($middleware == null){
                    return new ApiResponseDto(false, 'Middleware does not exist', HttpStatusCode::NOT_FOUND);
                }
                $middleware->value = $dto->value;
            if($middleware->save()){
                return new ApiResponseDto(true, "Middleware updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Middleware", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteSubMiddleware(int $id)
    {
        try {
            $GeneralMiddleware = SubMiddleware::find($id);
            if($GeneralMiddleware){
                if($GeneralMiddleware->delete()){
                    return new ApiResponseDto(true, "Middleware deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting middleware", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Middleware not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
        
#endregion sub middleware

#region Country
    public function getCountries()
    {
        try {
            $countries = Country::query()->orderBy('country')->get();
            $dto = $countries->map(function($country) {
                $countryEntity = new CountryEntity($country);
                return new CountryResponseDto($countryEntity);
            });
            return new ApiResponseDto(true, "Countries fetched successfully", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountry(int $id)
    {
        try {
            $country = Country::findOrFail($id);
            if($country == null){
                return new ApiResponseDto(false, "Country not found", HttpStatusCode::NOT_FOUND);
            }
            $countryEntity = new CountryEntity($country);
            $dto = new CountryResponseDto($countryEntity);
            return new ApiResponseDto(true, "Countries fetched successfully", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function createCountry(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country' => 'required|string|unique:'.Country::class,
                'short_code' => 'required|string|unique:'.Country::class,
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validate = $validator->validated();
            $dto = new CountryRequestDto($validate['country'], $validate['short_code']);
            $country = new Country();
            $country->country = $dto->country;
            $country->short_code = $dto->short_code;
            $country->status = "active";
            if($country->save()){
                return new ApiResponseDto(true, "Country created successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Failed to create country", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            //return server error
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCountry(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country' => 'required|string|unique:'.Country::class,
                'short_code' => 'required|string|unique:'.Country::class,
                'country_id' => 'required|int'
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validate = $validator->validated();
            $country = Country::find($validate['country_id']);
            if($country){
                $dto = new CountryRequestDto($validate['country'], $validate['short_code']);
                $country->country = $dto->country;
                $country->short_code = $dto->short_code;
                if($country->save()){
                    return new ApiResponseDto(true, "Country updated successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Failed to update country", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Country not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            //return server error
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCountry(int $id)
    {
       try {
         //find country
         $country = Country::findOrFail($id);
         if($country){
             if($country->delete()){
                 return new ApiResponseDto(true, "Country deleted successfully", HttpStatusCode::OK);
             }else{
                 return new ApiResponseDto(false, "Failed to delete country", HttpStatusCode::BAD_REQUEST);
             }
         }else{
             return new ApiResponseDto(false, "Country not found", HttpStatusCode::NOT_FOUND);
         }
       } catch (\Exception $e) {
            // return server error
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

       }
    }

   #endregion country

    public function setTimezone(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $dto = new TimeZoneRequestDto($latitude, $longitude);
        // Call an external API to get the timezone
        $client = new Client();
        $response = $client->get('https://maps.googleapis.com/maps/api/timezone/json', [
            'query' => [
                'location' => $dto->latitude . ',' . $dto->longitude,
                'timestamp' => time(),
                'key' => 'YOUR_GOOGLE_API_KEY'
            ]
        ]);

        $timezoneData = json_decode($response->getBody(), true);
        $timezone = $timezoneData['timeZoneId'];

        // Set the application timezone
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        return response()->json(['timezone' => $timezone]);
    }

#region tokens
    public function getTokens()
    {
       try {
        $tokens = PersonalAccessToken::all();
       $dto = $tokens->map(function($token){
            return new AccessTokensResponseDto($token);
       });
       return new ApiResponseDto(true, "Tokens fetched successfully", HttpStatusCode::OK, $dto->toArray());
       } catch (\Exception $e) {
         //return server
         return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }

    

    public function deleteToken(int $id)
    {
        try {
            $token = DB::table("personal_access_tokens")->where('id', $id)->first();
           //delete if existing
           if($token){
            //delete
             $token->delete();
             return new ApiResponseDto(false, 'Token deleted', HttpStatusCode::OK);
           }else{
            //return not found
            return new ApiResponseDto(false, 'Token not found', HttpStatusCode::NOT_FOUND);
           }

           } catch (\Exception $e) {
             //return server
             return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
           }
    }

 #endregion tokens
}
