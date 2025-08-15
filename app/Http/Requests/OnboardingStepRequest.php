<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnboardingStepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $step = $this->route('step');

        switch ($step) {
            case 1:
                return [
                    'name' => 'required|string|max:255',
                    'city' => 'required|string|max:255',
                    'address' => 'required|string|max:500',
                    'contact_phone' => 'required|string|max:20',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

            case 2:
                return [
                    'hostel_name' => 'required|string|max:255',
                    'hostel_city' => 'required|string|max:255',
                    'hostel_address' => 'required|string|max:500',
                    'contact_phone' => 'required|string|max:20',
                    'images' => 'nullable|array|max:5',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

            case 3:
                return [
                    'room_type' => 'required|string|max:50',
                    'capacity' => 'required|integer|min:1|max:10',
                    'price' => 'required|numeric|min:0',
                    'room_count' => 'required|integer|min:1|max:50',
                    'room_prefix' => 'required|string|max:10',
                    'amenities' => 'nullable|array',
                    'description' => 'nullable|string|max:1000',
                    'room_description' => 'nullable|string|max:1000',
                ];

            case 4:
                return [
                    'monthly_fee' => 'required|numeric|min:0',
                    'deposit' => 'required|numeric|min:0',
                    'meal_plan' => 'nullable|boolean',
                    'meal_price' => 'nullable|numeric|min:0|required_if:meal_plan,1',
                    'payment_methods' => 'nullable|array',
                    'late_fee' => 'nullable|numeric|min:0',
                    'late_fee_grace_days' => 'nullable|integer|min:0',
                    'invoice_template' => 'nullable|string|max:5000',
                ];

            case 5:
                return [
                    'student_name' => 'required|string|max:255',
                    'student_email' => 'required|email|max:255',
                    'student_phone' => 'required|string|max:20',
                    'student_address' => 'nullable|string|max:500',
                    'college' => 'nullable|string|max:255',
                    'course' => 'nullable|string|max:255',
                    'check_in' => 'required|date',
                    'check_out' => 'required|date|after:check_in',
                    'staff_name' => 'nullable|string|max:255',
                    'staff_email' => 'nullable|email|max:255|required_with:staff_name',
                    'staff_phone' => 'nullable|string|max:20|required_with:staff_name',
                    'staff_password' => 'nullable|string|min:8|required_with:staff_name',
                ];

            default:
                return [];
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'होस्टल/संस्थाको नाम आवश्यक छ',
            'city.required' => 'शहर आवश्यक छ',
            'address.required' => 'ठेगाना आवश्यक छ',
            'contact_phone.required' => 'सम्पर्क फोन नम्बर आवश्यक छ',
            'hostel_name.required' => 'होस्टलको नाम आवश्यक छ',
            'hostel_city.required' => 'होस्टलको शहर आवश्यक छ',
            'hostel_address.required' => 'होस्टलको ठेगाना आवश्यक छ',
            'room_type.required' => 'कोठा प्रकार आवश्यक छ',
            'capacity.required' => 'क्षमता आवश्यक छ',
            'capacity.min' => 'क्षमता १ भन्दा कम हुन सक्दैन',
            'price.required' => 'मूल्य आवश्यक छ',
            'price.min' => 'मूल्य ० भन्दा कम हुन सक्दैन',
            'room_count.required' => 'कोठाको संख्या आवश्यक छ',
            'room_count.min' => 'कम्तिमा १ कोठा हुनुपर्छ',
            'room_prefix.required' => 'कोठा उपसर्ग आवश्यक छ',
            'monthly_fee.required' => 'मासिक शुल्क आवश्यक छ',
            'deposit.required' => 'जम्मा रकम आवश्यक छ',
            'meal_price.required_if' => 'भोजन योजनाको लागि मूल्य आवश्यक छ',
            'student_name.required' => 'विद्यार्थीको नाम आवश्यक छ',
            'student_email.required' => 'इमेल ठेगाना आवश्यक छ',
            'student_phone.required' => 'फोन नम्बर आवश्यक छ',
            'check_in.required' => 'चेक-इन मिति आवश्यक छ',
            'check_out.required' => 'चेक-आउट मिति आवश्यक छ',
            'check_out.after' => 'चेक-आउट मिति चेक-इन मितिभन्दा पछि हुनुपर्छ',
            'staff_password.required_with' => 'स्टाफ पासवर्ड आवश्यक छ',
            'staff_password.min' => 'पासवर्ड कम्तिमा ८ वर्ण हुनुपर्छ',
        ];
    }
}
