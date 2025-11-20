<?php

namespace App\Http\Requests\Reference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'company' => [
                'nullable',
                'string',
                'max:255',
            ],
            'position' => [
                'nullable',
                'string',
                'max:100',
            ],
            'quote' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'image' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required',
            'full_name.min' => 'Full name must be at least 2 characters',
            'full_name.max' => 'Full name cannot exceed 100 characters',
            'email.email' => 'Invalid email format',
            'email.max' => 'Email cannot exceed 255 characters',
            'phone.max' => 'Phone cannot exceed 50 characters',
            'company.max' => 'Company name cannot exceed 255 characters',
            'position.max' => 'Position cannot exceed 100 characters',
            'quote.max' => 'Quote cannot exceed 1000 characters',
            'image.max' => 'Image path cannot exceed 255 characters',
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

