<?php

namespace App\Services\Configuration;
use App\Models\Footer;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Models\FooterCopyRight;
use App\Models\FooterSocialMedia;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Domain\Entities\FooterEntity;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\FooterCopyrightEntity;
use App\Domain\Entities\FooterSocialMediaEntity;
use App\Domain\Interfaces\Configuration\IFooterService;
use App\Domain\DTOs\Response\Configuration\FooterResponseDto;
use App\Domain\DTOs\Request\Configuration\CreateFooterRequestDto;
use App\Domain\DTOs\Response\Configuration\FooterCopyrightResponseDto;
use App\Domain\DTOs\Response\Configuration\FooterSocialMediaResponseDto;
use App\Domain\DTOs\Request\Configuration\CreateFooterCopyrightRequestDto;
use App\Domain\DTOs\Request\Configuration\CreateFooterSocialMediaRequestDto;




class FooterService implements IFooterService
{

    protected $_currentUser;
    public function __construct() {
       $this->_currentUser =  Auth::user();
      
    }

    public function getAllFooterSocialMedia()
    {
        try {
            $footerSocialMedia = FooterSocialMedia::all();

            $dto = $footerSocialMedia->map(function($footerSocialMedia){
                $footerSocialMediaEntity = new FooterSocialMediaEntity($footerSocialMedia);
                return new FooterSocialMediaResponseDto($footerSocialMediaEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }
        //get Socials by id

    public function getFooterSocialMedia($id)
    {
        try {
            $footerSocialMedia = FooterSocialMedia::findOrFail($id);
            if($footerSocialMedia != null){
               $entity = new FooterSocialMediaEntity($footerSocialMedia);
                $dto = new FooterSocialMediaResponseDto($entity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function createFooterSocialMedia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'icon' => ['required'],
                'url' => ['required'],
                'footer_id'=>['int']

        ]);

           if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                 $validate = $validator->validated();
                $dto = new CreateFooterSocialMediaRequestDto($validate['icon'], $validate ['url']);

                $socialExist = FooterSocialMedia::where('icon', $dto->icon)->whereOr('url', $dto->url)->first();
                if($socialExist != null){
                    return new ApiResponseDto(false, 'Social media already exist', HttpStatusCode::CONFLICT);
                }

                $footerSocialMedia = new FooterSocialMedia();
                $footerSocialMedia->footer_id = $dto->footer_id;
                $footerSocialMedia->icon = $dto->icon;
                $footerSocialMedia->url = $dto->url;

            if($footerSocialMedia->save()){
                return new ApiResponseDto(true, "Socials added successfully", HttpStatusCode::OK, $footerSocialMedia);
            }else{
                return new ApiResponseDto(false, "Error creating Socials", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getFooterSocialMediaFE()
    {
        try {
            $footer = Footer::where('id', 1)->first();
            $footerSocialMedia = FooterSocialMedia::where('status', 'active')->orderBy('created_at', 'desc')->get();

            if($footer != null){
                $dto = [
                    'title' => $footer->title,
                    'description' => $footer->description,
                    'socials' => $footerSocialMedia != null ? $footerSocialMedia->map(function($f) {
                                return [
                                    'icon' => $f->icon,
                                    'url' => $f->url
                                ];
                    })->toArray() : []
                 ];
            
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto);
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


        //update Social Media
    public function updateFooterSocialMedia(Request $request, $id)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'icon' => ['required', 'string'],
                'url' => ['required', 'url'],
            ]);

            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

                // $validate = $validator->validated();
                // $dto = new TestimonialRequestDto($validate['title'], $validate['description']);

                $footerSocialMedia =  FooterSocialMedia::find($id);
                if($footerSocialMedia == null){
                    return new ApiResponseDto(false, 'Social media does not exist', HttpStatusCode::NOT_FOUND);
                }
                $footerSocialMedia->icon = $request->icon;
                $footerSocialMedia->url = $request->url;
            if($footerSocialMedia->save()){
                return new ApiResponseDto(true, "Social Media updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating socials", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    //delete Footer social Media
    public function deleteFooterSocialMedia($id)
    {
        try {
            
            $footerSocialMedia = FooterSocialMedia::find($id);
            if($footerSocialMedia){
                if($footerSocialMedia->delete()){
                    return new ApiResponseDto(true, "FooterSocialMedia deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting FooterSocialMedia", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "FooterSocialMedia not found", HttpStatusCode::NOT_FOUND);
            }
        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function activateFooterSocialMedia($id)
    {
        try {
            $footerSocialMedia = FooterSocialMedia::findOrFail($id);
            if($footerSocialMedia){
                $footerSocialMedia->status = 'active';
                $footerSocialMedia->save();
                return new ApiResponseDto(true, "Social  activated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Social not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deactivateFooterSocialMedia($id)
    {
        try {
            $footerSocialMedia = FooterSocialMedia::findOrFail($id);
            if($footerSocialMedia){
                $footerSocialMedia->status = 'inactive';
                $footerSocialMedia->save();
                return new ApiResponseDto(true, "Social deactivated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Social not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }



    //Copyright Region

    public function getAllFooterCopyrights()
    {
        try {
            $footerCopyright = FooterCopyRight::all();

            $dto = $footerCopyright->map(function($footerCopyright){
                $footerCopyrightEntity = new FooterCopyrightEntity($footerCopyright);
                return new FooterCopyrightResponseDto($footerCopyrightEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

        //get Copyright by ID
    public function getFooterCopyright($id)
    {
        try {
            $footerCopyright = FooterCopyRight::findorfail($id);
            if($footerCopyright != null){
               $entity = new FooterCopyrightEntity($footerCopyright);
                $dto = new FooterCopyrightResponseDto($entity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);

            }

        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }


    public function createFooterCopyright(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required'],
                'description' => ['required'],
                'footer_id' => ['int']
        ]);

           if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validate = $validator->validated();

                $dto = new CreateFooterCopyrightRequestDto($validate['title'], $validate ['description'], $validate ['footer_id']);

                $footerCopyright = new FooterCopyRight();
                $footerCopyright->footer_id = $dto->footer_id;
                $footerCopyright->title = $dto->title;
                $footerCopyright->description = $dto->description;

            if($footerCopyright->save()){
                return new ApiResponseDto(true, "Copyright added successfully", HttpStatusCode::OK, $footerCopyright);
            }else{
                return new ApiResponseDto(false, "Error creating copyright", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

    //update copyright
    public function updateFooterCopyright(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'title' => ['required'],
                    'description' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
                $footerCopyright =  FooterCopyRight::find($id);
                if($footerCopyright == null){
                    return new ApiResponseDto(false, 'Copyright does not exist', HttpStatusCode::NOT_FOUND);
                }
                $footerCopyright->title = $request->title;
                $footerCopyright->description = $request->description;
            if($footerCopyright->save()){
                return new ApiResponseDto(true, "Footer copyright updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating Footer copy right", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    //delete copyright

    public function deleteFooterCopyright($id)
    {
        try {
            $footerCopyright = FooterCopyRight::find($id);
            if($footerCopyright){
                if($footerCopyright->delete()){
                    return new ApiResponseDto(true, "Copyright deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting Copyright", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Copyright not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

  


     //FOR FOOTER ALONE
    public function getFooters()
    {
        try {
            $footers = Footer::all();
            $dto = $footers->map(function($footers){
                $footerEntity = new FooterEntity($footers);
                return new FooterResponseDto($footerEntity);
            });
            return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

        //get footer by id
    public function getFooter($id)
    {
        try {
            $footer = Footer::findorfail($id);
            if($footer != null){
               $entity = new FooterEntity($footer);
                $dto = new FooterResponseDto($entity);
                return new ApiResponseDto(true, 'successful', HttpStatusCode::OK, $dto->toArray());
            }else{
                return new ApiResponseDto(false, 'Not Found', HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    // Create Footer

    public function createFooter(Request $request)
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

                $dto = new CreateFooterRequestDto($validate['title'], $validate ['description']);
                $footerExist = Footer::where('id', 1)->first();
                if($footerExist != null){
                    return new ApiResponseDto(false, 'Footer already exist, update it', HttpStatusCode::BAD_REQUEST);
                }

                $footer = new Footer();
                $footer->title = $dto->title;
                $footer->description = $dto->description;
                $footer->save();
                return new ApiResponseDto(true, "Footer added successfully", HttpStatusCode::OK, $footer);
            
        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }

    }

            //Update

    public function updateFooter(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                    'title' => ['required'],
                    'description' => ['required']
            ]);
            if($validator->fails()){
                return new ApiResponseDto(false, "Validation Error ", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
                $footer =  Footer::find($id);
                if($footer == null){
                    return new ApiResponseDto(false, 'Footer does not exist', HttpStatusCode::NOT_FOUND);
                }
                $footer->title = $request->title;
                $footer->description = $request->description;
            if($footer->save()){
                return new ApiResponseDto(true, "Footer updated successfully", HttpStatusCode::OK);
            }else{
                return new ApiResponseDto(false, "Error updating footer", HttpStatusCode::BAD_REQUEST);
            }

        } catch (\Exception $e) {
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteFooter($id)
    {
        try {

            if (Gate::denies('IsSuperadmin', $this->_currentUser)) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to perform this action'
                ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED));
            }

            $footer = Footer::find($id);
            if($footer){
                if($footer->delete()){
                    return new ApiResponseDto(true, "Footer deleted successfully", HttpStatusCode::OK);
                }else{
                    return new ApiResponseDto(false, "Error deleting Footer", HttpStatusCode::BAD_REQUEST);
                }
            }else{
                return new ApiResponseDto(false, "Footer not found", HttpStatusCode::NOT_FOUND);
            }

        }catch(\Exception $e){
            return new ApiResponseDto(false, 'server error ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);

        }
    }
    // Implement your service methods here
}
