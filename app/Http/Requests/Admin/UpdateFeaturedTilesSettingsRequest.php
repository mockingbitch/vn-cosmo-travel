<?php

namespace App\Http\Requests\Admin;

use App\Services\FeaturedTilesService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturedTilesSettingsRequest extends FormRequest
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
            'featured_tiles' => ['nullable', 'array'],
            'featured_tiles.*.eyebrow' => ['nullable', 'string', 'max:60'],
            'featured_tiles.*.title' => ['nullable', 'string', 'max:120'],
            'featured_tiles.*.chips' => ['nullable', 'string', 'max:400'],
            'featured_tiles.*.description' => ['nullable', 'string', 'max:500'],
            'featured_tiles.*.cta_label' => ['nullable', 'string', 'max:60'],
            'featured_tiles.*.image_url' => ['nullable', 'string', 'max:2048'],
            'featured_tiles.*.tour_ids' => ['nullable', 'array', 'max:'.FeaturedTilesService::MAX_TOURS_PER_TILE],
            'featured_tiles.*.tour_ids.*' => ['nullable', 'integer', 'exists:tours,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'featured_tiles.*.tour_ids.*.exists' => __('validation.featured_tiles.tour_missing'),
            'featured_tiles.*.tour_ids.*.integer' => __('validation.featured_tiles.tour_missing'),
            'featured_tiles.*.tour_ids.max' => __('validation.featured_tiles.tour_max', ['max' => FeaturedTilesService::MAX_TOURS_PER_TILE]),
        ];
    }
}
