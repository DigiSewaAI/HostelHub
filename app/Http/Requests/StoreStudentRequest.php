<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'guardian_phone' => 'required|string|max:15', // ✅ FIXED: Changed from guardian_contact
            'guardian_address' => 'required|string|max:500',
            'guardian_relation' => 'required|string|max:50',

            'college_id' => 'required', // ✅ Changed from nullable to required
            'other_college' => 'required_if:college_id,others|string|max:255|nullable', // ✅ Added for "Other" college option
            'room_id' => 'nullable|exists:rooms,id',      // ✅ nullable: room later assign गर्न सकिन्छ
            'hostel_id' => 'nullable|exists:hostels,id',  // ✅ optional, room बाट auto set हुन सक्छ

            'dob' => 'nullable|date|before:today',       // ✅ valid date of birth
            'gender' => 'nullable|in:male,female,other',
            'admission_date' => 'required|date',
            'status' => 'required|in:pending,approved,active,inactive',
            'payment_status' => 'required|in:pending,paid,unpaid', // ✅ Added 'unpaid' option
            'user_id' => 'nullable|exists:users,id|unique:students,user_id', // ✅ if linking to user
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',    // 2MB max

            // ✅ Additional fields if needed
            'note' => 'nullable|string|max:1000',
            'organization_id' => 'required|exists:organizations,id', // ✅ Added organization validation
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
            'guardian_phone.required' => 'अभिभावकको फोन नम्बर आवश्यक छ।', // ✅ FIXED: Added guardian phone message
            'guardian_address.required' => 'अभिभावकको ठेगाना आवश्यक छ।',
            'guardian_relation.required' => 'अभिभावकसँगको नाता आवश्यक छ।',
            'college_id.required' => 'कृपया कलेज छान्नुहोस्।',
            'other_college.required_if' => 'कृपया कलेजको नाम लेख्नुहोस्।', // ✅ Added for other college
            'admission_date.required' => 'प्रवेश मिति आवश्यक छ।',
            'status.required' => 'स्थिति छान्नुहोस्।',
            'payment_status.required' => 'भुक्तानी स्थिति छान्नुहोस्।',
            'image.max' => 'छवि 2MB भन्दा ठूलो हुन सक्दैन।',
            'image.mimes' => 'केवल jpeg, png, jpg, webp प्रारूप समर्थित छन्।',
            'organization_id.required' => 'संस्था आईडी आवश्यक छ।',
            'organization_id.exists' => 'यो संस्था अमान्य छ।',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // ✅ Ensure college_id is properly handled when "others" is selected
        if ($this->college_id === 'others' && $this->filled('other_college')) {
            // This will be handled in the controller to create new college
            $this->merge([
                'college_selection' => 'others'
            ]);
        }

        // ✅ Ensure guardian_phone is properly mapped (if needed)
        // If the form field is guardian_phone but database expects guardian_contact,
        // this will be handled in the controller
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'नाम',
            'email' => 'इमेल',
            'phone' => 'फोन',
            'address' => 'ठेगाना',
            'guardian_name' => 'अभिभावकको नाम',
            'guardian_phone' => 'अभिभावकको फोन', // ✅ FIXED: Added attribute
            'guardian_address' => 'अभिभावकको ठेगाना',
            'guardian_relation' => 'अभिभावकसँगको नाता',
            'college_id' => 'कलेज',
            'other_college' => 'अन्य कलेज',
            'dob' => 'जन्म मिति',
            'gender' => 'लिङ्ग',
            'admission_date' => 'भर्ना मिति',
            'status' => 'स्थिति',
            'payment_status' => 'भुक्तानी स्थिति',
        ];
    }
}
