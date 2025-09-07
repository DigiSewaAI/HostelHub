<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:50',
            'type' => 'required|in:स्ट्यान्डर्ड,डीलक्स,विआईपी',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:उपलब्ध,बुक भएको,रिङ्गोट',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'floor' => 'nullable|string|max:10',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'hostel_id.required' => 'होस्टल अनिवार्य छ।',
            'room_number.required' => 'कोठा नम्बर अनिवार्य छ।',
            'type.required' => 'कोठाको प्रकार अनिवार्य छ।',
            'type.in' => 'अमान्य कोठा प्रकार चयन गरिएको छ।',
            'capacity.required' => 'क्षमता अनिवार्य छ।',
            'capacity.min' => 'क्षमता कम्तिमा १ हुनुपर्छ।',
            'price.required' => 'मूल्य अनिवार्य छ।',
            'price.min' => 'मूल्य कम्तिमा ० हुनुपर्छ।',
            'status.required' => 'स्थिति अनिवार्य छ।',
            'status.in' => 'अमान्य स्थिति चयन गरिएको छ।',
            'image.image' => 'तस्वीर मात्र अपलोड गर्न सकिन्छ।',
            'image.max' => 'तस्वीरको आकार २MB भन्दा कम हुनुपर्छ।',
        ];
    }
}
