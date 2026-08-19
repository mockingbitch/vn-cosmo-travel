<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\FeaturedTilesService;
use App\Services\TourService;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TourCardViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeaturedController extends Controller
{
    public function __construct(
        private readonly FeaturedTilesService $featuredTiles,
        private readonly TourService $tourService,
    ) {}

    /**
     * `/featured` lists every tile; `/featured?p={tile}` opens one tile with the
     * tours an admin picked for it.
     */
    public function show(Request $request): View
    {
        $key = trim((string) $request->query('p', ''));

        if ($key === '') {
            return view('pages.featured.index', [
                'seo' => new SeoViewModel(
                    title: __('featured.index.title'),
                    description: __('featured.index.subtitle'),
                ),
                'tiles' => $this->featuredTiles->tiles(),
            ]);
        }

        $tile = $this->featuredTiles->findBySlug($key);
        abort_if($tile === null, 404);

        return view('pages.featured.show', [
            'seo' => new SeoViewModel(
                title: $tile['title'],
                description: Str::limit(strip_tags((string) $tile['description']), 155),
            ),
            'tile' => $tile,
            'tours' => $this->toursFor($tile),
            'usesFallback' => $tile['tour_ids'] === [],
        ]);
    }

    /**
     * Curated tours when the admin picked some; otherwise the tours of the
     * tile's default destination, so a tile is never a dead end.
     *
     * @param  array<string, mixed>  $tile
     * @return Collection<int, TourCardViewModel>
     */
    private function toursFor(array $tile): Collection
    {
        /** @var list<int> $tourIds */
        $tourIds = $tile['tour_ids'];

        if ($tourIds !== []) {
            return $this->tourService->byIds($tourIds)
                ->map(fn ($tour) => new TourCardViewModel($tour));
        }

        $destinationSlug = (string) $tile['destination_slug'];
        if (! $this->featuredTiles->destinationExists($destinationSlug)) {
            return collect();
        }

        return $this->tourService
            ->paginate(['destination' => $destinationSlug], 12)
            ->getCollection()
            ->map(fn ($tour) => new TourCardViewModel($tour));
    }
}
