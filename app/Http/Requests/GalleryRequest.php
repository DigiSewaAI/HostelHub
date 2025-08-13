<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:photo,local_video,youtube',
            'file' => 'required_if:type,photo,local_video|file|mimes:jpg,jpeg,png,mp4|max:20480',
            'external_link' => 'required_if:type,youtube|url',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'file.required_if' => 'The file field is required for photos and local videos',
            'external_link.required_if' => 'YouTube URL is required for youtube type'
        ];
    }
}
