<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ✅ अब user authorize गरिनेछ (middleware ले नै जाँच गर्छ)
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
            'email' => 'required|email|unique:students,email|max:255', // ✅ specify column
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:15',
            'guardian_address' => 'required|string|max:500',
            'guardian_relation' => 'required|string|max:50',

            'college_id' => 'nullable|exists:colleges,id', // ✅ if college table exists
            'room_id' => 'nullable|exists:rooms,id',      // ✅ nullable: room later assign गर्न सकिन्छ
            'hostel_id' => 'nullable|exists:hostels,id',  // ✅ optional, room बाट auto set हुन सक्छ

            'dob' => 'nullable|date|before:today',       // ✅ valid date of birth
            'gender' => 'nullable|in:male,female,other',
            'admission_date' => 'required|date',
            'status' => 'required|in:pending,approved,active,inactive',
            'payment_status' => 'required|in:pending,paid',
            'user_id' => 'nullable|exists:users,id|unique:students,user_id', // ✅ if linking to user
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',    // 2MB max

            // ✅ Additional fields if needed
            'note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom validation messages (optional - Nepali or English)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'कृपया विद्यार्थीको नाम दिनुहोस्।',
            'email.required' => 'इमेल आवश्यक छ।',
            'email.unique' => 'यो इमेल पहिले नै दर्ता गरिएको छ।',
            'phone.required' => 'फोन नम्बर आवश्यक छ।',
            'address.required' => 'ठेगाना आवश्यक छ।',
            'guardian_name.required' => 'अभिभावकको नाम आवश्यक छ।',
            'admission_date.required' => 'प्रवेश मिति आवश्यक छ।',
            'status.required' => 'स्थिति छान्नुहोस्।',
            'payment_status.required' => 'भुक्तानी स्थिति छान्नुहोस्।',
            'image.max' => 'छवि 2MB भन्दा ठूलो हुन सक्दैन।',
            'image.mimes' => 'केवल jpeg, png, jpg, webp प्रारूप समर्थित छन्।',
        ];
    }
}
