<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFeaturedToursRequest;
use App\Http\Requests\Admin\UpdateTourFeaturedRequest;
use App\Models\Tour;
use App\Services\Admin\TourAdminService;
use App\Services\DestinationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeaturedTourController extends Controller
{
    public function index(Request $request, TourAdminService $tours, DestinationService $destinations): View
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'destination_id' => trim((string) $request->query('destination_id', '')),
        ];

        return view('admin.featured-tours.index', [
            'featuredTours' => $tours->listFeatured(),
            'availableTours' => $tours->paginateNonFeatured(10, $filters),
            'filters' => $filters,
            'destinations' => $destinations->all(),
        ]);
    }

    public function update(UpdateFeaturedToursRequest $request, TourAdminService $tours): RedirectResponse
    {
        $tours->updateFeaturedSorts($request->validated('featured_tours', []));

        return redirect()
            ->route('admin.featured-tours.index')
            ->with('status', __('flash.featured_tours.updated'));
    }

    public function updateTour(UpdateTourFeaturedRequest $request, Tour $tour, TourAdminService $tours): RedirectResponse
    {
        $validated = $request->validated();
        $isFeatured = (bool) $validated['is_featured'];
        $sort = isset($validated['featured_sort']) ? (int) $validated['featured_sort'] : null;

        $tours->updateFeatured($tour, $isFeatured, $isFeatured ? $sort : null);

        return redirect()
            ->route('admin.featured-tours.index', $this->listQueryFromRequest($request))
            ->with('status', __('flash.tour.featured_updated'));
    }

    /**
     * @return array<string, int|string>
     */
    private function listQueryFromRequest(Request $request): array
    {
        $query = [];

        if ($request->filled('page')) {
            $page = (int) $request->input('page');
            if ($page > 0) {
                $query['page'] = $page;
            }
        }

        if ($request->filled('q')) {
            $query['q'] = trim((string) $request->input('q'));
        }

        if ($request->filled('destination_id')) {
            $query['destination_id'] = (string) $request->input('destination_id');
        }

        return $query;
    }
}
