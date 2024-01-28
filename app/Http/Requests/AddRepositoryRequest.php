<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AddRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'repository' => 'required|string|unique:repositories,name'
        ];
    }

    public function messages(): array
    {
        return [
            'repository.required' => 'The field cannot be empty',
            'repository.string' => 'Invalid data type when submitting the repository',
            'repository.unique' => 'Repository already exists in system'
        ];
    }

    protected function failedValidation(Validator $validator): ValidationException
    {
        throw new ValidationException($validator, response()->json([
            'message' => $validator->errors(),
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
