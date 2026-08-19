<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\DestinationService;
use App\Services\FeaturedTilesService;
use App\Services\PostService;
use App\Services\SettingsService;
use App\ViewModels\HomeHeroViewModel;
use App\ViewModels\PostCardViewModel;
use App\ViewModels\SeoViewModel;

class HomeController extends Controller
{
    public function __construct(
        private readonly PostService $postService,
        private readonly DestinationService $destinationService,
        private readonly FeaturedTilesService $featuredTiles,
        private readonly SettingsService $settingsService,
        private readonly HeroBannerRepositoryInterface $heroBanners,
    ) {}

    public function index()
    {
        $latestPosts = $this->postService
            ->latest(3)
            ->map(fn ($post) => new PostCardViewModel($post));

        $destinations = $this->destinationService->all();
        $popularDestinations = $this->destinationService->mostPopularByTourCount(4);

        $hero = new HomeHeroViewModel(
            banner: $this->heroBanners->currentOrNull(),
            locale: app()->getLocale(),
        );

        return view('pages.home', [
            'seo' => new SeoViewModel(
                title: __('seo.home.title'),
                description: __('seo.home.description'),
            ),
            'hero' => $hero,
            'featuredTiles' => $this->featuredTiles->tiles(),
            'latestPosts' => $latestPosts,
            'destinations' => $destinations,
            'popularDestinations' => $popularDestinations,
            'homeWhy' => $this->settingsService->getHomeWhyForLocale(),
            'testimonials' => $this->settingsService->getTestimonials(),
        ]);
    }
}
