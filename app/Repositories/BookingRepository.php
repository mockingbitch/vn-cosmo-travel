<?php

namespace App\Repositories;

use App\Contracts\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingRepository implements BookingRepositoryInterface
{
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function adminPaginate(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $q = isset($filters['q']) ? trim((string) $filters['q']) : '';
        $status = isset($filters['status']) ? trim((string) $filters['status']) : '';
        $tourId = isset($filters['tour_id']) ? (int) $filters['tour_id'] : 0;

        return Booking::query()
            ->with(['tour', 'creator', 'updatedBy'])
            ->when($q !== '', function ($builder) use ($q): void {
                $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $q).'%';
                $builder->where(function ($w) use ($like): void {
                    $w->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('note', 'like', $like);
                });
            })
            ->when(in_array($status, ['pending', 'confirmed', 'cancelled'], true), fn ($b) => $b->where('status', $status))
            ->when($tourId > 0, fn ($b) => $b->where('tour_id', $tourId))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function adminUpdate(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh(['tour', 'creator', 'updatedBy']);
    }
}
