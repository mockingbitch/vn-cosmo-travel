<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;
use App\Services\PostService;
use App\Services\TourService;
use App\ViewModels\PostCardViewModel;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TourCardViewModel;

class HomeController extends Controller
{
    public function __construct(
        private readonly TourService $tourService,
        private readonly PostService $postService,
        private readonly DestinationService $destinationService,
    ) {
    }

    public function index()
    {
        $featuredTours = $this->tourService
            ->featured(4)
            ->map(fn ($tour) => new TourCardViewModel($tour));

        $latestPosts = $this->postService
            ->latest(3)
            ->map(fn ($post) => new PostCardViewModel($post));

        $destinations = $this->destinationService->all();

        return view('pages.home', [
            'seo' => new SeoViewModel(
                title: __('seo.home.title'),
                description: __('seo.home.description'),
            ),
            'featuredTours' => $featuredTours,
            'latestPosts' => $latestPosts,
            'destinations' => $destinations,
        ]);
    }
}
