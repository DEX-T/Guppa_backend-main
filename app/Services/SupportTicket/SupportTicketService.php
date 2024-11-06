<?php

namespace App\Services\SupportTicket;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\SupportTicket;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\SupportTicket\SupportTicketEntity;
use App\Domain\Interfaces\SupportTicket\ISupportTicketService;
use App\Domain\DTOs\Request\SupportTicket\SupportTicketRequestDto;
use App\Domain\DTOs\Response\SupportTicket\SupportTicketResponseDto;
use App\Domain\DTOs\Request\SupportTicket\UpdateSupportTicketRequestDto;
use App\Events\TicketEvent;

class SupportTicketService implements ISupportTicketService
{

    protected ?\Illuminate\Contracts\Auth\Authenticatable $_currentUser;

    function __construct()
    {
        $this->_currentUser = Auth::user();
    }
  
    public function createSupportTicket(Request $request): ApiResponseDto
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required','integer','exists:users,id'],
                'type' => ['required','string'],
                'message' => ['required', 'string', 'max:1000'],
                'attachments' => ['nullable','string']
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new SupportTicketRequestDto($validated['user_id'], $validated['type'], $validated['message'], $validated['attachments']);

            // Generate a random ticket_id
            $ticket_id = rand(111111,999999);

            $ticket = new SupportTicket();
            $ticket->user_id = $dto->user_id;
            $ticket->ticket_id = $ticket_id;
            $ticket->type = $dto->type;
            $ticket->message = $dto->message;
            $ticket->attachments = $dto->attachments;
            $ticket->status = "active";

            if ($ticket->save()) {
                
                event(new TicketEvent($this->_currentUser, $ticket));
                return new ApiResponseDto(true, "Support Ticket created successfully", HttpStatusCode::CREATED);
            } else {
                return new ApiResponseDto(false, "Error creating Support Ticket", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function resolveTicket(int $ticketId): ApiResponseDto
    {
        try {
           
            $ticket = SupportTicket::where('ticket_id', $ticketId)->first();
            if($ticket != null){
                $user = User::where('id', $ticket->user_id)->first();
                $ticket->status = "resolved";
                $ticket->updated_at = Carbon::now();
                if ($ticket->save()) {
                    event(new TicketEvent($user, $ticket));
                    return new ApiResponseDto(true, "Ticket resolved", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error something went wrong", HttpStatusCode::BAD_REQUEST);
                }
            }
            return new ApiResponseDto(false, "Ticket not found", HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function closeTicket(int $ticketId): ApiResponseDto
    {
        try {
           
            $ticket = SupportTicket::where('ticket_id', $ticketId)->first();
            if($ticket != null){
                $user = User::where('id', $ticket->user_id)->first();
                $ticket->status = "closed";
                $ticket->updated_at = Carbon::now();
                if ($ticket->save()) {
                    event(new TicketEvent($user, $ticket));
                    return new ApiResponseDto(true, "Ticket closed", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error something went wrong", HttpStatusCode::BAD_REQUEST);
                }
            }
            return new ApiResponseDto(false, "Ticket not found", HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllSupportTicket(): ApiResponseDto
    {
        try {
            $ticket = SupportTicket::orderBy('created_at', 'desc')->get();

            if ($ticket->isEmpty()) {
                return new ApiResponseDto(false, "No Support Ticket found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $ticket->map(function($ticketList){
                $ticketEntity = new SupportTicketEntity($ticketList);
                return new SupportTicketResponseDto($ticketEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllMySupportTicket(): ApiResponseDto
    {
        try {
            $ticket = SupportTicket::where('user_id', $this->_currentUser->id)->orderBy('created_at', 'desc')->get();

            if ($ticket->isEmpty()) {
                return new ApiResponseDto(false, "No Support Ticket found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $ticket->map(function($ticketList){
                $ticketEntity = new SupportTicketEntity($ticketList);
                return new SupportTicketResponseDto($ticketEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getSupportTicketbyId(int $id): ApiResponseDto
    {
        try {
            $ticket = SupportTicket::findOrFail($id);

            if ($ticket == null) {
                return new ApiResponseDto(false, "Support Ticket not found", HttpStatusCode::NOT_FOUND);
            }
            $ticketEntity = new SupportTicketEntity($ticket);
            $dto = new SupportTicketResponseDto($ticketEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateSupportTicket(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'message' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateSupportTicketRequestDto(
                $validatedData['id'],
                $validatedData['type'],
                $validatedData['message']
            );

            // 4. Find the SupportTicket item by ID (from the DTO)
            $ticket = SupportTicket::findOrFail($dto->id);

            if (!$ticket) {
                return new ApiResponseDto(false, "Support Ticket not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the Job Type
            $ticket->update([
                'type' => $dto->type,
                'message' => $dto->message,
            ]);

            // 6. Prepare the response DTO
            $ticketEntity = new SupportTicketEntity($ticket);
            $ticketResponse = new SupportTicketResponseDto($ticketEntity);

            return new ApiResponseDto(true, "Support Ticket updated successfully", HttpStatusCode::OK,  $ticketResponse->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteSupportTicket(int $id)
    {
        try {
            $ticket = SupportTicket::where('ticket_id', $id)->first();
            if ($ticket != null) {
                $ticket->delete();
                return new ApiResponseDto(true, "Support Ticket deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Support Ticket not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


}
