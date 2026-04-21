<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:255'],
            'title_vi' => ['required', 'string', 'max:255'],
            'subtitle_en' => ['nullable', 'string', 'max:255'],
            'subtitle_vi' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'cta_text_en' => ['nullable', 'string', 'max:50'],
            'cta_text_vi' => ['nullable', 'string', 'max:50'],
            'cta_link' => ['nullable', 'string', 'max:2048'],
        ];
    }
}

