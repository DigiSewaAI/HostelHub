<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCircularRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // प्राधिकरण policy मा ह्यान्डल गरिनेछ
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $audienceType = $this->input('audience_type', $this->route('circular')?->audience_type);

        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:urgent,normal,info',
            'audience_type' => 'sometimes|required|in:all_students,all_managers,all_users,organization_students,organization_managers,organization_users,specific_hostel,specific_students',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after:scheduled_at',
            'status' => 'sometimes|in:draft,published,archived',
            'delivery_channels' => 'nullable|array',
        ];

        // Admin can select any organization
        if ($isAdmin) {
            $rules['organization_id'] = 'sometimes|nullable|exists:organizations,id';
        }

        // Dynamic validation for target_audience based on audience_type
        if ($audienceType === 'specific_hostel') {
            $rules['target_audience'] = 'sometimes|required|array';
            $rules['target_audience.*'] = 'integer|exists:hostels,id';
        } elseif ($audienceType === 'specific_students') {
            $rules['target_audience'] = 'sometimes|required|array';
            $rules['target_audience.*'] = 'integer|exists:users,id';
        } else {
            $rules['target_audience'] = 'sometimes|nullable|array';
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

            // Skip organization validation for admin users
            if ($user->hasRole('admin')) {
                return;
            }

            // For non-admin users (owner/manager)
            $orgId = $user->organizations()->first()?->id;

            if (!$orgId) {
                $validator->errors()->add('organization', 'तपाईं कुनै संगठनसँग सम्बद्ध हुनुहुन्न।');
                return;
            }

            // Check if audience_type is being updated
            $audienceType = $this->audience_type ?? $this->route('circular')?->audience_type;
            $targetAudience = $this->target_audience ?? [];

            // Validate specific_hostel targets belong to user's organization
            if ($audienceType === 'specific_hostel' && !empty($targetAudience)) {
                $invalid = \App\Models\Hostel::whereIn('id', $targetAudience)
                    ->where('organization_id', '!=', $orgId)
                    ->exists();
                if ($invalid) {
                    $validator->errors()->add('target_audience', 'केही होस्टल तपाईंको संगठनसँग सम्बन्धित छैनन्।');
                }
            }

            // Validate specific_students targets belong to user's organization
            if ($audienceType === 'specific_students' && !empty($targetAudience)) {
                $invalid = \App\Models\Student::whereIn('user_id', $targetAudience)
                    ->where('organization_id', '!=', $orgId)
                    ->exists();
                if ($invalid) {
                    $validator->errors()->add('target_audience', 'केही विद्यार्थी तपाईंको संगठनसँग सम्बन्धित छैनन्।');
                }
            }

            // Validate that the circular belongs to user's organization (if organization_id is provided)
            if ($this->has('organization_id') && $this->organization_id != $orgId) {
                $validator->errors()->add('organization_id', 'तपाईंले आफ्नो संगठन मात्र चयन गर्न सक्नुहुन्छ।');
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
            'target_audience.*.exists' => 'चयन गरिएको प्रयोगकर्ता/होस्टल मान्य छैन।',
            'scheduled_at.date' => 'तालिकाबद्ध मिति मान्य मिति हुनुपर्छ।',
            'scheduled_at.after_or_equal' => 'तालिकाबद्ध मिति हालको मितिभन्दा पछि हुनुपर्छ।',
            'expires_at.date' => 'समाप्ति मिति मान्य मिति हुनुपर्छ।',
            'expires_at.after' => 'समाप्ति मिति तालिकाबद्ध मितिभन्दा पछि हुनुपर्छ।',
            'organization_id.exists' => 'चयन गरिएको संगठन मान्य छैन।',
            'status.in' => 'स्थिति draft, published, वा archived मात्र हुन सक्छ।',
            'delivery_channels.array' => 'डेलिभरी च्यानलहरू एरे हुनुपर्छ।',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove created_by from request if present (should not be updated)
        if ($this->has('created_by')) {
            $this->request->remove('created_by');
        }
    }
}
