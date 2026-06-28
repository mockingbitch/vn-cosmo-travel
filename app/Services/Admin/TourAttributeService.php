<?php

namespace App\Services\Admin;

use App\Models\TourAttribute;

class TourAttributeService
{
    /**
     * Custom (admin-added) labels for a type, sorted and reusable across tours.
     *
     * @return list<string>
     */
    public function optionsFor(string $type): array
    {
        return TourAttribute::query()
            ->where('type', $type)
            ->orderBy('label')
            ->pluck('label')
            ->all();
    }

    /**
     * Persist free-text (non-catalog) labels so they become reusable options on
     * every tour. Canonical catalog keys and blanks are ignored.
     *
     * @param  list<string>  $labels
     */
    public function sync(array $labels, string $type): void
    {
        $catalogKeys = $type === TourAttribute::TYPE_SERVICE
            ? config('tour_catalog.services', [])
            : config('tour_catalog.amenities', []);

        foreach ($labels as $label) {
            $label = is_string($label) ? trim($label) : '';
            if ($label === '' || in_array($label, $catalogKeys, true)) {
                continue;
            }

            TourAttribute::query()->firstOrCreate([
                'type' => $type,
                'label' => $label,
            ]);
        }
    }
}
