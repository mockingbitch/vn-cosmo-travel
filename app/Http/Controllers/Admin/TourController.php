<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourRequest;
use App\Http\Requests\Admin\UpdateTourRequest;
use App\Models\Tour;
use App\Services\Admin\TourAdminService;
use App\Services\DestinationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(TourAdminService $tours): View
    {
        return view('admin.tours.index', [
            'tours' => $tours->paginate(15),
        ]);
    }

    public function create(DestinationService $destinations): View
    {
        return view('admin.tours.create', [
            'destinations' => $destinations->all(),
        ]);
    }

    public function store(StoreTourRequest $request, TourAdminService $tours): RedirectResponse
    {
        $tours->create($request->validated());

        return redirect()->route('admin.tours.index')->with('status', __('flash.tour.created'));
    }

    public function edit(Tour $tour, DestinationService $destinations): View
    {
        $tour->load([
            'itineraries' => fn ($q) => $q->orderBy('day'),
            'images' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        return view('admin.tours.edit', [
            'tour' => $tour,
            'destinations' => $destinations->all(),
        ]);
    }

    public function update(UpdateTourRequest $request, Tour $tour, TourAdminService $tours): RedirectResponse
    {
        $tours->update($tour, $request->validated());

        return redirect()->route('admin.tours.index')->with('status', __('flash.tour.updated'));
    }

    public function destroy(Tour $tour, TourAdminService $tours): RedirectResponse
    {
        $tours->delete($tour);

        return redirect()->route('admin.tours.index')->with('status', __('flash.tour.deleted'));
    }
}
