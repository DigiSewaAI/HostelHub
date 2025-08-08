<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'content' => 'required|string',
            'initials' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:testimonial,review,feedback',
            'status' => 'required|in:active,inactive',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'कृपया नाम प्रविष्ट गर्नुहोस्',
            'position.required' => 'कृपया पद प्रविष्ट गर्नुहोस्',
            'content.required' => 'कृपया समीक्षा सामग्री प्रविष्ट गर्नुहोस्',
            'type.required' => 'कृपया समीक्षा प्रकार छान्नुहोस्',
        ];
    }
}
