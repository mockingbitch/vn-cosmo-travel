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

    public function adminPaginate(int $perPage = 20): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['tour'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function adminUpdate(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh();
    }
}

