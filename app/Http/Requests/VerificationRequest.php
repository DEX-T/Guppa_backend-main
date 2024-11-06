<?php

namespace App\Http\Requests;

use App\Domain\DTOs\ApiResponseDto;
use App\enums\HttpStatusCode;
use App\Helpers\UserRoleHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return UserRoleHelper::isClient($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type' => 'required|string',
            'government_id' => 'required|string',
            'selfie_with_id' => 'required|string',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'current_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'nationality' => 'required|string|max:100',
            'id_document_number' => 'required|string|max:100',
        ];
    }



    protected  function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $responseDto = new ApiResponseDto(false, "validation error", HttpStatusCode::VALIDATION_ERROR, $errors);
        throw new HttpResponseException(
            response()->json($responseDto, $responseDto->code)
        );
    }
}
