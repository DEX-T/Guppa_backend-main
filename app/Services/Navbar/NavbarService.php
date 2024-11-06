<?php

namespace App\Services\Navbar;

use App\Models\Navbar;
use App\Models\NavbarLogo;
use App\Models\NavbarMenu;
use App\Models\NavbarText;
use App\Models\NavbarButton;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\Navbar\NavbarLogoEntity;
use App\Domain\Entities\Navbar\NavbarMenuEntity;
use App\Domain\Entities\Navbar\NavbarTextEntity;
use App\Domain\Entities\Navbar\NavbarTypeEntity;
use App\Domain\Interfaces\Navbar\INavbarService;
use App\Domain\Entities\Navbar\NavbarButtonEntity;
use App\Domain\DTOs\Request\Navbar\UpdateNavbarTypeDto;
use App\Domain\DTOs\Response\Navbar\FullNavsResponseDto;
use App\Domain\DTOs\Response\Navbar\NavbarLogoResponseDto;
use App\Domain\DTOs\Response\Navbar\NavbarMenuResponseDto;
use App\Domain\DTOs\Response\Navbar\NavbarTextResponseDto;
use App\Domain\DTOs\Response\Navbar\NavbarTypeResponseDto;
use App\Domain\DTOs\Response\Navbar\NavbarButtonResponseDto;
use App\Domain\DTOs\Request\Navbar\CreateNavbarLogoRequestDto;
use App\Domain\DTOs\Request\Navbar\CreateNavbarMenuRequestDto;
use App\Domain\DTOs\Request\Navbar\CreateNavbarTextRequestDto;
use App\Domain\DTOs\Request\Navbar\CreateNavbarTypeRequestDto;
use App\Domain\DTOs\Request\Navbar\UpdateNavbarMenuRequestDto;
use App\Domain\DTOs\Request\Navbar\UpdateNavbarTextRequestDto;
use App\Domain\DTOs\Request\Navbar\CreateNavbarButtonRequestDto;
use App\Domain\DTOs\Request\Navbar\UpdateNavbarButtonRequestDto;
use App\Domain\DTOs\Response\Navbar\NavbarBannerTextResponseDto;

class NavbarService implements INavbarService
{
    // Implement your service methods here
    
    public function getFullNavs()
    {
        try {
            $navBars = Navbar::with(['nav_menus', 'nav_buttons', 'nav_logo', 'nav_texts'])->where('type', 'TopNav')->get();
            
            if ($navBars->isEmpty()) {
                return new ApiResponseDto(true, "No navbar  found", HttpStatusCode::NO_CONTENT);
            }

            $navbarTypeEntities = $navBars->map(function ($navbar) {
                $entity = new NavbarTypeEntity($navbar);
                return  new FullNavsResponseDto($entity);
            })->toArray();

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $navbarTypeEntities);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createNavType(Request $request)
    {
        try {
            $user = Auth::user();
           
            // Gate::authorize('create', $user);
            if (Gate::denies('create_navbar', $user)) {
                return new ApiResponseDto(false, "You don't have permission to create a navbar.", HttpStatusCode::UNAUTHORIZED);
               
            }

            $validator = Validator::make($request->all(), [
                'type' => ['required', 'string', 'max:255', 'unique:' . Navbar::class],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }



            $validated = $validator->validated();
            $dto = new CreateNavbarTypeRequestDto($validated['type']);
            $dto->type = ucwords($dto->type);
            $type = str_replace(" ", '', $dto->type);
            //check if type exist
            $navbar = Navbar::where('type', $type)->first();
            if ($navbar) {
                return new ApiResponseDto(false, "Navbar type already exist", HttpStatusCode::BAD_REQUEST);
            }else{
                $navbar = new Navbar();
                $navbar->type = $type;
                if ($navbar->save()) {
                    return new ApiResponseDto(true, "Navbar type created successfully", HttpStatusCode::CREATED);
                } else {
                    return new ApiResponseDto(false, "Error creating navbar type", HttpStatusCode::BAD_REQUEST);
                }
            }   
           
         
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllNavType()
    {
        try {
          
            $navBars = Navbar::get();
             // Gate::authorize('create', $user);
            Log::info("navbar types ", [$navBars]);
            if ($navBars->isEmpty()) {
                return new ApiResponseDto(false, "No navbar types found", HttpStatusCode::NOT_FOUND);
            }

            $navbarTypeEntities = $navBars->map(function ($navbar) {
                $entity = new NavbarTypeEntity($navbar);
                Log::info("navbar entity ", [$navbar]);
                return  new NavbarTypeResponseDto($entity);
            })->toArray();

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $navbarTypeEntities);
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getNavTypeById(int $id)
    {
        try {
            $navbar = Navbar::with(['nav_menus', 'nav_buttons', 'nav_logo', 'nav_text'])->where('id', $id)->first();

            if (!$navbar) {
                return new ApiResponseDto(false, "Navbar type not found", HttpStatusCode::NOT_FOUND);
            }

            $navbarTypeEntity = new NavbarTypeEntity($navbar);
            $dto = new NavbarTypeResponseDto($navbarTypeEntity);

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateNavType(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer', 'exists:navbars'],
                'type' => ['required', 'string', 'max:255', 'unique:navbars,type,' . $request->id],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $navbar = Navbar::findOrFail($validated['id']);

            $user = auth()->user();
            if (Gate::denies('update_navbar', $user, $navbar)) {
               return new ApiResponseDto(false, "You don't have permission to update a navbar.", HttpStatusCode::UNAUTHORIZED);
              
            }
            if (!$navbar) {
                return new ApiResponseDto(false, "Navbar type not found", HttpStatusCode::NOT_FOUND);
            }

            $dto = new UpdateNavbarTypeDto($validated['type'], $validated['id']);
            $navbar->type = $dto->type;
            if ($navbar->save()) {
                $navbarTypeEntity = new NavbarTypeEntity($navbar);
                $navbarTypeResponseDto = new NavbarTypeResponseDto($navbarTypeEntity);
                return new ApiResponseDto(true, "Navbar type updated successfully", HttpStatusCode::OK, $navbarTypeResponseDto->toArray());
            } else {
                return new ApiResponseDto(false, "Error updating navbar type", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteNavType(int $id)
    {
        try {
            $navbar = Navbar::findOrFail($id);
            $user = auth()->user();
            if (Gate::denies('delete_navbar', $user, $navbar)) {
               return new ApiResponseDto(false, "You don't have permission to delete a navbar.", HttpStatusCode::UNAUTHORIZED);
              
           }
            if ($navbar) {
                $navbar->delete();
                return new ApiResponseDto(true, "Navbar type deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Navbar type not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function createNavMenu(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'navbar_id' => ['required', 'integer', 'exists:navbars,id'],
                'menu_text' => ['required', 'string', 'max:255'],
                'menu_link' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $dto = new CreateNavbarMenuRequestDto($validated['navbar_id'], $validated['menu_text'], $validated['menu_link']);
            $navbarMenu = new NavbarMenu();
            $navbarMenu->navbar_id = $dto->navbar_id;
            $navbarMenu->menu_text = $dto->menu_text;
            $navbarMenu->menu_link = $dto->menu_link;
            $navbarMenu->status = "active";
            if ($navbarMenu->save()) {
                //return response
                return new ApiResponseDto(true, "Navbar Menus created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Error creating navbar menu", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllNavMenu()
    {
        try {
            $navbarMenus = NavbarMenu::all();

            if ($navbarMenus->isEmpty()) {
                return new ApiResponseDto(false, "No navbar Menus found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $navbarMenus->map(function($menu){
                $navbarMenuEntity = new NavbarMenuEntity($menu);
                return new NavbarMenuResponseDto($navbarMenuEntity);
            });
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getNavMenuById(int $id)
    {
        try {
            $navbarMenu = NavbarMenu::findOrFail($id);

            if ($navbarMenu == null) {
                return new ApiResponseDto(false, "Navbar Menu not found", HttpStatusCode::NOT_FOUND);
            }
            $navbarMenuEntity = new NavbarMenuEntity($navbarMenu);
            $dto = new NavbarMenuResponseDto($navbarMenuEntity);
            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function updateNavMenu(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer', 'exists:navbar_menus,id'],
                'navbar_id' => ['required', 'integer', 'exists:navbars,id'],
                'menu_text' => ['required', 'string', 'max:255'],
                'menu_link' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateNavbarMenuRequestDto(
                $validatedData['id'],
                $validatedData['navbar_id'],
                $validatedData['menu_text'],
                $validatedData['menu_link'],
            );

            // 4. Find the NavbarMenu item by ID (from the DTO)
            $navbarMenu = NavbarMenu::findOrFail($dto->id);

            if (!$navbarMenu) {
                return new ApiResponseDto(false, "Navbar Menu not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the menu item
            $navbarMenu->update([
                'navbar_id' => $dto->id,
                'menu_text' => $dto->menu_text,
                'menu_link' => $dto->menu_link,
            ]);

            // 6. Prepare the response DTO
            $navbarMenuEntity = new NavbarMenuEntity($navbarMenu);
            $navbarMenuResponseDto = new NavbarMenuResponseDto($navbarMenuEntity);

            return new ApiResponseDto(true, "Navbar Menu updated successfully", HttpStatusCode::OK, $navbarMenuResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function deleteNavMenu(int $id)
    {
        try {
            $navbarMenu = NavbarMenu::findOrFail($id);
            if ($navbarMenu) {
                $navbarMenu->delete();
                return new ApiResponseDto(true, "Navbar Menu deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Navbar Menu not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function createNavText(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'navbar_id' => ['required', 'integer'],
                'text' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string']
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new CreateNavbarTextRequestDto($validated['navbar_id'], $validated['text'], $validated['description']);

            $navbar = Navbar::findOrFail($dto->navbar_id);
            if (!$navbar) {
                return new ApiResponseDto(false, "Navbar not found", HttpStatusCode::NOT_FOUND);
            }

            $navbarText = new NavbarText();
            $navbarText->navbar_id = $dto->navbar_id;
            $navbarText->text = $dto->text;
            $navbarText->description = $dto->description;
            $navbarText->status = "inactive";
        

            if ($navbarText->save()) {
                return new ApiResponseDto(true, "Navbar Text created successfully", HttpStatusCode::CREATED);
            } else {
                return new ApiResponseDto(false, "Error creating navbar Text", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllNavText()
    {
        try {
            $navbarTexts = NavbarText::all();

            if ($navbarTexts->isEmpty()) {
                return new ApiResponseDto(false, "No navbar Texts found", HttpStatusCode::NOT_FOUND);
            }

            $dto = $navbarTexts->map(function($text){
                $navbarTextEntity = new NavbarTextEntity($text);
                return new NavbarTextResponseDto($navbarTextEntity);
            });

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getBannerText()
    {
        try {
            $navbarText = NavbarText::where('status', 'active')->first();

            if (!$navbarText) {
                return new ApiResponseDto(false, "Navbar Text not found", HttpStatusCode::NOT_FOUND);
            }

            $navbarTextEntity = new NavbarTextEntity($navbarText);
            $dto = new NavbarBannerTextResponseDto($navbarTextEntity);

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function getNavTextById(int $id)
    {
        try {
            $navbarText = NavbarText::find($id);

            if (!$navbarText) {
                return new ApiResponseDto(false, "Navbar Text not found", HttpStatusCode::NOT_FOUND);
            }

            $navbarTextEntity = new NavbarTextEntity($navbarText);
            $dto = new NavbarTextResponseDto($navbarTextEntity);

            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateNavText(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'navbar_id' => ['required', 'integer'],
                'navbar_text_id' => ['required', 'integer'],
                'text' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }
            $validated = $validator->validated();
            $navbarText = NavbarText::findOrFail($validated['navbar_text_id']);

            if (!$navbarText) {
                return new ApiResponseDto(false, "Navbar Text not found", HttpStatusCode::NOT_FOUND);
            }

            $dto = new UpdateNavbarTextRequestDto($validated['navbar_text_id'], $validated['navbar_id'], $validated['text'], $validated['description']);
            

            $navbarText->navbar_id = $dto->navbar_id;
            $navbarText->text = $dto->text;
            $navbarText->description = $dto->description;

            if ($navbarText->save()) {
                $navbarTextEntity = new NavbarTextEntity($navbarText);
                $navbarTextResponseDto = new NavbarTextResponseDto($navbarTextEntity);

                return new ApiResponseDto(true, "Navbar Text updated successfully", HttpStatusCode::OK, $navbarTextResponseDto->toArray());
            } else {
                return new ApiResponseDto(false, "Error updating navbar Text", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function activateNavText(int $id)
    {
        try {
            $navbarText = NavbarText::findOrFail($id);

            if ($navbarText) {
                $navbarText->status = "active";
                $navbarText->save();
                $others = NavbarText::where('status', 'active')->where('id', '!=', $navbarText->id)->get();
                if($others->isNotEmpty()){
                    foreach($others as $other){
                        $other->status = "inactive";
                        $other->save();
                    }
                }
                return new ApiResponseDto(true, "The selected text is activated and others deactivated successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Navbar Text not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteNavText(int $id)
    {
        try {
            $navbarText = NavbarText::findOrFail($id);

            if ($navbarText) {
                if($navbarText->status == "active"){
                    return new ApiResponseDto(false, "Activate another Banner Text before deleting this one", HttpStatusCode::FORBIDDEN);
                }else{
                    $navbarText->delete();
                    return new ApiResponseDto(true, "Navbar Text deleted successfully", HttpStatusCode::OK);
                }
            } else {
                return new ApiResponseDto(false, "Navbar Text not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function createNavButton(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'navbar_id' => ['required', 'integer', 'exists:navbars,id'],
                'button_text' => ['required', 'string', 'max:255'],
                'button_link' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }


            $validateData = $validator->validated();
            $dto = new CreateNavbarButtonRequestDto($validateData['navbar_id'], $validateData['button_text'], $validateData['button_link']);
            $navbarButton = new NavbarButton();
            $navbarButton->navbar_id = $dto->navbar_id;
            $navbarButton->button_text = $dto->button_text;
            $navbarButton->button_link = $dto->button_link;
            if($navbarButton->save()){
                return new ApiResponseDto(true, "Navbar Buttons created successfully", HttpStatusCode::CREATED);
            }else{
                return new ApiResponseDto(false, "Error creating Navbar Buttons", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getNavButtonById(int $id)
    {
        try {
            $navbarButton = NavbarButton::findOrFail($id);

            if (!$navbarButton) {
                return new ApiResponseDto(false, "Navbar Button not found", HttpStatusCode::NOT_FOUND);
            }
            $navbarButtonEntity = new NavbarButtonEntity($navbarButton);
            $dto = new NavbarButtonResponseDto($navbarButtonEntity);


            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllNavButton()
    {
        try {
            $navbarButtons = NavbarButton::all();

            if ($navbarButtons->isEmpty()) {
                return new ApiResponseDto(false, "Navbar Buttons not found", HttpStatusCode::NOT_FOUND);
            }
            $dto = $navbarButtons->map(function($button) {
                $navbarButtonEntity = new NavbarButtonEntity($button);
                return new NavbarButtonResponseDto($navbarButtonEntity);
            });


            return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateNavButton(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer', 'exists:navbar_buttons,id'],
                'navbar_id' => ['required', 'integer', 'exists:navbars,id'],
                'button_text' => ['required', 'string', 'max:255'],
                'button_link' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validatedData = $validator->validated();

            $dto = new UpdateNavbarButtonRequestDto(
                $validatedData['id'],
                $validatedData['navbar_id'],
                $validatedData['button_text'],
                $validatedData['button_link'],
            );

            // 4. Find the NavbarButton item by ID (from the DTO)
            $navbarButton = NavbarButton::findOrFail($dto->id);

            if (!$navbarButton) {
                return new ApiResponseDto(false, "Navbar Button not found", HttpStatusCode::NOT_FOUND);
            }

            // 5. Update the Button item
            $navbarButton->update([
                'navbar_id' => $dto->navbar_id,
                'button_text' => $dto->button_text,
                'button_link' => $dto->button_link,
            ]);

            // 6. Prepare the response DTO
            $navbarButtonEntity = new NavbarButtonEntity($navbarButton);
            $navbarButtonResponseDto = new NavbarButtonResponseDto($navbarButtonEntity);

            return new ApiResponseDto(true, "Navbar Button updated successfully", HttpStatusCode::OK, $navbarButtonResponseDto->toArray());
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function deleteNavButton(int $id)
    {
        try {
            $navbarButton = NavbarButton::findOrFail($id);
            if ($navbarButton) {
                $navbarButton->delete();
                return new ApiResponseDto(true, "Navbar Button deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Navbar Button not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }



    public function upsertLogo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'navbar_id' => ['required', 'integer'],
                'logo_url' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validated = $validator->validated();
            $dto = new CreateNavbarLogoRequestDto($validated['navbar_id'], $validated['logo_url']);


            $navbar = Navbar::findOrFail($dto->navbar_id);
            if ($navbar == null) {
                return new ApiResponseDto(false, "Navbar not found", HttpStatusCode::NOT_FOUND);
            }

           
            $navbarLogo = NavbarLogo::where('id', 1)->first();
            if($navbarLogo == null){
                $navbarLogo = new NavbarLogo();
                $navbarLogo->id = 0;
                $navbarLogo->navbar_id = $dto->navbar_id;
                $navbarLogo->logo_url = $dto->logo_url;
                $message = "Navbar logo created successfully";
            }else{
                $navbarLogo->logo_url = $dto->logo_url;
                $message = "Navbar logo updated successfully";
            }
            if ($navbarLogo->save()) {
                return new ApiResponseDto(true, $message, HttpStatusCode::CREATED);
            } else {
                return new ApiResponseDto(false, "Error creating navbar logo", HttpStatusCode::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getNavLogo()
    {
        try {
            $navbarLogo = NavbarLogo::where('id', 1)->first();

          if ($navbarLogo == null) {
                return new ApiResponseDto(false, "Navbar logo not found", HttpStatusCode::NOT_FOUND);
            }else{
                $navbarButtonEntity = new NavbarLogoEntity($navbarLogo);
                $dto = new NavbarLogoResponseDto($navbarButtonEntity);
           
               return new ApiResponseDto(true, "Successful", HttpStatusCode::OK, $dto->toArray());

          }
           
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function deleteNavLogo(int $id)
    {
        try {
            $navbarLogo = NavbarLogo::findOrFail($id);

            if ($navbarLogo) {
                $navbarLogo->delete();
                return new ApiResponseDto(true, "Navbar logo deleted successfully", HttpStatusCode::OK);
            } else {
                return new ApiResponseDto(false, "Navbar logo not found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
