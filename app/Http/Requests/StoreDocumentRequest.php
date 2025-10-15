<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('hostel_manager');
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'document_type' => 'required|in:id_card,academic,medical,financial,contract,other',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'description' => 'nullable|string|max:500',
            'expiry_date' => 'nullable|date|after:today'
        ];
    }

    public function messages()
    {
        return [
            'student_id.required' => 'कृपया विद्यार्थी चयन गर्नुहोस्',
            'document_type.required' => 'कृपया कागजातको प्रकार चयन गर्नुहोस्',
            'document.required' => 'कृपया कागजात छनौट गर्नुहोस्',
            'document.mimes' => 'कागजात PDF, JPG, PNG, DOC, DOCX मात्र हुन सक्छ',
            'document.max' => 'कागजात 10MB भन्दा ठूलो हुनु हुँदैन',
            'expiry_date.after' => 'म्याद नाघेको मिति हुनु हुँदैन'
        ];
    }
}
