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

    /**
     * @param  array{q?: string|null, status?: string|null, tour_id?: int|null}  $filters
     */
    public function paginate(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->bookings->adminPaginate($perPage, $filters);
    }

    public function updateStatus(Booking $booking, string $status): Booking
    {
        $payload = ['status' => $status];

        if (($uid = auth()->id()) !== null) {
            $payload['updated_by'] = $uid;
        }

        return $this->bookings->adminUpdate($booking, $payload);
    }
}
