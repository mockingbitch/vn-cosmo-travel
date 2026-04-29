<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Tour;
use App\Services\Admin\BookingAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request, BookingAdminService $bookings): View
    {
        $filters = [
            'q' => $request->string('q')->toString(),
            'status' => $request->string('status')->toString(),
            'tour_id' => (int) $request->integer('tour_id'),
        ];

        return view('admin.bookings.index', [
            'bookings' => $bookings->paginate(20, $filters),
            'filters' => $filters,
            'tours' => Tour::query()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking, BookingAdminService $bookings): RedirectResponse
    {
        $bookings->updateStatus($booking, $request->validated('status'));

        $listQuery = array_filter(
            $request->only(['q', 'status', 'tour_id', 'page']),
            fn ($v) => $v !== null && $v !== '',
        );

        return redirect()
            ->route('admin.bookings.index', $listQuery)
            ->with('status', __('flash.booking.updated'));
    }
}
