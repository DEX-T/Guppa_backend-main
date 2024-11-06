<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Domain\Interfaces\Configuration\IFooterService;

class FooterController extends Controller
{
    private IFooterService $footerService;
    protected $_currentUser;
    public function __construct(IFooterService $footerService) {
        $this->footerService = $footerService;
         $this->_currentUser = Auth::user();
    }

    /**
     * @OA\Get(
     *     path="/api/footer/getFooters",
     *     tags={"Footer"},
     *     summary="Get all Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getFooters()
    {
        $dto = $this->footerService->getFooters();

        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/footer/getFooter/{id}",
     *     tags={"Footer"},
     *     summary="Get Footer Social Media by ID",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getFooter($id)
    {
        $dto = $this->footerService->getFooter($id);
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/footer/createFooter",
     *     tags={"Footer"},
     *     summary="Create Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"icon", "url", "footer_id"},
     *             @OA\Property(property="icon", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="url"),
     *             @OA\Property(property="footer_id", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Socials added successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Socials"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function createFooter(Request $request)
    {
        
        $footerSocialMedia = $this->footerService->createFooter($request);

        if ($footerSocialMedia->status) {
            return response()->json([
                'success' => true,
                'message' => $footerSocialMedia->message,
                'data' => $footerSocialMedia->data,
            ], $footerSocialMedia->code);
        } else {
            return response()->json([
                'success' => false,
                'message' => $footerSocialMedia->message,
            ], $footerSocialMedia->code);
        }

    }

    /**
     * @OA\Put(
     *     path="/api/footer/updateFooter/{id}",
     *     tags={"Footer"},
     *     summary="Update Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"icon", "url"},
     *             @OA\Property(property="icon", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="url")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media does not exist"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function updateFooter(Request $request, $id)
    {
        $update = $this->footerService->updateFooter($request, $id);
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
     *     path="/api/footer/deleteFooter/{id}",
     *     tags={"Footer"},
     *     summary="Delete Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteFooter($id)
    {
        $delete = $this->footerService->deleteFooter($id);
        return response()->json([
            'success' => true,
            'message' => $delete->message,
        ], $delete->code);

    }


    /**
     * @OA\Get(
     *     path="/api/footer/getAllFooterSocialMedia",
     *     tags={"Footer"},
     *     summary="Get all Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllFooterSocialMedia()
    {
        $dto = $this->footerService->getAllFooterSocialMedia();

        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/footer/getFooterSocialMedia/{id}",
     *     tags={"Footer"},
     *     summary="Get Footer Social Media by ID",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getFooterSocialMedia($id)
    {
        $dto = $this->footerService->getFooterSocialMedia($id);
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/footer/footer-with-socials",
     *     tags={"Footer"},
     *     summary="Get Footer Social Media for FE",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getFooterSocialMediaFE()
    {
        $dto = $this->footerService->getFooterSocialMediaFE();
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/footer/createFooterSocialMedia",
     *     tags={"Footer"},
     *     summary="Create Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"icon", "url", "footer_id"},
     *             @OA\Property(property="icon", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="url"),
     *             @OA\Property(property="footer_id", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Socials added successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Socials"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function createFooterSocialMedia(Request $request)
    {

            $footerSocialMedia = $this->footerService->createFooterSocialMedia($request);

            if ($footerSocialMedia->status) {
                return response()->json([
                    'success' => true,
                    'message' => $footerSocialMedia->message,
                    'data' => $footerSocialMedia->data,
                ], $footerSocialMedia->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $footerSocialMedia->message,
                ], $footerSocialMedia->code);
            }

    }

    /**
     * @OA\Put(
     *     path="/api/footer/updateFooterSocialMedia/{id}",
     *     tags={"Footer"},
     *     summary="Update Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"icon", "url"},
     *             @OA\Property(property="icon", type="string", example="string"),
     *             @OA\Property(property="url", type="string", example="url")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media does not exist"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function updateFooterSocialMedia(Request $request, $id)
    {
        $update = $this->footerService->updateFooterSocialMedia($request, $id);
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
     *     path="/api/footer/deleteFooterSocialMedia/{id}",
     *     tags={"Footer"},
     *     summary="Delete Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteFooterSocialMedia($id)
    {
        $delete = $this->footerService->deleteFooterSocialMedia($id);
        return response()->json([
            'success' => $delete->status,
            'message' => $delete->message,
        ], $delete->code);

    }

    /**
     * @OA\PUT(
     *     path="/api/footer/activateFooterSocialMedia/{id}",
     *     tags={"Footer"},
     *     summary="Activate Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media activate successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function activateFooterSocialMedia($id)
    {
        $activate = $this->footerService->activateFooterSocialMedia($id);
        return response()->json([
            'success' => $activate->status,
            'message' => $activate->message,
        ], $activate->code);

    }



    /**
     * @OA\PUT(
     *     path="/api/footer/deactivateFooterSocialMedia/{id}",
     *     tags={"Footer"},
     *     summary="Deactivate Footer Social Media",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social Media deactivate successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deactivateFooterSocialMedia($id)
    {
        $deactivate = $this->footerService->deactivateFooterSocialMedia($id);
        return response()->json([
            'success' => $deactivate->status,
            'message' => $deactivate->message,
        ], $deactivate->code);

    }



    /**
     * @OA\Get(
     *     path="/api/footer/getAllFooterCopyrights",
     *     tags={"Footer"},
     *     summary="Get all Footer Copyrights",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllFooterCopyrights()
    {
        $dto = $this->footerService->getAllFooterCopyrights();

        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/footer/getFooterCopyright/{id}",
     *     tags={"Footer"},
     *     summary="Get Footer Copyright by ID",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getFooterCopyright($id)
    {
        $dto = $this->footerService->getFooterCopyright($id);
        return response()->json([
            'success' =>  $dto->status,
            'message' =>  $dto->message,
            'data' =>     $dto->data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/footer/createFooterCopyright",
     *     tags={"Footer"},
     *     summary="Create Footer Copyright",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text", "footer_id"},
     *             @OA\Property(property="text", type="string", example="string"),
     *             @OA\Property(property="footer_id", type="integer", example=0 )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Footer Copyright added successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating Footer Copyright"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function createFooterCopyright(Request $request)
    {
        try {
            $footerCopyright = $this->footerService->createFooterCopyright($request);

            if ($footerCopyright->status) {
                return response()->json([
                    'success' => true,
                    'message' => $footerCopyright->message,
                    'data' => $footerCopyright->data,
                ], $footerCopyright->code);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $footerCopyright->message,
                ], $footerCopyright->code);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/footer/updateFooterCopyright/{id}",
     *     tags={"Footer"},
     *     summary="Update Footer Copyright",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text"},
     *             @OA\Property(property="text", type="string", example="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Footer Copyright updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Footer Copyright does not exist"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function updateFooterCopyright(Request $request, $id)
    {
        $update = $this->footerService->updateFooterCopyright($request, $id);
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
     *     path="/api/footer/deleteFooterCopyright/{id}",
     *     tags={"Footer"},
     *     summary="Delete Footer Copyright",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Footer Copyright deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Footer Copyright not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteFooterCopyright($id)
    {
        $delete = $this->footerService->deleteFooterCopyright($id);
        if ($delete != null) {
            return response()->json([
                'success' => true,
                'message' => $delete->message,
            ], $delete->code);
        }
    }
}
