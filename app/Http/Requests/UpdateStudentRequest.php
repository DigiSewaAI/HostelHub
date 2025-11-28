<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ✅ EMERGENCY FIX: AUTHORIZATION BYPASS - return true instead of false
        \Log::info('UpdateStudentRequest: authorize called - EMERGENCY BYPASS: returning true');
        return true; // ✅ CHANGED FROM false TO true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->route('student')->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $studentId,
            'phone' => 'sometimes|required|string|max:15',
            'address' => 'sometimes|required|string|max:500',
            'guardian_name' => 'sometimes|required|string|max:255',
            'guardian_phone' => 'sometimes|required|string|max:15', // ✅ Form field: guardian_phone
            'guardian_address' => 'sometimes|required|string|max:500',
            'guardian_relation' => 'sometimes|required|string|max:50',
            'college_id' => 'sometimes|required',
            'other_college' => 'sometimes|required_if:college_id,others|string|max:255|nullable',
            'room_id' => 'sometimes|nullable|exists:rooms,id',
            'hostel_id' => 'sometimes|nullable|exists:hostels,id',
            'dob' => 'sometimes|nullable|date|before:today',
            'gender' => 'sometimes|nullable|in:male,female,other',
            'admission_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:pending,approved,active,inactive',
            'payment_status' => 'sometimes|required|in:pending,paid,unpaid',

            // ✅ FIXED: SIMPLIFIED user_id validation
            'user_id' => 'sometimes|nullable',

            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'note' => 'sometimes|nullable|string|max:1000',
            'organization_id' => 'sometimes|required|exists:organizations,id',
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
            'guardian_phone.required' => 'अभिभावकको फोन नम्बर आवश्यक छ।',
            'guardian_address.required' => 'अभिभावकको ठेगाना आवश्यक छ।',
            'guardian_relation.required' => 'अभिभावकसँगको नाता आवश्यक छ।',
            'college_id.required' => 'कृपया कलेज छान्नुहोस्।',
            'other_college.required_if' => 'कृपया कलेजको नाम लेख्नुहोस्।',
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
        // ✅ FIXED: Convert user_id=0 to null before validation
        if ($this->has('user_id') && $this->user_id == 0) {
            $this->merge([
                'user_id' => null
            ]);
        }

        // ✅ CRITICAL FIX: Map guardian_phone to guardian_contact for database
        if ($this->has('guardian_phone')) {
            $this->merge([
                'guardian_contact' => $this->guardian_phone
            ]);
        }

        // ✅ Ensure college_id is properly handled when "others" is selected
        if ($this->college_id === 'others' && $this->filled('other_college')) {
            $this->merge([
                'college_selection' => 'others'
            ]);
        }
    }

    /**
     * ✅ DEBUGGING: Log validation errors
     */
    protected function failedValidation(Validator $validator)
    {
        \Log::error('UpdateStudentRequest VALIDATION FAILED:', [
            'errors' => $validator->errors()->toArray(),
            'input_data' => $this->all()
        ]);

        parent::failedValidation($validator);
    }
}
