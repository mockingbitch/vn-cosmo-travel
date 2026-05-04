<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialsSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'testimonials' => ['nullable', 'array'],
            'testimonials.title' => ['nullable', 'string', 'max:255'],
            'testimonials.subtitle' => ['nullable', 'string', 'max:500'],
            'testimonials.items' => ['nullable', 'array'],
            'testimonials.items.*.quote' => ['nullable', 'string', 'max:2000'],
            'testimonials.items.*.author' => ['nullable', 'string', 'max:255'],
            'testimonials.items.*.meta' => ['nullable', 'string', 'max:255'],
            'testimonials.items.*.image_url' => ['nullable', 'string', 'max:2048'],
            'testimonials.items.*.scene_alt' => ['nullable', 'string', 'max:500'],
        ];
    }
}
