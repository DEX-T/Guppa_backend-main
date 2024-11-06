<?php

namespace App\Services\Testimonial;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\TestimonialEntity;
use App\Domain\DTOs\Response\Users\UserResponseDto;
use App\Domain\Interfaces\Testimonial\ITestimonialsService;
use App\Domain\DTOs\Request\Testimonial\CreateTestimonialRequestDto;
use App\Domain\DTOs\Response\Testimonial\TestimonialsResponseDto;

class TestimonialsService implements ITestimonialsService
{

       
    //Get all Testimonials

     public function getAllTestimonials()
    {
        try {
            $testimonials = Testimonial::all();

            $dto = $testimonials->map(function($testimonials){
                $testimonialEntity = new TestimonialEntity($testimonials);
                return new TestimonialsResponseDto($testimonialEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
        
    }

        //get testimonial by id
    public function getTestimonial($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            if($testimonial != null){
               $entity = new TestimonialEntity($testimonial); 
                $dto = new TestimonialsResponseDto($entity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createTestimonial(Request $request)
    {
        
        try {

            $validator = Validator::make($request->all(), [
                'title' => ['required'],
                'description' => ['required'],
                
        ]);
          
           if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
           
            $validate = $validator->validated();

                $dto = new CreateTestimonialRequestDto($validate['title'], $validate ['description']);
               
                $testimonial = new Testimonial();
                $testimonial->title = $dto->title;
                $testimonial->description = $dto->description;
                
            if($testimonial->save()){
                return new ApiResponseDto(true, "Testimonial added successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error creating Testimonial", HttpStatusCode::BAD_REQUEST);
            } 

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
       
    }

    public function updateTestimonial(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                   
                    'title' => ['required'],
                    'description' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                // $validate = $validator->validated();
                // $dto = new TestimonialRequestDto($validate['title'], $validate['description']); 

                $testimonial =  Testimonial::find($id);
                if($testimonial == null){
                    return new ApiResponseDto(false, 'Testimonial does not exist', HttpStatusCode::NOT_FOUND);
                }
                $testimonial->title = $request->title;
                $testimonial->description = $request->description;
            if($testimonial->save()){
                return new ApiResponseDto(true, "Testimonial updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Testimonial", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteTestimonial($id)
    {
        //Testimonial::destroy($id);
        try {
            $testimonial = Testimonial::find($id);
            if($testimonial){
                if($testimonial->delete()){
                    return new ApiResponseDto(true, "Testimonial deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting Testimonial", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Testimonial not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
    

   
}