<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_vi' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:32', Rule::in(config('destination_regions.order', []))],
            'description' => ['nullable', 'string'],
        ];
    }
}
