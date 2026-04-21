<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\DestinationService;
use App\Services\TourService;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TourCardViewModel;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function __construct(
        private readonly TourService $tourService,
        private readonly DestinationService $destinationService,
    ) {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['destination', 'duration', 'min_price', 'max_price', 'sort']);

        $tours = $this->tourService->paginate($filters, 12);
        $tourCards = $tours->getCollection()->map(fn ($tour) => new TourCardViewModel($tour));
        $tours->setCollection($tourCards);

        return view('pages.tours.index', [
            'seo' => new SeoViewModel(
                title: __('seo.tours_index.title'),
                description: __('seo.tours_index.description'),
            ),
            'tours' => $tours,
            'destinations' => $this->destinationService->all(),
            'filters' => $filters,
        ]);
    }

    public function show(Tour $tour)
    {
        $tour = $this->tourService->detail($tour->slug);
        $related = $this->tourService->related($tour, 4)->map(fn ($t) => new TourCardViewModel($t));

        $images = collect([$tour->thumbnail])
            ->merge($tour->images->pluck('path'))
            ->filter()
            ->values();

        if ($images->isEmpty()) {
            $images = collect(['https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=80']);
        }

        return view('pages.tours.show', [
            'seo' => new SeoViewModel(
                title: $tour->title.' — '.__('Vietnam Tour'),
                description: \Illuminate\Support\Str::limit(strip_tags((string) $tour->description), 155),
                image: $images->first(),
            ),
            'tour' => $tour,
            'images' => $images,
            'relatedTours' => $related,
        ]);
    }
}
