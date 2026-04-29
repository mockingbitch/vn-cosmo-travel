<?php

namespace App\Contracts\Interfaces;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookingRepositoryInterface
{
    public function create(array $data): Booking;

    /**
     * @param  array{q?: string|null, status?: string|null, tour_id?: int|null}  $filters
     */
    public function adminPaginate(int $perPage = 20, array $filters = []): LengthAwarePaginator;

    public function adminUpdate(Booking $booking, array $data): Booking;
}

