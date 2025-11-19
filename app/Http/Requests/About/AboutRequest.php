<?php

namespace App\Http\Requests\About;

use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\PseudoTypes\True_;

class AboutRequest extends FormRequest
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
            'full_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'github' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'image' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required',
            'title.required' => 'Title is required',
            'summary.required' => 'Summary is required',
            'email.required' => 'Email is required',
            'phone.required' => 'Phone is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'country.required' => 'Country is required',
            'postal_code.required' => 'Postal code is required',
            'github.required' => 'Github is required',
            'linkedin.required' => 'Linkedin is required',
            'twitter.required' => 'Twitter is required',
            'image.required' => 'Image is required',
        ];
    }
}
