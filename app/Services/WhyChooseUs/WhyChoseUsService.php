<?php

namespace App\Services\WhyChooseUs;

use App\Models\Guppa;
use App\Models\Navbar;
use App\Models\GuppaCard;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\WhyChooseUs\WhyChooseUsEntity;
use App\Domain\Interfaces\WhyChooseUs\IWhyChoseUsService;
use App\Domain\Entities\WhyChooseUs\WhyChooseUsCardEntity;
use App\Domain\DTOs\Request\WhyChooseUs\CreateWhyChooseUsDto;
use App\Domain\DTOs\Request\WhyChooseUs\UpdateWhyChooseUsDto;
use App\Domain\DTOs\Response\WhyChooseUs\WhyChooseUsResponseDto;
use App\Domain\DTOs\Response\WhyChooseUs\WhyChooseUsCardResponseDto;
use App\Domain\DTOs\Request\WhyChooseUs\CreateWhyChooseUsCardRequestDto;
use App\Domain\DTOs\Request\WhyChooseUs\UpdateWhyChooseUsCardRequestDto;

class WhyChoseUsService implements IWhyChoseUsService
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'heading' => ['required'],
                'description' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new CreateWhyChooseUsDto($validated['heading'], $validated['description']);

            $exist = Guppa::where('id', 1)->first();
            if($exist != null){
                return new ApiResponseDto(false, "You have already created why chose us, please update the existing one!", HttpStatusCode::CONFLICT);
            }
            $WhyChooseUs = new Guppa();
            $WhyChooseUs->heading = $dto->heading;
            $WhyChooseUs->description = $dto->description;
            $WhyChooseUs->save();
                return new ApiResponseDto(true, "Why Choose Us created successfully", HttpStatusCode::CREATED);
          
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function GetAllFE()
    {
        try {
            $WhyChooseUs = Guppa::orderBy('created_at', 'desc')->first();
            if ($WhyChooseUs == null) {
                return new ApiResponseDto(true, "No Why Choose Us found", HttpStatusCode::NO_CONTENT);
            }
             $dto = [
                'heading' => $WhyChooseUs->heading,
                'description' => $WhyChooseUs->description,
                'cards' => $this->getCards($WhyChooseUs->id)
             ];
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto);
        } catch (\Exception $e)  {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    private function getCards($id){
        $cards = GuppaCard::where('guppa_id', $id)->OrderBy('created_at', 'desc')->Limit(3)->get();
        if($cards->isNotEmpty()){
           $dto = $cards->map(function($card) {
                return [
                    'picture' =>  asset("storage/app/public/uploads/".$card->picture),
                    'title' => $card->title,
                    'description' => $card->description
                ];
            });
            return $dto;
        }else{
            return [];
        }
        
    }

    public function GetAll()
    {
        try {
            $WhyChooseUs = Guppa::all();

            if ($WhyChooseUs->isEmpty()) {
                return new ApiResponseDto(false, "No Why Choose Us found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $WhyChooseUs->map(function ($us) {
                $whyChoseUsEntity = new WhyChooseUsEntity($us);
                return new WhyChooseUsResponseDto($whyChoseUsEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function getById(int $id)
    {
        try {
            $WhyChooseUs = Guppa::findOrFail($id);

            if ($WhyChooseUs == null) {
                return new ApiResponseDto(false, "Why Choose Us not found", HttpStatusCode::NOT_FOUND);
            }
            $whyChooseEntity = new WhyChooseUsEntity($WhyChooseUs);
            $dto = new WhyChooseUsResponseDto($whyChooseEntity);

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'int'],
                'heading' => ['required', 'string'],
                'description' => ['required', 'string'],

            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateWhyChooseUsDto(
                $validatedData['id'],
                $validatedData['heading'],
                $validatedData['description'],
            );

            // 4. Find the NavbarMenu item by ID (from the DTO)
            $whyChooseUs = Guppa::findOrFail($dto->id);

            if ($whyChooseUs  == null) {
                return new ApiResponseDto(false, "WhyChoose Use  not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the menu item
            $whyChooseUs->update([
                'heading' => $dto->heading,
                'description' => $dto->description,

            ]);

            // 6. Prepare the response DTO
            $WhyChooseUsEntity = new WhyChooseUsEntity($whyChooseUs);
            $whychooseusResponseDto = new WhyChooseUsResponseDto($WhyChooseUsEntity);

            return new ApiResponseDto(true, "Why Choose Us updated successfully", HttpStatusCode::OK, $whychooseusResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(int $id)
    {
        try {
            $WhyChooseUs = Guppa::findOrFail($id);

            if ($WhyChooseUs) {
                $WhyChooseUs->delete();
                return new ApiResponseDto(true, "Why Choose Us deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Why Choose Us not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function createCard(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'guppa_id' => ['required', 'integer', 'exists:guppas,id'],
                'picture' => ['required', 'string'],
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]);


            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();
            $dto = new CreateWhyChooseUsCardRequestDto($validatedData['guppa_id'], $validatedData['picture'], $validatedData['title'], $validatedData['description']);
            $card = new GuppaCard();
            $card->guppa_id = $dto->whychooseus_id;
            $card->picture = $dto->picture;
            $card->title = $dto->title;
            $card->description = $dto->description;
            if($card->save()){
                return new ApiResponseDto(true, "Why Choose Us Card created successfully", HttpStatusCode::CREATED);

            }else{
                return new ApiResponseDto(false, "Error creating why choose us", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function GetAllCard()
    {
        try {
            $WhyChooseUsCards = GuppaCard::all();

            if ($WhyChooseUsCards->isEmpty()) {
                return new ApiResponseDto(false, "No Why Choose Us Card found", HttpStatusCode::NOT_FOUND);
            }

            $dto = $WhyChooseUsCards->map(function ($us) {
                $whyChoseUsCardEntity = new WhyChooseUsCardEntity($us);
                return new WhyChooseUsCardResponseDto($whyChoseUsCardEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function getCardById(int $id)
    {
        try {
            $WhyChooseUsCard = GuppaCard::findOrFail($id);

            if (!$WhyChooseUsCard) {
                return new ApiResponseDto(false, "Why Choose Us Card not found", HttpStatusCode::NOT_FOUND);
            }

            $whyChoseUsCardEntity = new WhyChooseUsCardEntity($WhyChooseUsCard);
            $dto = new WhyChooseUsCardResponseDto($whyChoseUsCardEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateCard(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'guppa_id' => ['required', 'integer'],
                'picture' => ['required'],
                'title' => ['required'],
                'description' => ['required'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }


            $validatedData = $validator->validated();


            $dto = new UpdateWhyChooseUsCardRequestDto(
                $validatedData['guppa_id'],
                $validatedData['title'],
                $validatedData['picture'],
                $validatedData['description'],
                $validatedData['id'],
            );

            $WhyChooseUsCard = GuppaCard::findOrFail($dto->id);

            if (!$WhyChooseUsCard) {
                return new ApiResponseDto(false, "Why Choose Us Card not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the menu item
            $WhyChooseUsCard->update([
                'guppa_id' => $dto->whychooseus_id,
                'picture' => $dto->picture,
                'title' => $dto->title,
                'description' => $dto->description,
            ]);

            // 6. Prepare the response DTO
            $WhyChooseUsCardEntity = new WhyChooseUsCardEntity($WhyChooseUsCard);
            $WhyChooseUsCardResponseDto = new WhyChooseUsCardResponseDto($WhyChooseUsCardEntity);

            return new ApiResponseDto(true, "Why Choose Us Card updated successfully", HttpStatusCode::OK, $WhyChooseUsCardResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteCard(int $id)
    {
        try {
            $WhyChooseUsCard = GuppaCard::findOrFail($id);

            if ($WhyChooseUsCard) {
                $WhyChooseUsCard->delete();
                return new ApiResponseDto(true, "Why Choose Us Card deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Why Choose Us Card not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
