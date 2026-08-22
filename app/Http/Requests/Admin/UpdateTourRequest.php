<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\HandlesTourPriceRows;
use App\Models\Tour;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTourRequest extends FormRequest
{
    use HandlesTourPriceRows;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('thumbnail_media_id') === '') {
            $this->merge(['thumbnail_media_id' => null]);
        }
        $this->merge([
            'thumbnail' => $this->input('thumbnail') !== null
                ? trim((string) $this->input('thumbnail'))
                : '',
        ]);
        if (! $this->has('services')) {
            $this->merge(['services' => []]);
        }
        if (! $this->has('amenities')) {
            $this->merge(['amenities' => []]);
        }
        if (! $this->has('gallery')) {
            $this->merge(['gallery' => []]);
        }

        $this->merge(['currency' => Tour::CURRENCY_USD]);

        $this->normalizeDestinationIds();

        $this->normalizePriceRows();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->priceRowRules() + [
            'destination_ids' => ['required', 'array', 'min:1', 'max:12'],
            'destination_ids.*' => ['required', 'integer', 'distinct', 'exists:destinations,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'services' => ['present', 'array'],
            'services.*' => ['string', 'max:120'],
            'amenities' => ['present', 'array'],
            'amenities.*' => ['string', 'max:120'],
            'currency' => ['required', 'string', Rule::in([Tour::CURRENCY_USD])],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'thumbnail_media_id' => ['nullable', 'integer', 'exists:media,id'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string', 'max:10000'],
            'itinerary.*.schedule' => ['nullable', 'array'],
            'itinerary.*.schedule.*.time' => ['nullable', 'string', 'max:32'],
            'itinerary.*.schedule.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.schedule.*.description' => ['nullable', 'string', 'max:2000'],
            'gallery' => ['present', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->priceRowMessages() + [
            'destination_ids.required' => __('validation.tour_destinations.required'),
            'destination_ids.min' => __('validation.tour_destinations.required'),
            'destination_ids.*.required' => __('validation.tour_destinations.required'),
            'destination_ids.*.distinct' => __('validation.tour_destinations.duplicate'),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validatePriceRowTypesAreUnique($validator);
    }
}
