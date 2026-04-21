<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingAdminService
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookings,
    ) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->bookings->adminPaginate($perPage);
    }

    public function updateStatus(Booking $booking, string $status): Booking
    {
        return $this->bookings->adminUpdate($booking, ['status' => $status]);
    }
}
