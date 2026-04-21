<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TourController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\HeroBannerController as AdminHeroBannerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

Route::get('/language/{locale}', function (Request $request, string $locale): RedirectResponse {
    $supported = array_keys((array) config('locales.supported', []));
    if (!in_array($locale, $supported, true)) {
        $locale = (string) config('locales.default', config('app.locale'));
    }

    $request->session()->put('locale', $locale);

    return back()->withCookie(cookie()->forever('locale', $locale));
})->name('language.switch');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::post('logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

        Route::get('banners', [AdminHeroBannerController::class, 'edit'])->name('banners.edit');
        Route::put('banners', [AdminHeroBannerController::class, 'update'])->name('banners.update');
        Route::post('banners/apply/{banner}', [AdminHeroBannerController::class, 'apply'])->name('banners.apply');

        Route::resource('tours', AdminTourController::class)->except(['show']);
        Route::resource('posts', AdminPostController::class)->except(['show']);
        Route::resource('destinations', AdminDestinationController::class)->except(['show']);
        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::patch('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    });
});

Route::name('')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('tours')->name('tours.')->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/{tour:slug}', [TourController::class, 'show'])->name('show');
        Route::post('/{tour:slug}/book', [BookingController::class, 'store'])->name('book');
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
    });

    Route::prefix('destinations')->name('destinations.')->group(function () {
        Route::get('/{destination:slug}', [DestinationController::class, 'show'])->name('show');
    });
});
