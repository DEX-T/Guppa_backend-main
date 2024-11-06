<?php
namespace App\Services\Testimonial;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\TestimonialCardEntity;
use App\Domain\Interfaces\Testimonial\ITestimonialCardService;
use App\Domain\DTOs\Request\Testimonial\CreateTestimonialCardrequestDto;
use App\Domain\DTOs\Response\Testimonial\TestimonialCardResponseDto;
use App\Models\TestimonialCard;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class TestimonialCardService implements ITestimonialCardService
{
    

    public function getAllTestimonialCards()
    {
        try {
            $testimonialcard = TestimonialCard::all();

            $dto = $testimonialcard->map(function($testimonialcard){
                $testimonialcardEntity = new TestimonialCardEntity($testimonialcard);
                return new TestimonialCardResponseDto($testimonialcardEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

           }          
    }  
        //get testimonialCard by id
    public function gettestimonialcard_id($id)
    {
        try {
            $testimonialcard = TestimonialCard::findOrFail($id);
            if($testimonialcard != null){
               $entity = new TestimonialCardEntity($testimonialcard); 
                $dto = new TestimonialCardResponseDto($entity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createTestimonialCard(Request $request)
    { 

        try {
            $validator = Validator::make($request->all(), [
                'testimonial' => ['required'],
                'testimonial_id' => [ 'required','int', 'exists:testimonials,id'],
       
        ]);
          
           if($validator->fails()){
            
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
           
            $validate = $validator->validated();

                $dto = new CreateTestimonialCardrequestDto($validate['testimonial'], $validate['testimonial_id']);
                $user = auth()->user();
               
                $testimonialcard = new TestimonialCard();
                $testimonialcard->testimonials = $dto->testimonial;
                $testimonialcard->portfolio = $user->portfolio ?? "null";
                $testimonialcard->name = $user->last_name . " ".$user->first_name;
                $testimonialcard->profile_picture = $user->profile_picture;
                $testimonialcard->testimonial_id = $dto->testimonial_Id;
                $testimonialcard->user_id = $user->id;
              
                
            if($testimonialcard->save()){
                return new ApiResponseDto(true, "Your testimony is submitted successfully", HttpStatusCode::OK );
            }else{
                return new ApiResponseDto(false, "Error creating testimony", HttpStatusCode::BAD_REQUEST);
            } 

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


    public function updateTestimonialCard(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                   'testimonial' => ['required'],
                    'testimonial_card_id' => ['required', 'int'],
                    'testimonial_id' => [ 'required','int']       
                     
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());


            }
                $testimonialcard =  TestimonialCard::find($id);
                if($testimonialcard == null){
                    return new ApiResponseDto(false, 'Card  not found', HttpStatusCode::NOT_FOUND);
                }

                  $validate = $validator->validated();

                 $dto = new CreateTestimonialCardrequestDto($validate['testimonial'], $validate['testimonial_id'], $validate['testimonial_card_id']);

                $testimonialcard->testimonials = $dto->testimonial;
                

            if($testimonialcard->save()){
                return new ApiResponseDto(true, "Testimonial Card updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Testimonial Card", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteTestimonialCard($id)
    {
        //Testimonial::destroy($id);
        try {
            $testimonialcard = TestimonialCard::find($id);
            if($testimonialcard){
                if($testimonialcard->delete()){
                    return new ApiResponseDto(true, "Card deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting Card", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "card not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
    
    // Implement your service methods here
}