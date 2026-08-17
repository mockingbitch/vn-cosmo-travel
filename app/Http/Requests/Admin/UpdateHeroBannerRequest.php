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
            'subtitle_en' => ['nullable', 'string', 'max:255'],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
            'cta_text_en' => ['nullable', 'string', 'max:50'],
            'cta_link' => ['nullable', 'string', 'max:2048'],
        ];
    }
}

