<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\DestinationService;
use App\Services\TourService;
use App\Support\TourGallerySlide;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TourCardViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function __construct(
        private readonly TourService $tourService,
        private readonly DestinationService $destinationService,
    ) {}

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

        $urls = collect([$tour->thumbnail])
            ->merge($tour->images->pluck('path'))
            ->map(fn ($u) => is_string($u) ? trim($u) : '')
            ->filter()
            ->values();

        $gallerySlides = $urls
            ->map(fn (string $url) => TourGallerySlide::fromUrl($url)->toArray())
            ->values();

        if ($gallerySlides->isEmpty()) {
            $gallerySlides = collect([
                TourGallerySlide::fromUrl('https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=80')->toArray(),
            ]);
        }

        $first = $gallerySlides->first();
        $seoImage = ($first['type'] ?? 'image') === 'youtube'
            ? ($first['posterUrl'] ?? $first['src'])
            : $first['src'];

        return view('pages.tours.show', [
            'seo' => new SeoViewModel(
                title: $tour->title.' — '.__('ui.vietnam_tour'),
                description: Str::limit(strip_tags((string) $tour->description), 155),
                image: $seoImage,
            ),
            'tour' => $tour,
            'gallerySlides' => $gallerySlides,
            'relatedTours' => $related,
        ]);
    }
}
