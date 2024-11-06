<?php

namespace App\Http\Controllers\BidPaymentConfig;

use App\Domain\Interfaces\BidPaymentConfig\IBidPaymentConfigService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BidPaymentConfigController extends Controller
{
    public IBidPaymentConfigService $_bidpaymentconfig;

    public function __construct(IBidPaymentConfigService $_bidpaymentconfig)
    {
        $this->_bidpaymentconfig = $_bidpaymentconfig;
    }


#region gigList

/**
 * @OA\Post(
 *     path="/api/bidpaymentconfig/create",
 *     operationId="createBidPaymentConfig",
 *     tags={"BidPaymentConfig"},
 *     security={{"sanctum":{}}},
 *     summary="Create new Bid Payment Config",
 *     description="Create a new Bid Payment Config",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"amount"},
 *              @OA\Property(property="amount", type="int", example="2000"),
 *
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
public function createBidPaymentConfig(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_bidpaymentconfig->createBidPaymentConfig($request);
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
 *     path="/api/bidpaymentconfig/getBidConfig",
 *     operationId="getAllBidPaymentConfig",
 *     tags={"BidPaymentConfig"},
 *     summary="Get  Bid Payment Config",
 *     description="Returns  Bid Payment Config",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No Bid Payment Config found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getBidPaymentConfig(): \Illuminate\Http\JsonResponse
{
    $bidpaymentconfig = $this->_bidpaymentconfig->getBidPaymentConfig();
    return response()->json([
        'success' => $bidpaymentconfig->status,
        'message' => $bidpaymentconfig->message,
        'data' => $bidpaymentconfig->data
    ]);
}


#endregion BidPaymentConfig

}
