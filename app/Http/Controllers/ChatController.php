<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\Interfaces\Messaging\IMessagingService;

class ChatController extends Controller
{
    private IMessagingService $_chat;
    public function __construct(IMessagingService $chat) {
        $this->_chat = $chat;
    }

      /**
     * @OA\Post(
     *     path="/api/chat/initiate-chat",
     *     operationId="initiateChat",
     *     tags={"Chat"},
     *      security={{"sanctum":{}}},
     *     summary="Start chatting with a freelancer or client",
     *     description="Start chatting with a freelancer or client",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sender_chat_id","receiver_chat_id"},
     *             @OA\Property(property="sender_chat_id", type="string", example="string"),
     *             @OA\Property(property="receiver_chat_id", type="string", example="string")
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
    public function initiate_chat(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_chat->initiateChat($request);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message,
                    'data' => $create->data
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
     * @OA\Post(
     *     path="/api/chat/send-message",
     *     operationId="sendMessage",
     *     tags={"Chat"},
     *      security={{"sanctum":{}}},
     *     summary="Send a message",
     *     description="send message",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sender_chat_id","receiver_chat_id","chat_id","message","message_id"},
     *             @OA\Property(property="sender_chat_id", type="string", example="string"),
     *             @OA\Property(property="receiver_chat_id", type="string", example="string"),
     *             @OA\Property(property="chat_id", type="int", example=0),
     *             @OA\Property(property="message", type="string", example="string"),
     *             @OA\Property(property="message_id", type="int", example=0),
     *             @OA\Property(property="IsFile", type="bool", example=false)
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
    public function send_message(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_chat->sendMessage($request);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message,
                    'data' => $create->data
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
     *     path="/api/chat/messages/{chat_id}",
     *     operationId="getMessages",
     *     tags={"Chat"},
     *     summary="Get list of messages for a specific chat",
     *     description="Returns list of messages for a chat",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="chat_id",
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
     * )
     */

    //Get All roles
    public function getAllMessages(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_chat->getMessage($request->chat_id);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);
        
    }

    /**
     * @OA\Get(
     *     path="/api/chat/latest_chat",
     *     operationId="getLatestChat",
     *     tags={"Chat"},
     *     summary="Get list of messages for a latest chat",
     *     description="Returns list of messages for latest chat",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     * )
     */

    //Get All roles
    public function getLatestChatMessages(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_chat->getLatestMessage();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);
        
    }

    
    /**
     * @OA\Get(
     *     path="/api/chat/my-chats",
     *     operationId="getMyChats",
     *     tags={"Chat"},
     *     summary="Get list of my chats",
     *     description="Returns list of my chats",
     *     security={{"sanctum":{}}},
    *      @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * )
    */

    //Get All roles
    public function getMyChats(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_chat->getMyChats();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ], $roleDto->code);
        
    }


/**
 * @OA\Post(
 *     path="/api/chat/send-attachment",
 *     operationId="sendAttachment",
 *     tags={"Chat"},
 *     security={{"sanctum":{}}},
 *     summary="Send attachment",
 *     description="Send attachment",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"attachment", "chat_id", "sender_chat_id", "receiver_chat_id"},
 *                 @OA\Property(
 *                     property="attachment",
 *                     type="string",
 *                     format="binary",
 *                     description="The file to upload",
 *                     example="file"
 *                 ),
 *                 @OA\Property(
 *                     property="chat_id",
 *                     type="int",
 *                     example=0
 *                 ),
 *                 @OA\Property(property="sender_chat_id", type="string", example="string"),
 *                 @OA\Property(property="receiver_chat_id", type="string", example="string"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     )
 * )
 */

    public function send_attachments(Request $request): \Illuminate\Http\JsonResponse
    {
       
            $create = $this->_chat->sendAttachment($request);
            if ($create->status) {
                return response()->json([
                    'success' => true,
                    'message' => $create->message,
                    'file' => $create->data
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
     *     path="/api/chat/delete/{message_id}",
     *     operationId="deleteMessage",
     *     tags={"Chat"},
     *      security={{"sanctum":{}}},
     *     summary="Delete message",
     *     description="delete message",
    *@OA\Parameter(
     *         name="message_id",
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
    public function delete_message(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_chat->deleteMessage($request->message_id);
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
     *     path="/api/chat/delete-chat/{chat_id}",
     *     operationId="deleteChat",
     *     tags={"Chat"},
     *      security={{"sanctum":{}}},
     *     summary="Delete chat",
     *     description="delete chat",
    *@OA\Parameter(
     *         name="chat_id",
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
    public function delete_chat(Request $request): \Illuminate\Http\JsonResponse
    {
            $create = $this->_chat->deleteChat($request->chat_id);
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
}
