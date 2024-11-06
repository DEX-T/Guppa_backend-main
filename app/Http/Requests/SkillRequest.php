<?php

namespace App\Http\Requests;

use App\Domain\DTOs\ApiResponseDto;
use App\enums\HttpStatusCode;
use App\Helpers\UserRoleHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return UserRoleHelper::IsAdmin($this->user()) || UserRoleHelper::isSuperAdmin($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'skill_id' => 'integer',
            'skill' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id'
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
