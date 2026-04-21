<?php

namespace App\Contracts\Interfaces;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookingRepositoryInterface
{
    public function create(array $data): Booking;

    public function adminPaginate(int $perPage = 20): LengthAwarePaginator;

    public function adminUpdate(Booking $booking, array $data): Booking;
}

