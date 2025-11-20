<?php

namespace App\Http\Requests\WorkLife;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkLifeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = (int) date('Y');
        $maxYear = $currentYear + 1;

        return [
            'company_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'position' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'start_year' => [
                'required',
                'integer',
                'min:1900',
                "max:{$maxYear}",
            ],
            'end_year' => [
                'nullable',
                'integer',
                'min:1900',
                "max:{$maxYear}",
                'gte:start_year',
            ],
            'is_ongoing' => [
                'boolean',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required',
            'company_name.min' => 'Company name must be at least 2 characters',
            'company_name.max' => 'Company name cannot exceed 255 characters',
            'position.required' => 'Position is required',
            'position.min' => 'Position must be at least 2 characters',
            'position.max' => 'Position cannot exceed 100 characters',
            'start_year.required' => 'Start year is required',
            'start_year.integer' => 'Start year must be a valid year',
            'start_year.min' => 'Start year must be at least 1900',
            'start_year.max' => 'Start year cannot be in the future',
            'end_year.integer' => 'End year must be a valid year',
            'end_year.min' => 'End year must be at least 1900',
            'end_year.max' => 'End year cannot be in the future',
            'end_year.gte' => 'End year must be greater than or equal to start year',
            'is_ongoing.boolean' => 'Is ongoing must be true or false',
            'description.max' => 'Description cannot exceed 2000 characters',
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

