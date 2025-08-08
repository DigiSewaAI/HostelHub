<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:hostels,name',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'contact_person' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'required|email',
            'description' => 'nullable|string',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'facilities' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,under_maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'यो होस्टलको नाम पहिले नै रेजिष्टर्ड छ',
            'image.max' => 'तस्वीर २MB भन्दा ठूलो हुनुहुदैन'
        ];
    }
}
