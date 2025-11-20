<?php

namespace App\Http\Requests\Hobby;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HobbyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'image' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Hobby name is required',
            'name.min' => 'Hobby name must be at least 2 characters',
            'name.max' => 'Hobby name cannot exceed 100 characters',
            'image.required' => 'Hobby image is required',
            'image.max' => 'Image path cannot exceed 255 characters',
            'description.max' => 'Description cannot exceed 1000 characters',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}

