<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreBookingRequest;
use App\Mail\BookingCreated;
use App\Models\Tour;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {
    }

    public function store(StoreBookingRequest $request, Tour $tour): JsonResponse|RedirectResponse
    {
        $booking = $this->bookingService->create([
            ...$request->validated(),
            'tour_id' => $tour->id,
            'status' => 'pending',
        ]);

        $booking->loadMissing('tour');

        $notifyEmail = (string) config('mail.from.address', 'hello@vietnamcosmotravel.com');

        Mail::to($notifyEmail)->send(new BookingCreated($booking));
        Mail::to($booking->email)->send(new BookingCreated($booking, true));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('Booking request received. We will contact you shortly.'),
                'booking_id' => $booking->id,
            ]);
        }

        return back()
            ->with('booking_success', __('Booking request received. We will contact you shortly.'))
            ->withInput([]);
    }
}
