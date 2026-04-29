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
        $status = $request->validated('status');
        $bookings->updateStatus($booking, $status);

        return redirect()
            ->route('admin.bookings.index', $this->bookingListQueryFromRequest($request))
            ->with('status', __('flash.booking.updated'));
    }

    /**
     * Preserve list filters from the update form hidden fields only — same params as before, no extras.
     *
     * @return array<string, int|string>
     */
    private function bookingListQueryFromRequest(Request $request): array
    {
        $query = [];

        if ($request->filled('q')) {
            $query['q'] = $request->string('q')->toString();
        }

        if ($request->filled('filter_status')) {
            $query['status'] = $request->string('filter_status')->toString();
        }

        if ($request->filled('tour_id') && (int) $request->input('tour_id') > 0) {
            $query['tour_id'] = (int) $request->input('tour_id');
        }

        if ($request->filled('page')) {
            $page = (int) $request->input('page');
            if ($page > 0) {
                $query['page'] = $page;
            }
        }

        return $query;
    }
}
