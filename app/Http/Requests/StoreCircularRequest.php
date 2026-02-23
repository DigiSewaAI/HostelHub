<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCircularRequest extends FormRequest
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
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:urgent,normal,info',
            'audience_type' => 'required|in:all_students,all_managers,all_users,organization_students,organization_managers,organization_users,specific_hostel,specific_students',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after:scheduled_at',
        ];

        // Admin can select any organization
        if ($isAdmin) {
            $rules['organization_id'] = 'nullable|exists:organizations,id';
        }

        // Specific audience validation
        if (in_array($this->audience_type, ['specific_hostel', 'specific_students'])) {
            $rules['target_audience'] = 'required|array';
            $rules['target_audience.*'] = 'integer';
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            $audienceType = $this->audience_type;
            $targetAudience = $this->target_audience ?? [];

            // Check if target audience is empty for specific types
            if (in_array($audienceType, ['specific_hostel', 'specific_students']) && empty($targetAudience)) {
                $validator->errors()->add('target_audience', 'कृपया कम्तीमा एक प्रयोगकर्ता चयन गर्नुहोस्।');
            }

            // Additional validation for non-admin users (owner/manager)
            if (!$user->hasRole('admin')) {
                $orgId = $user->organizations()->first()?->id;

                if (!$orgId) {
                    $validator->errors()->add('organization', 'तपाईं कुनै संगठनसँग सम्बद्ध हुनुहुन्न।');
                    return;
                }

                if ($audienceType === 'specific_hostel' && !empty($targetAudience)) {
                    $invalid = \App\Models\Hostel::whereIn('id', $targetAudience)
                        ->where('organization_id', '!=', $orgId)
                        ->exists();
                    if ($invalid) {
                        $validator->errors()->add('target_audience', 'केही होस्टल तपाईंको संगठनसँग सम्बन्धित छैनन्।');
                    }
                }

                if ($audienceType === 'specific_students' && !empty($targetAudience)) {
                    $invalid = \App\Models\Student::whereIn('user_id', $targetAudience)
                        ->where('organization_id', '!=', $orgId)
                        ->exists();
                    if ($invalid) {
                        $validator->errors()->add('target_audience', 'केही विद्यार्थी तपाईंको संगठनसँग सम्बन्धित छैनन्।');
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'शीर्षक आवश्यक छ।',
            'title.max' => 'शीर्षक २५५ अक्षरभन्दा लामो हुनु हुँदैन।',
            'content.required' => 'सूचनाको विवरण आवश्यक छ।',
            'priority.required' => 'प्राथमिकता चयन गर्नुहोस्।',
            'priority.in' => 'प्राथमिकता मान्य छैन।',
            'audience_type.required' => 'लक्षित प्रयोगकर्ता चयन गर्नुहोस्।',
            'audience_type.in' => 'लक्षित प्रयोगकर्ता प्रकार मान्य छैन।',
            'target_audience.required' => 'कृपया लक्षित प्रयोगकर्ता चयन गर्नुहोस्।',
            'target_audience.array' => 'लक्षित प्रयोगकर्ता एरे हुनुपर्छ।',
            'target_audience.*.integer' => 'प्रत्येक लक्षित प्रयोगकर्ता ID पूर्णांक हुनुपर्छ।',
            'scheduled_at.date' => 'तालिकाबद्ध मिति मान्य मिति हुनुपर्छ।',
            'scheduled_at.after_or_equal' => 'तालिकाबद्ध मिति हालको मितिभन्दा पछि हुनुपर्छ।',
            'expires_at.date' => 'समाप्ति मिति मान्य मिति हुनुपर्छ।',
            'expires_at.after' => 'समाप्ति मिति तालिकाबद्ध मितिभन्दा पछि हुनुपर्छ।',
            'organization_id.exists' => 'चयन गरिएको संगठन मान्य छैन।',
        ];
    }
}
