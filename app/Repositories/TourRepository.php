<?php

namespace App\Repositories;

use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TourRepository implements TourRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Tour::query()
            ->active()
            ->with(['destination'])
            ->withCount(['images', 'itineraries']);

        if (! empty($filters['destination'])) {
            $query->whereHas('destination', fn ($q) => $q->where('slug', $filters['destination']));
        }

        if (! empty($filters['duration'])) {
            // duration: "1-3", "4-7", "8+"
            $duration = (string) $filters['duration'];
            if (preg_match('/^(\d+)\-(\d+)$/', $duration, $m)) {
                $query->whereBetween('duration', [(int) $m[1], (int) $m[2]]);
            } elseif ($duration === '8+') {
                $query->where('duration', '>=', 8);
            }
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', (int) $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', (int) $filters['max_price']);
        }

        $sort = $filters['sort'] ?? 'popular';
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'duration_asc') {
            $query->orderBy('duration');
        } else {
            $query->latest('id');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getFeatured(int $limit = 4): Collection
    {
        return Tour::query()
            ->active()
            ->with(['destination'])
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function findBySlugOrFail(string $slug): Tour
    {
        return Tour::query()
            ->active()
            ->with(['destination', 'images', 'itineraries'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getRelated(int $tourId, int $destinationId, int $limit = 4): Collection
    {
        return Tour::query()
            ->active()
            ->with(['destination'])
            ->where('destination_id', $destinationId)
            ->where('id', '!=', $tourId)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function adminPaginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return Tour::query()
            ->with(['destination', 'creator', 'updatedBy'])
            ->when(
                filled($filters['q'] ?? null),
                function ($query) use ($filters): void {
                    $keyword = trim((string) $filters['q']);
                    $query->where(function ($inner) use ($keyword): void {
                        $inner
                            ->where('title', 'like', '%'.$keyword.'%')
                            ->orWhere('slug', 'like', '%'.$keyword.'%');
                    });
                }
            )
            ->when(
                filled($filters['status'] ?? null),
                fn ($query) => $query->where('status', (string) $filters['status'])
            )
            ->when(
                filled($filters['destination_id'] ?? null),
                fn ($query) => $query->where('destination_id', (int) $filters['destination_id'])
            )
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function adminCreate(array $data): Tour
    {
        return Tour::query()->create($data);
    }

    public function adminUpdate(Tour $tour, array $data): Tour
    {
        $tour->update($data);

        return $tour->fresh();
    }

    public function adminDelete(Tour $tour): void
    {
        $tour->delete();
    }
}
