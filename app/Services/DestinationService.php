<?php

namespace App\Services;

use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;
use Illuminate\Support\Collection;

class DestinationService
{
    public function __construct(
        private readonly DestinationRepositoryInterface $destinations,
    ) {
    }

    public function all(): Collection
    {
        return $this->destinations->all();
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Destination>
     */
    public function mostPopularByTourCount(int $limit = 4): Collection
    {
        return $this->destinations->mostPopularByTourCount($limit);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{key: string, label: string, items: \Illuminate\Support\Collection<int, \App\Models\Destination>}>
     */
    public function groupedByRegionForNav(): Collection
    {
        $order = config('destination_regions.order', []);
        $byKey = $this->all()->groupBy('region');

        return collect($order)
            ->map(function (string $key) use ($byKey) {
                $items = $byKey->get($key, collect())->sortBy(
                    fn (Destination $d) => mb_strtolower($d->localizedName())
                )->values();
                if ($items->isEmpty()) {
                    return null;
                }

                return [
                    'key' => $key,
                    'label' => __('dest.region.'.$key),
                    'items' => $items,
                ];
            })
            ->filter()
            ->values();
    }

    public function detail(string $slug): Destination
    {
        return $this->destinations->findBySlugOrFail($slug);
    }
}

