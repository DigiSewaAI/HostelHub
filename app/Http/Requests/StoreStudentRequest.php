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
        return false;
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
        'email' => 'required|email|unique:students',
        'phone' => 'required|string|max:15',
        'room_id' => 'required|exists:rooms,id',
        'guardian_name' => 'required|string|max:255',
        'guardian_phone' => 'required|string|max:15',
        'status' => 'required|in:active,inactive'
    ];
}
}
