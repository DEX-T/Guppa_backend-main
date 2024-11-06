<?php

namespace App\Http\Controllers\SupportTicket;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\PrivacyPolicy;
use App\Events\ContactUsEvent;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Domain\Interfaces\SupportTicket\ISupportTicketService;
use App\Models\BannerImage;

class SupportTicketController extends Controller
{
    public ISupportTicketService $_ticket;

    public function __construct(ISupportTicketService $_ticket)
    {
        $this->_ticket = $_ticket;
    }


#region gigList

/**
 * @OA\Post(
 *     path="/api/supportticket/create",
 *     operationId="createSupportTicket",
 *     tags={"SupportTicket"},
 *     security={{"sanctum":{}}},
 *     summary="Create new Support Ticket",
 *     description="Create a new SupportTicket",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","type","message","status"},
 *             @OA\Property(property="user_id", type="int", example="2"),
 *             @OA\Property(property="type", type="string", example="Jobs"),
 *             @OA\Property(property="message", type="string", example="i am having problem"),
 *             @OA\Property(property="attachments", type="string", example="file1,file2,file3", description="attachments path separeted by comma"),
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
public function createSupportTicket(Request $request): \Illuminate\Http\JsonResponse
{
    $create = $this->_ticket->createSupportTicket($request);
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
 *     path="/api/supportticket/getAll",
 *     operationId="getAllSupportTicket",
 *     tags={"SupportTicket"},
 *     summary="Get list of all Support Ticket",
 *     description="Returns list of All Support Ticket",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No Support Ticket found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getAllSupportTicket(): \Illuminate\Http\JsonResponse
{
    $ticket = $this->_ticket->getAllSupportTicket();
    return response()->json([
        'success' => $ticket->status,
        'message' => $ticket->message,
        'data' => $ticket->data
    ]);
}

/**
 * @OA\Get(
 *     path="/api/supportticket/getById/{id}",
 *     operationId="getSupportTicketById",
 *     tags={"SupportTicket"},
 *     summary="Get Support Ticket information",
 *     description="Returns Support Ticket data",
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getSupportTicketbyId(Request $request): \Illuminate\Http\JsonResponse
{
    $ticket = $this->_ticket->getSupportTicketbyId($request->id);
    if ($ticket->status) {
        return response()->json([
            'success' => $ticket->status,
            'message' => $ticket->message,
            'data' => $ticket->data
        ], $ticket->code);
    } else {
        return response()->json([
            'success' => $ticket->status,
            'message' => $ticket->message,
            'data' => $ticket->data
        ], $ticket->code);
    }
}


    /**
     * @OA\Get(
     *     path="/api/supportticket/get-my-tickets",
     *     operationId="getAllMySupportTicket",
     *     tags={"SupportTicket"},
     *     summary="Get list of all my Tickets",
     *     description="Returns list of All my Tickets",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Support Ticket found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getAllMySupportTicket(): \Illuminate\Http\JsonResponse
    {
        $ticket = $this->_ticket->getAllMySupportTicket();
        return response()->json([
            'success' => $ticket->status,
            'message' => $ticket->message,
            'data' => $ticket->data
        ]);
    }

/**
 * @OA\Put(
 *     path="/api/supportticket/update",
 *     operationId="updateSupportTicket",
 *     tags={"SupportTicket"},
 *     security={{"sanctum":{}}},
 *     summary="Update Support Ticket",
 *     description="Update Support Ticket",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id","type","message"},
 *             @OA\Property(property="id", type="integer", example=1),
 *              @OA\Property(property="type", type="string", example="Jobs"),
 *              @OA\Property(property="message", type="string", example="i am having problem"),
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     ),
 * )
 */
public function updateSupportTicket(Request $request): \Illuminate\Http\JsonResponse
{
    $update = $this->_ticket->updateSupportTicket($request);
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
 *     path="/api/supportticket/delete/{id}",
 *     operationId="deleteSupportTicket",
 *     tags={"SupportTicket"},
 *     security={{"sanctum":{}}},
 *     summary="Delete SupportTicket",
 *     description="Delete ticket",
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteSupportTicket(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_ticket->deleteSupportTicket($request->id);
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
 *     path="/api/supportticket/resolve/{id}",
 *     operationId="resolveSupportTicket",
 *     tags={"SupportTicket"},
 *     security={{"sanctum":{}}},
 *     summary="Resolve Ticket",
 *     description="Resolve ticket",
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function resolveSupportTicket(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_ticket->resolveTicket($request->id);
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
 *     path="/api/supportticket/close/{id}",
 *     operationId="closeSupportTicket",
 *     tags={"SupportTicket"},
 *     security={{"sanctum":{}}},
 *     summary="Close Ticket",
 *     description="Close ticket",
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function closeSupportTicket(Request $request): \Illuminate\Http\JsonResponse
{
    $delete = $this->_ticket->closeTicket($request->id);
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

#endregion SupportTicket


/**
 * @OA\Post(
 *     path="/api/privacy/create",
 *     operationId="createPrivacy",
 *     tags={"Privacy"},
 *     security={{"sanctum":{}}},
 *     summary="Create new privacy",
 *     description="Create a new privacy",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"policy"},
 *             @OA\Property(property="policy", type="string", example="string")
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
public function privacyPolicy(Request $request)
{
    try {
        $validated = Validator::make($request->all(), [
            'policy' => 'required',
        ]);
    
        if($validated->fails()){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'validation error',
                    'error' => $validated->errors()->toArray()
            ], HttpStatusCode::VALIDATION_ERROR);
        }
        $validated = $validated->validated();
        $check = PrivacyPolicy::where('id', 1)->first();
        if($check){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You have already created privacy policy, you can update it',
            ], HttpStatusCode::CONFLICT);
        }else{
            if(PrivacyPolicy::create($validated)){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Privacy policy created!',
                ], HttpStatusCode::OK);
            }
        }
    } catch (\Exception $e) {
        return response()->json(
            [
                'success' => false,
                'message' => 'Service error '.$e->getMessage()
        ], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}

/**
 * @OA\Put(
 *     path="/api/privacy/update",
 *     operationId="updatePrivacy",
 *     tags={"Privacy"},
 *     security={{"sanctum":{}}},
 *     summary="Create new privacy",
 *     description="Create a new privacy",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"policy_id","policy"},
 *             @OA\Property(property="policy_id", type="int", example="0"),
 *             @OA\Property(property="policy", type="string", example="Jobs")
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
public function updatePrivacyPolicy(Request $request)
{
    try {
        $validated = Validator::make($request->all(), [
            'policy' => 'required',
            'policy_id' => 'int|required'
        ]);
    
        if($validated->fails()){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'validation error',
                    'error' => $validated->errors()->toArray()
            ], HttpStatusCode::VALIDATION_ERROR);
        }
        $validated = $validated->validated();
        $check = PrivacyPolicy::where('id', $validated['policy_id'])->first();
        if(!$check){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Policy does not exist',
            ], HttpStatusCode::NOT_FOUND);
        }else{
            $check->policy = $validated['policy'];
            $check->updated_at = Carbon::now();
            $check->save();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Privacy policy updated!',
            ], HttpStatusCode::OK);
        }
    } catch (\Exception $e) {
        return response()->json(
            [
                'success' => false,
                'message' => 'Service error '.$e->getMessage()
        ], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}



/**
 * @OA\Get(
 *     path="/api/privacy/get",
 *     operationId="getPrivacy",
 *     tags={"Privacy"},
 *     summary="Get privacy",
 *     description="Returns privacy",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No contacts found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function getPrivacyPolicy()
{
    $privacy = PrivacyPolicy::all();
    if($privacy->isNotEmpty()){
        $dto = $privacy->map(function($policy) {
            return [
                'id' => $policy->id,
                'policy' => $policy->policy,
                'created_at' => $policy->created_at,
                'updated_at' => $policy->updated_at
            ];
        });

        return response()->json(
            [
                'success' => true,
                'message' => 'Privacy policy!',
                'data' => $dto
        ], HttpStatusCode::OK);
    }else{
        return response()->json(
            [
                'success' => false,
                'message' => 'NOT FOUND',
        ], HttpStatusCode::NOT_FOUND);
    }
   
}



/**
 * @OA\Post(
 *     path="/api/contact/create",
 *     operationId="createContactUs",
 *     tags={"Contact"},
 *     security={{"sanctum":{}}},
 *     summary="Create new contact",
 *     description="Create a new contact",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","subject","message"},
 *             @OA\Property(property="name", type="string", example="john doe"),
 *             @OA\Property(property="email", type="string", example="doe@gmail.com"),
 *             @OA\Property(property="subject", type="string", example="complain"),
 *             @OA\Property(property="message", type="string", example="description")
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
public function contactUs(Request $request)
{
    try {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string|min:20',
        ]);
    
        if($validated->fails()){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'validation error',
                    'error' => $validated->errors()->toArray()
            ], HttpStatusCode::VALIDATION_ERROR);
        }
        $validated = $validated->validated();
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'content' => $validated['message']
        ];
        if(Contact::create($validated)){
            //event
            event(new ContactUsEvent($data));
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Your message has been submitted!, check your mail for response',
            ], HttpStatusCode::OK);
        }
    
        return response()->json(
            [
                'success' => false,
                'message' => 'Something went wrong, try again',
        ], HttpStatusCode::BAD_REQUEST);
    } catch (\Exception $e) {
        return response()->json(
            [
                'success' => false,
                'message' => 'Service error '.$e->getMessage()
        ], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}

 
/**
 * @OA\Get(
 *     path="/api/contact/getAll",
 *     operationId="getAllContacts",
 *     tags={"Contact"},
 *     summary="Get list of all contacts",
 *     description="Returns list of All contacts",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No contacts found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
    public function getContacts()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        if($contacts->isNotEmpty()){
            $dto = $contacts->map(function($contact) {
                return [
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'subject' => $contact->subject,
                    'message' => $contact->message
                ];
            });

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Privacy policy!',
                    'data' => $dto
            ], HttpStatusCode::OK);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'NOT FOUND',
            ], HttpStatusCode::NOT_FOUND);
        }
       
    }

/**
 * @OA\Delete(
 *     path="/api/contact/delete/{id}",
 *     operationId="deleteContactUs",
 *     tags={"Contact"},
 *     security={{"sanctum":{}}},
 *     summary="Delete contact",
 *     description="Delete contact",
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
 *         description="Support Ticket not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
public function deleteContact($id)
{
    $contact = Contact::find($id);

    if (!$contact) {
        return response()->json(
            [
                'success' => false,
                'message' => 'Not Found!',
        ], HttpStatusCode::NOT_FOUND);
    }

    $contact->delete();
    return response()->json(
        [
            'success' => true,
            'message' => 'Contact deleted',
    ], HttpStatusCode::OK);
}

    /**
 * @OA\Post(
 *     path="/api/banner/create",
 *     operationId="createBanner",
 *     tags={"Banner"},
 *     security={{"sanctum":{}}},
 *     summary="Create new banner",
 *     description="Create a new banner",
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"image_url"},
 *             @OA\Property(property="image_url", type="string", example="image.png"),
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
    public function createBannerImage(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'image_url' => 'required|string'
            ]);
        
            if($validated->fails()){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'validation error',
                        'error' => $validated->errors()->toArray()
                ], HttpStatusCode::VALIDATION_ERROR);
            }
            $validated = $validated->validated();
            if(BannerImage::create($validated)){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Banner image created',
                ], HttpStatusCode::OK);
            }
        
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Something went wrong, try again',
            ], HttpStatusCode::BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Service error '.$e->getMessage()
            ], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    
    
/**
 * @OA\Get(
 *     path="/api/banner/getAll",
 *     operationId="getAllBanners",
 *     tags={"Banner"},
 *     summary="Get list of all banners",
 *     description="Returns list of All banners",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No banners found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
    public function getBanners()
    {
        $banners = BannerImage::orderBy('created_at', 'desc')->get();
        if($banners->isNotEmpty()){
            $dto = $banners->map(function($banner) {
                return [
                    'id' => $banner->id,
                    'image_url' => asset("storage/app/public/uploads/".$banner->image_url),
                    'created_at' => $banner->created_at,
                    'updated_at' => $banner->updated_at,
                    'status' => $banner->status
                ];
            });

            return response()->json(
                [
                    'success' => true,
                    'message' => 'banner image!',
                    'data' => $dto
            ], HttpStatusCode::OK);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'NOT FOUND',
            ], HttpStatusCode::NOT_FOUND);
        }
       
    }

    /**
     * @OA\Delete(
     *     path="/api/banner/delete/{id}",
     *     operationId="deleteBanner",
     *     tags={"Banner"},
     *     security={{"sanctum":{}}},
     *     summary="Delete banner",
     *     description="Delete banner",
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
     *         description="Support Ticket not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deleteBanner($id)
    {
        $banner = BannerImage::find($id);

        if (!$banner) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Not Found!',
            ], HttpStatusCode::NOT_FOUND);
        }

        $banner->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'banner deleted',
        ], HttpStatusCode::OK);
    }


    /**
     * @OA\Put(
     *     path="/api/banner/activate/{id}",
     *     operationId="activateBanner",
     *     tags={"Banner"},
     *     security={{"sanctum":{}}},
     *     summary="activate banner",
     *     description="activate banner",
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
     *         description="banner not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function activateBanner($id)
    {
        $banner = BannerImage::find($id);

        if (!$banner) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Not Found!',
            ], HttpStatusCode::NOT_FOUND);
        }

        $banner->status = "active";
        $banner->save();
        return response()->json(
            [
                'success' => true,
                'message' => 'banner activated',
        ], HttpStatusCode::OK);
    }

    
    /**
     * @OA\Put(
     *     path="/api/banner/deactivate/{id}",
     *     operationId="deactivateBanner",
     *     tags={"Banner"},
     *     security={{"sanctum":{}}},
     *     summary="deactivate banner",
     *     description="deactivate banner",
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
     *         description="banner not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function deactivateBanner($id)
    {
        $banner = BannerImage::find($id);

        if (!$banner) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Not Found!',
            ], HttpStatusCode::NOT_FOUND);
        }

        $banner->status = "inactive";
        $banner->save();
        return response()->json(
            [
                'success' => true,
                'message' => 'banner deactivated',
        ], HttpStatusCode::OK);
    }



}
