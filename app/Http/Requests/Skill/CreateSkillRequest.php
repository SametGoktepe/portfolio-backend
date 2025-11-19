<?php

namespace App\Http\Requests\Skill;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateSkillRequest extends FormRequest
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
            'category_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'skills' => [
                'required',
                'array',
                'min:1',
            ],
            'skills.*' => [
                'required',
                'string',
                'min:1',
                'max:100',
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
            'category_name.required' => 'Category name is required',
            'category_name.string' => 'Category name must be a string',
            'category_name.min' => 'Category name must be at least 2 characters',
            'category_name.max' => 'Category name cannot exceed 50 characters',
            'skills.required' => 'Skills array is required',
            'skills.array' => 'Skills must be an array',
            'skills.min' => 'At least one skill is required',
            'skills.*.required' => 'Skill name is required',
            'skills.*.string' => 'Skill name must be a string',
            'skills.*.min' => 'Skill name must be at least 1 character',
            'skills.*.max' => 'Skill name cannot exceed 100 characters',
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

