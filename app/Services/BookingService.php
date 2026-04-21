<?php

namespace App\Services;

use App\Contracts\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class BookingService
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookings,
    ) {
    }

    public function create(array $data): Booking
    {
        $booking = $this->bookings->create($data);

        Log::info('Booking created', [
            'booking_id' => $booking->id,
            'tour_id' => $booking->tour_id,
            'email' => $booking->email,
        ]);

        return $booking;
    }
}

