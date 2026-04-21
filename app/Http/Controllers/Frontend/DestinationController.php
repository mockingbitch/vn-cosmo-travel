<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Services\DestinationService;
use App\Services\TourService;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TourCardViewModel;

class DestinationController extends Controller
{
    public function __construct(
        private readonly DestinationService $destinationService,
        private readonly TourService $tourService,
    ) {
    }

    public function show(Destination $destination)
    {
        $destination = $this->destinationService->detail($destination->slug);

        $tours = $this->tourService->paginate([
            'destination' => $destination->slug,
        ], 12);

        $tours->setCollection($tours->getCollection()->map(fn ($t) => new TourCardViewModel($t)));

        return view('pages.destinations.show', [
            'seo' => new SeoViewModel(
                title: $destination->name.' — '.__('Destination'),
                description: \Illuminate\Support\Str::limit(strip_tags((string) $destination->description), 155),
            ),
            'destination' => $destination,
            'tours' => $tours,
        ]);
    }
}
