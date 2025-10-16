<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCircularRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
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

    public function messages()
    {
        return [
            'title.required' => 'शीर्षक आवश्यक छ',
            'title.max' => 'शीर्षक २५५ अक्षरभन्दा लामो हुनु हुँदैन',
            'content.required' => 'सूचनाको विवरण आवश्यक छ',
            'audience_type.required' => 'लक्षित प्रयोगकर्ता चयन गर्नुहोस्',
            'target_audience.required' => 'कृपया लक्षित प्रयोगकर्ता चयन गर्नुहोस्',
            'scheduled_at.after_or_equal' => 'तोकिएको मिति हालको मितिभन्दा पछि हुनुपर्छ',
            'expires_at.after' => 'समाप्ति मिति प्रकाशन मितिभन्दा पछि हुनुपर्छ',
        ];
    }
}
