<?php

namespace App\Http\Controllers\Navbar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Interfaces\Navbar\INavbarService;
use Illuminate\Support\Facades\Gate;

class NavbarController extends Controller
{
    public INavbarService $_navbarService;

    public function __construct(INavbarService $navbarService)
    {
        $this->_navbarService = $navbarService;
    }

#region navbar type
  /**
     * @OA\Post(
     *     path="/api/navbar/create-navbar-type",
     *     operationId="createNavbarType",
     *     tags={"Navbar"},
     *     security={{"sanctum":{}}},
     *     summary="Create new navbar type",
     *     description="create a new navbar type",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type"},
     *             @OA\Property(property="type", type="string", example="string")
     *
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
    public function create_navbar_type(Request $request): \Illuminate\Http\JsonResponse
    {
           
            $create = $this->_navbarService->createNavType($request);
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
     *     path="/api/navbar/navbar-types",
     *     operationId="getNavbarTypeList",
     *     tags={"Navbar"},
     *     summary="Get list of navbar types",
     *     description="Returns list of navbar types",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllNavbarTypes(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_navbarService->getAllNavType();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }


    /**
     * @OA\Get(
     *     path="/api/navbar/navbar",
     *     operationId="getFullNavbarList",
     *     tags={"Navbar"},
     *     summary="Get list of navbar",
     *     description="Returns list of navbar",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     * )
     */

    //Get All roles
    public function getFullNavs(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_navbarService->getFullNavs();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);

    }

     /**
     * @OA\Get(
     *     path="/api/navbar/navbar_type/{id}",
     *     operationId="getNavbarTypeById",
     *     tags={"Navbar"},
     *     summary="Get navbar type information",
     *     description="Returns navbar type data",
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
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    //Get  navbar type by id
    public function getNavbarTypeById(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_navbarService->getNavTypeById($request->id);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);

    }

  /**
     * @OA\PUT(
     *     path="/api/navbar/navbar_type/update",
     *     operationId="updateNavbarType",
     *     tags={"Navbar"},
     *     security={{"sanctum":{}}},
     *     summary="Update navbar type",
     *     description="update navbar type",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "type"},
     *             @OA\Property(property="id", type="int", example="0"),
     *             @OA\Property(property="type", type="string", example="string")
     *
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     )
     *
     * )
     */
    public function update_navbar_type(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_navbarService->updateNavType($request);
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
     *     path="/api/navbar/navbar_type/delete/{id}",
     *     operationId="deleteNavbarType",
     *     tags={"Navbar"},
     *     security={{"sanctum":{}}},
     *     summary="Delete navbar type",
     *     description="delete navbar type",
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
     *         description="successful operation"
     *
     *     ),
     *
     * )
     */
    public function delete_navbar_type(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_navbarService->deleteNavType($request->id);
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
    #endregion navbar type

#region navmenu

/**
 * @OA\Post(
 *     path="/api/navbar/create-navmenu",
 *     operationId="createNavMenu",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Create new nav menu",
 *     description="Create a new nav menu",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"navbar_id", "menu_text", "menu_link"},
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="menu_text", type="string", example="Home"),
 *             @OA\Property(property="menu_link", type="string", format="url", example="http://example.com/home")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function createNavMenu(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_navbarService->createNavMenu($request);
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
 *     path="/api/navbar/navmenus",
 *     operationId="getNavMenuList",
 *     tags={"Navbar"},
 *     summary="Get list of nav menus",
 *     description="Returns list of nav menus",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No nav menus found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllNavMenu(): \Illuminate\Http\JsonResponse
{
    $menuDto = $this->_navbarService->getAllNavMenu();
    return response()->json([
        'success' => $menuDto->status,
        'message' => $menuDto->message,
        'data' => $menuDto->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/navbar/navmenu/{id}",
 *     operationId="getNavMenuById",
 *     tags={"Navbar"},
 *     summary="Get nav menu information",
 *     description="Returns nav menu data",
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
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nav menu not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getNavMenuById(Request $request): \Illuminate\Http\JsonResponse
{
    $menuDto = $this->_navbarService->getNavMenuById($request->id);
    if ($menuDto->status) {
        return response()->json([
            'success' => $menuDto->status,
            'message' => $menuDto->message,
            'data' => $menuDto->data
        ], $menuDto->code);
    } else {
        return response()->json([
            'success' => $menuDto->status,
            'message' => $menuDto->message,
            'data' => $menuDto->data
        ], $menuDto->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/navbar/navmenu/update",
 *     operationId="updateNavMenu",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Update nav menu",
 *     description="Update nav menu",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "navbar_id", "menu_text", "menu_link"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="menu_text", type="string", example="Home"),
 *             @OA\Property(property="menu_link", type="string", format="url", example="http://example.com/home")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nav menu not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function updateNavMenu(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_navbarService->updateNavMenu($request);
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
 * @OA\Delete(
 *     path="/api/navbar/navmenu/delete/{id}",
 *     operationId="deleteNavMenu",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Delete nav menu",
 *     description="Delete nav menu",
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
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nav menu not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteNavMenu(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_navbarService->deleteNavMenu($request->id);
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

#endregion navmenu

 #region navbartext

/**
 * @OA\Post(
 *     path="/api/navbar/create-navtext",
 *     operationId="createNavText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Create new nav text",
 *     description="Create a new nav text",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"navbar_id", "text", "description"},
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="text", type="string", example="Welcome to our website"),
 *             @OA\Property(property="description", type="string", example="Welcome to our website")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Navbar Text created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function createNavText(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_navbarService->createNavText($request);
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
 *     path="/api/navbar/navtexts",
 *     operationId="getAllNavText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get list of nav texts",
 *     description="Returns list of nav texts",
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No nav texts found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllNavText(): \Illuminate\Http\JsonResponse
{
    $texts = $this->_navbarService->getAllNavText();
    return response()->json([
        'success' => $texts->status,
        'message' => $texts->message,
        'data' => $texts->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/navbar/navtext/{id}",
 *     operationId="getNavTextById",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get nav text by ID",
 *     description="Returns nav text data",
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
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar text not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getNavTextById(Request $request): \Illuminate\Http\JsonResponse
{
    $text = $this->_navbarService->getNavTextById($request->id);
    if ($text->status) {
        return response()->json([
            'success' => $text->status,
            'message' => $text->message,
            'data' => $text->data
        ], $text->code);
    } else {
        return response()->json([
            'success' => $text->status,
            'message' => $text->message,
            'data' => $text->data
        ], $text->code);
    }
}
/**
 * @OA\Get(
 *     path="/api/navbar/banner_text",
 *     operationId="getBannerText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get nav banner text",
 *     description="Returns banner text data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar text not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getBannerText(Request $request): \Illuminate\Http\JsonResponse
{
    $text = $this->_navbarService->getBannerText();
    if ($text->status) {
        return response()->json([
            'success' => $text->status,
            'message' => $text->message,
            'data' => $text->data
        ], $text->code);
    } else {
        return response()->json([
            'success' => $text->status,
            'message' => $text->message,
            'data' => $text->data
        ], $text->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/navbar/navtext/update",
 *     operationId="updateNavText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Update nav text",
 *     description="Update nav text",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"navbar_id", "navbar_text_id", "text", "description"},
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="navbar_text_id", type="integer", example=1),
 *             @OA\Property(property="text", type="string", example="Updated welcome message"),
 *             @OA\Property(property="description", type="string", example="Updated welcome message"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Navbar Text updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar text not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function updateNavText(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_navbarService->updateNavText($request);
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
 * @OA\Delete(
 *     path="/api/navbar/navtext/delete/{id}",
 *     operationId="deleteNavText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Delete nav text",
 *     description="Delete nav text",
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
 *         description="Navbar Text deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar text not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteNavText(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_navbarService->deleteNavText($request->id);
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
/**
 * @OA\Put(
 *     path="/api/navbar/navtext/activate/{id}",
 *     operationId="activateNavText",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Activate nav text",
 *     description="activate nav text",
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
 *         description="Navbar Text activated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar text not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function activateNavText(Request $request): \Illuminate\Http\JsonResponse
{
    $status = $this->_navbarService->activateNavText($request->id);
    if ($status->status) {
        return response()->json([
            'success' => true,
            'message' => $status->message
        ], $status->code);
    } else {
        return response()->json([
            'success' => false,
            'message' => $status->message,
            'error' => $status->data
        ], $status->code);
    }
}

#endregion navbartext

#region navbarbutton

/**
 * @OA\Post(
 *     path="/api/navbar/create-navbutton",
 *     operationId="createNavButton",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Create new nav button",
 *     description="Create a new nav button",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"navbar_id", "button_text", "button_link"},
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="button_text", type="string", example="Click Me"),
 *             @OA\Property(property="button_link", type="string", format="url", example="https://example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Navbar Buttons created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function createNavButton(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_navbarService->createNavButton($request);
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
 *     path="/api/navbar/navbuttons",
 *     operationId="getAllNavButton",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get list of nav buttons",
 *     description="Returns list of nav buttons",
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar Buttons not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllNavButton(): \Illuminate\Http\JsonResponse
{
    $buttons = $this->_navbarService->getAllNavButton();
    return response()->json([
        'success' => $buttons->status,
        'message' => $buttons->message,
        'data' => $buttons->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/navbar/navbutton/{id}",
 *     operationId="getNavButtonById",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get nav button by ID",
 *     description="Returns nav button data",
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
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar Button not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getNavButtonById(Request $request): \Illuminate\Http\JsonResponse
{
    $button = $this->_navbarService->getNavButtonById($request->id);
    if ($button->status) {
        return response()->json([
            'success' => $button->status,
            'message' => $button->message,
            'data' => $button->data
        ], $button->code);
    } else {
        return response()->json([
            'success' => $button->status,
            'message' => $button->message,
            'data' => $button->data
        ], $button->code);
    }
}

/**
 * @OA\Put(
 *     path="/api/navbar/navbutton/update",
 *     operationId="updateNavButton",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Update nav button",
 *     description="Update nav button",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "navbar_id", "button_text", "button_link"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="button_text", type="string", example="Click Me"),
 *             @OA\Property(property="button_link", type="string", format="url", example="https://example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Navbar Button updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar Button not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function updateNavButton(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_navbarService->updateNavButton($request);
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
 * @OA\Delete(
 *     path="/api/navbar/navbutton/delete/{id}",
 *     operationId="deleteNavButton",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Delete nav button",
 *     description="Delete nav button",
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
 *         description="Navbar Button deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar Button not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteNavButton(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_navbarService->deleteNavButton($request->id);
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

#endregion navbarbutton

#region navbarlogo

/**
 * @OA\Post(
 *     path="/api/navbar/upsert-logo",
 *     operationId="upsertLogo",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Create or update nav logo",
 *     description="Create or update a nav logo, for creating set id = 0",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"navbar_id", "logo_url"},
 *             @OA\Property(property="navbar_id", type="integer", example=1),
 *             @OA\Property(property="logo_url", type="string", example="https://example.com/logo.png")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Navbar logo created/updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function upsertLogo(Request $request): \Illuminate\Http\JsonResponse
{
    $createOrUpdate = $this->_navbarService->upsertLogo($request);
    if ($createOrUpdate->status) {
        return response()->json([
            'success' => true,
            'message' => $createOrUpdate->message
        ], $createOrUpdate->code);
    } else {
        return response()->json([
            'success' => false,
            'message' => $createOrUpdate->message,
            'error' => $createOrUpdate->data
        ], $createOrUpdate->code);
    }
}

/**
 * @OA\Get(
 *     path="/api/navbar/get-logo",
 *     operationId="getNavLogo",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Get nav logo",
 *     description="Returns nav logo data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar logo not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getNavLogo(Request $request): \Illuminate\Http\JsonResponse
{
    $logo = $this->_navbarService->getNavLogo();
    if ($logo->status) {
        return response()->json([
            'success' => $logo->status,
            'message' => $logo->message,
            'data' => $logo->data
        ], $logo->code);
    } else {
        return response()->json([
            'success' => $logo->status,
            'message' => $logo->message,
            'data' => $logo->data
        ], $logo->code);
    }
}

/**
 * @OA\Delete(
 *     path="/api/navbar/logo/delete/{id}",
 *     operationId="deleteNavLogo",
 *     tags={"Navbar"},
 *     security={{"sanctum":{}}},
 *     summary="Delete nav logo",
 *     description="Delete nav logo",
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
 *         description="Navbar logo deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Navbar logo not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteNavLogo(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_navbarService->deleteNavLogo($request->id);
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

#endregion navbarlogo

}
