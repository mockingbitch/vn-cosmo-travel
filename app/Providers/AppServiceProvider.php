<?php

namespace App\Providers;

use App\Contracts\Interfaces\BookingRepositoryInterface;
use App\Contracts\Interfaces\CategoryRepositoryInterface;
use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Contracts\Interfaces\SettingRepositoryInterface;
use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\HeroBannerRepository;
use App\Repositories\MediaRepository;
use App\Repositories\PostRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TourRepository;
use App\Services\DestinationService;
use App\Services\MainNavigationService;
use App\Services\SettingsService;
use App\ViewModels\SiteContactViewModel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $destinations = app(DestinationService::class);
            $view->with('navDestinations', $destinations->all());
            $view->with('navDestinationGroups', $destinations->groupedByRegionForNav());
            $view->with('mainNav', app(MainNavigationService::class)->build(request()));
            $view->with('siteContact', new SiteContactViewModel(app(SettingsService::class)));
        });

        View::composer('pages.home', function ($view): void {
            $view->with('siteContact', new SiteContactViewModel(app(SettingsService::class)));
        });

        $this->configureRateLimiters();
    }

    /**
     * Define throttle limiters used by public-facing forms to deter spam.
     */
    protected function configureRateLimiters(): void
    {
        // Public booking form: 5 submissions / minute and 20 / hour per IP,
        // and 3 / hour per (IP + email) to block aggressive resends.
        RateLimiter::for('bookings', function (Request $request): array {
            $ip = (string) $request->ip();
            $email = strtolower((string) $request->input('email', ''));
            $emailKey = $email !== '' ? sha1($ip.'|'.$email) : $ip;

            return [
                Limit::perMinute(5)->by('booking:ip:'.$ip),
                Limit::perHour(20)->by('booking:ip:hour:'.$ip),
                Limit::perHour(3)->by('booking:email:'.$emailKey),
            ];
        });

        // Admin login: 5 attempts per minute per (email + IP) to slow brute force.
        RateLimiter::for('admin-login', function (Request $request): Limit {
            $email = strtolower((string) $request->input('email', ''));

            return Limit::perMinute(5)->by($email.'|'.$request->ip());
        });
    }
}
