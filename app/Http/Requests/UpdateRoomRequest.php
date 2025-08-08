<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roomId = $this->route('room'); // URL बाट कोठा ID लिने

        return [
            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms')->ignore($roomId)
            ],
            'type' => 'required|in:single,double,shared,dormitory',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'hostel_id' => 'required|exists:hostels,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'room_number.unique' => 'यो कोठा नम्बर पहिले नै रेजिष्टर्ड छ',
            'type.in' => 'अमान्य कोठाको प्रकार (single, double, shared, dormitory मध्ये छान्नुहोस्)',
            'image.max' => 'तस्बिर २MB भन्दा ठूलो हुनुहुदैन'
        ];
    }
}
