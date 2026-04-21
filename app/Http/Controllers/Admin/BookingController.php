<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use App\Services\Admin\BookingAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(BookingAdminService $bookings): View
    {
        return view('admin.bookings.index', [
            'bookings' => $bookings->paginate(20),
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking, BookingAdminService $bookings): RedirectResponse
    {
        $bookings->updateStatus($booking, $request->validated('status'));

        return redirect()->route('admin.bookings.index')->with('status', __('flash.booking.updated'));
    }
}
