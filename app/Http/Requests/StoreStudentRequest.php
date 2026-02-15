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
        // Base rules that apply to everyone
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        return;
                    }

                    $existingStudent = \App\Models\Student::where('email', $value)->first();

                    if ($existingStudent) {
                        $currentHostelId = auth()->user()->hostel_id;

                        // Case 1: Same hostel duplicate
                        if ($existingStudent->hostel_id == $currentHostelId) {
                            $fail('यो इमेल पहिले नै तपाईंको होस्टलमा दर्ता गरिएको छ।');
                            return;
                        }

                        // Case 2: Active in another hostel
                        if (
                            in_array($existingStudent->status, ['active', 'approved']) &&
                            $existingStudent->hostel_id != $currentHostelId
                        ) {
                            $fail('यो विद्यार्थी हाल अन्य होस्टलमा सक्रिय छन्।');
                            return;
                        }

                        // Case 3: Inactive in another hostel → ALLOW (transfer logic will handle)
                        // Do nothing, validation passes
                    }
                }
            ],
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:15',
            'guardian_address' => 'required|string|max:500',
            'guardian_relation' => 'required|string|max:50',
            'college_id' => 'required',
            'other_college' => 'required_if:college_id,others|string|max:255|nullable',
            'room_id' => 'nullable|exists:rooms,id',
            'hostel_id' => 'nullable|exists:hostels,id',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'admission_date' => 'required|date',
            'status' => 'required|in:pending,approved,active,inactive',
            'user_id' => 'nullable|exists:users,id|unique:students,user_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'note' => 'nullable|string|max:1000',
            'organization_id' => 'required|exists:organizations,id',
        ];

        // ✅ Role‑based handling for payment_status
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            // Admin: payment_status is required
            $rules['payment_status'] = 'required|in:pending,paid,unpaid';
        } else {
            // Owner / hostel_manager / others: payment_status is optional
            // (because they use the new initial payment section)
            $rules['payment_status'] = 'nullable|in:pending,paid,unpaid';
        }

        return $rules;
    }

    /**
     * Custom validation messages (optional - Nepali or English)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'कृपया विद्यार्थीको नाम दिनुहोस्।',
            'email.required' => 'इमेल आवश्यक छ।',
            'email.email' => 'कृपया वैध इमेल ठेगाना दिनुहोस्।',
            // Remove the unique email message since we're handling it in custom validation
            'phone.required' => 'फोन नम्बर आवश्यक छ।',
            // ... rest of messages remain the same
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // ✅ Ensure college_id is properly handled when "others" is selected
        if ($this->college_id === 'others' && $this->filled('other_college')) {
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
            'guardian_phone' => 'अभिभावकको फोन',
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
