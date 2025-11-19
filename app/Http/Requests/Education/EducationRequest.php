<?php

namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EducationRequest extends FormRequest
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
        $currentYear = (int) date('Y');
        $maxYear = $currentYear + 10;

        return [
            'school' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'degree' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'field_of_study' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
            'start_year' => [
                'nullable',
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
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'school.required' => 'School name is required',
            'school.min' => 'School name must be at least 2 characters',
            'school.max' => 'School name cannot exceed 255 characters',
            'degree.required' => 'Degree is required',
            'degree.min' => 'Degree must be at least 2 characters',
            'degree.max' => 'Degree cannot exceed 100 characters',
            'field_of_study.min' => 'Field of study must be at least 2 characters',
            'field_of_study.max' => 'Field of study cannot exceed 255 characters',
            'start_year.integer' => 'Start year must be a valid year',
            'start_year.min' => 'Start year must be at least 1900',
            'start_year.max' => 'Start year cannot be more than 10 years in the future',
            'end_year.integer' => 'End year must be a valid year',
            'end_year.min' => 'End year must be at least 1900',
            'end_year.max' => 'End year cannot be more than 10 years in the future',
            'end_year.gte' => 'End year must be greater than or equal to start year',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
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

