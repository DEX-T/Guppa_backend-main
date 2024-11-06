<?php

namespace App\Http\Requests;

use App\Domain\DTOs\ApiResponseDto;
use App\enums\HttpStatusCode;
use App\Helpers\UserRoleHelper;
use App\Models\DocumentTypeConfiguration;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentTypeConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return UserRoleHelper::isAdmin($this->user()) || UserRoleHelper::isSuperAdmin($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:500', 'unique:'.DocumentTypeConfiguration::class],
            'description' => ['nullable','string', 'max:1000']
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $response = new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $errors);
        throw new HttpResponseException(
            response()->json($response, $response->code)
        );
    }
}
