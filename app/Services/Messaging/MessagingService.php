<?php

namespace App\Services\Messaging;

use Exception;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Messaging;
use App\Models\Attachment;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Events\SendAttachment;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\ChatEntity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\ConversationAttachment;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\ConversationEntity;
use App\Domain\Interfaces\Messaging\IMessagingService;
use App\Domain\DTOs\Request\Messaging\ConversationRequestDto;
use App\Domain\DTOs\Request\Messaging\InitiateChatRequestDto;
use App\Domain\DTOs\Response\Messaging\ConversationResponseDto;
use App\Domain\DTOs\Request\Messaging\ConversationAttachmentRequestDto;
use App\Domain\DTOs\Response\Messaging\ChatResponseDto;

class MessagingService  implements IMessagingService
{
    protected $attachment;
    protected $_currentUser;
    public function __construct() {
       $this->_currentUser =  Auth::user();
      
    }
    // Implement your service methods here
    public function getMessage(int $chatId)
    {
        Log::error(" User email before gate ". $this->_currentUser->email . " " . $this->_currentUser->chatId);
        $messages = Conversation::where('chat_id', $chatId)->get();
    
        if($messages->isNotEmpty()){
            $dto = $messages->map(function($message) {
                $messageEntity = new ConversationEntity($message, $this->_currentUser);
                return new ConversationResponseDto($messageEntity);
            });
            return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
        }else{
            return new ApiResponseDto(false, "No Conversation Yet", HttpStatusCode::NO_CONTENT);
        }

    } 

     // Implement your service methods here
     public function getLatestMessage()
     {
         Log::error(" User email before gate ". $this->_currentUser->email . " " . $this->_currentUser->chatId);
         $chat = Chat::where('sender_chat_id', $this->_currentUser->chatId)->orWhere('receiver_chat_id', $this->_currentUser->chatId)->Latest()->first();
        if($chat != null){
            $messages = Conversation::where('chat_id', $chat->id)->get();
        
            if($messages->isNotEmpty()){
                $dto = $messages->map(function($message) {
                    $messageEntity = new ConversationEntity($message, $this->_currentUser);
                    return new ConversationResponseDto($messageEntity);
                });
                return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, "No Conversation Yet", HttpStatusCode::NO_CONTENT);
            }
        }
        return new ApiResponseDto(false, "No Chat Yet", HttpStatusCode::NO_CONTENT);
     } 

    public function sendMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sender_chat_id' => 'required|exists:users,chatId',
                'receiver_chat_id' => 'required|exists:users,chatId',
                'chat_id' => 'required|exists:chats,id',
                'message' => 'string',
                'message_id' => 'int',
                'IsFile' => 'boolean'
            ]);
            Log::error("Validation ");
            if($validator->fails()){
                Log::error("Validation error ", $validator->errors()->toArray());
                return new ApiResponseDto(false,'Validation failed', HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            Log::info("Validated ");
            $validated = $validator->validated();
            $dto = new ConversationRequestDto($validated);
            if($dto->message_id != 0){
                $conversation = Conversation::where('id', $dto->message_id)->first();
                $conversation->message = $dto->message;
                $conversation->updated_at = Carbon::now();
                Log::info("Message updated ");
            }else{
                $conversation = new Conversation();
                $conversation->chat_id = $dto->chat_id;
                $conversation->sender_chat_id = $dto->sender_chat_id;  
                $conversation->receiver_chat_id = $dto->receiver_chat_id;
                $conversation->message = $dto->message;
                $conversation->created_at = Carbon::now();
                $conversation->updated_at = null;
                $conversation->IsFile = $dto->IsFile;
                $conversation->read_at = null;
                Log::info("Message created ");
            }   
            $conversation->save();
            Log::info("Message saved ");
            $conEntity = new ConversationEntity($conversation, $this->_currentUser);
            $dto = new ConversationResponseDto($conEntity);
            broadcast(new MessageSent($conversation));
            
            Log::info("Message broadcasted ");
            return new ApiResponseDto(true,'Message Sent ', HttpStatusCode::CREATED, $dto->toArray());

        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server Error " .$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function sendAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|exists:chats,id',
            'attachment' => ['required', 'file', 'mimes:png,jpg,pdf,doc,docx,xlsx', 'max:2048'],
            'sender_chat_id' => 'required|exists:users,chatId',
            'receiver_chat_id' => 'required|exists:users,chatId',
        ]);
       
        if($validator->fails()){
            return new ApiResponseDto(false,'Validation failed', HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
        }
        $validated = $validator->validated();
        $dto = new ConversationAttachmentRequestDto($validated);
        Log::info('Request Data: ', $request->all());
        Log::info('Has Files: '. $request->hasFile('attachment') ? 'true' : 'false');
        if ($request->hasFile('attachment')) {
             $attachmentPaths = ""; // 
             Log::info("has file ". $request->file('attachment'));
                $rand = rand(1111, 9999);
               $file = $request->file('attachment');
                $fileName = $request->chat_id . '_' . $rand . '.' . $file->extension();
                $file->storeAs('public/uploads/attachments/'.$rand."/", $fileName); // Save the file to the storage
                Log::info("file ".$fileName);
                $attachment = new ConversationAttachment();
                $attachment->chat_id = $request->chat_id;
                $attachment->type = $file->getClientOriginalExtension();
                $attachment->path = $rand."/".$fileName;
                $attachment->save();
            
                // Add the file name to the array
            
               $conversation = new Conversation();
               $conversation->chat_id = $dto->chat_id;
               $conversation->sender_chat_id = $dto->sender_chat_id;  
               $conversation->receiver_chat_id = $dto->receiver_chat_id;
               $conversation->message = $rand."/".$fileName;
               $conversation->created_at = Carbon::now();
               $conversation->IsFile = true;
               $conversation->save();
             
               $attach = asset('storage/app/public/uploads/attachments/'.$attachment->path);
               
            event(new SendAttachment($attach));
            return new ApiResponseDto(true,'Attachment Sent ', HttpStatusCode::CREATED, $attach);
        }else{
            return new ApiResponseDto(false,'No Attachment Found', HttpStatusCode::NOT_FOUND);
        }
    }

    public function getMyChats() {
        try {
            $chats = Chat::where('sender_chat_id', $this->_currentUser->chatId)->orWhere('receiver_chat_id', $this->_currentUser->chatId)->get();
        if($chats->IsNotEmpty()){
            $dto = $chats->map(function($chat) {
                $chatEntity = new ChatEntity($chat, $this->_currentUser);
                return new ChatResponseDto($chatEntity);
            });
            return new ApiResponseDto(true,'Chats Fetched', HttpStatusCode::OK, $dto->toArray());
        }else{
            // not found
            return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
        }
        } catch (\Throwable $th) {
            return new ApiResponseDto(false, " Server error" . $th->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function initiateChat(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'sender_chat_id' => 'required|exists:users,chatId',
                'receiver_chat_id' => 'required|exists:users,chatId',
            ]);
    
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
    
            $validated = $validator->validated();
            Log::info("Validated ");
            $dto = new InitiateChatRequestDto($validated['sender_chat_id'], $validated['receiver_chat_id']);
            Log::info("Dto is ready " . $dto->sender_chat_id . " " . $dto->receiver_chat_id);

            //check if chat has been initiated before 
            $chat = Chat::where(['sender_chat_id'=> $dto->sender_chat_id, 'receiver_chat_id' => $dto->receiver_chat_id])->first();
            Log::info("Checking if Chat exists  ");

            if($chat){
                Log::info("Chat is existing  ");
                if($chat->IsBlocked){
                    Log::info("Chat is blocked  " . $chat->IsBlocked);
                    return new ApiResponseDto(false, "Chat has been blocked", HttpStatusCode::BAD_REQUEST);
                }
                return new ApiResponseDto(true, "Chat already initiated, go to messages", HttpStatusCode::CONFLICT);
            }
            $newChat = new Chat();
            $newChat->sender_chat_id = $dto->sender_chat_id;
            $newChat->receiver_chat_id = $dto->receiver_chat_id;
            $newChat->status = "online";
            $newChat->save();
            Log::info("Chat Initiated  " . $newChat->id);

            $conversation = new Conversation();
            $conversation->chat_id = $newChat->id;
            $conversation->sender_chat_id = $dto->sender_chat_id;  
            $conversation->receiver_chat_id = $dto->receiver_chat_id;
            $conversation->message = "Hello!";
            $conversation->created_at = Carbon::now();
            $conversation->updated_at = null;
            $conversation->IsFile = false;
            $conversation->read_at = null;
            $conversation->save();
            Log::info("Conversation started by   " . $newChat->sender_chat_id);
            $conEntity = new ConversationEntity($conversation, $this->_currentUser);
            $dto = new ConversationResponseDto($conEntity);
            return new ApiResponseDto(true, "Chat Initiated, Redirecting to Conversations", HttpStatusCode::CREATED, $dto->toArray());

        } catch (\Exception $e) {
            Log::info("Server Error  " . $e->getMessage());
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function deleteChat(int $chatId){
        try {
            $chat = Chat::find($chatId);
            if(!$chat){
                return new ApiResponseDto(false, "Chat not found", HttpStatusCode::NOT_FOUND);
            }else{
                $chat->delete();
                return new ApiResponseDto(true, "Chat deleted", HttpStatusCode::OK);
            }
        }catch(Exception $e){
            Log::info("Server Error  " . $e->getMessage());
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMessage(int $messageId){
        try {
            $chat = Conversation::find($messageId);
            if(!$chat){
                return new ApiResponseDto(false, "Message not found", HttpStatusCode::NOT_FOUND);
            }else{
                $chat->delete();
                return new ApiResponseDto(true, "Message deleted", HttpStatusCode::OK);
            }
        }catch(Exception $e){
            Log::info("Server Error  " . $e->getMessage());
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
