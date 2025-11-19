<?php

namespace App\Http\Requests\Project;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*' => [
                'string',
                'url',
            ],
            'github_url' => [
                'nullable',
                'string',
                'url',
            ],
            'demo_link' => [
                'nullable',
                'string',
                'url',
            ],
            'technologies' => [
                'required',
                'array',
                'min:1',
            ],
            'technologies.*' => [
                'required',
                'string',
                'max:50',
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(['in_progress', 'completed', 'backlog', 'cancelled']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required',
            'title.min' => 'Project title must be at least 3 characters',
            'title.max' => 'Project title cannot exceed 255 characters',
            'description.required' => 'Project description is required',
            'description.min' => 'Project description must be at least 10 characters',
            'images.array' => 'Images must be an array',
            'images.*.url' => 'Each image must be a valid URL',
            'github_url.url' => 'GitHub URL must be a valid URL',
            'demo_link.url' => 'Demo link must be a valid URL',
            'technologies.required' => 'Technologies are required',
            'technologies.array' => 'Technologies must be an array',
            'technologies.min' => 'At least one technology is required',
            'technologies.*.required' => 'Technology name is required',
            'technologies.*.max' => 'Technology name cannot exceed 50 characters',
            'status.in' => 'Status must be one of: in_progress, completed, backlog, cancelled',
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

