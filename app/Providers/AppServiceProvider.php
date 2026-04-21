<?php

namespace App\Providers;

use App\Contracts\Interfaces\BookingRepositoryInterface;
use App\Contracts\Interfaces\CategoryRepositoryInterface;
use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Contracts\Interfaces\SettingRepositoryInterface;
use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\HeroBannerRepository;
use App\Repositories\PostRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TourRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(DestinationRepositoryInterface::class, DestinationRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(HeroBannerRepositoryInterface::class, HeroBannerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
