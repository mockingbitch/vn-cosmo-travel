<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\HeroBannerController as AdminHeroBannerController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TourController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Default Laravel auth views may expect a `login` route name.
// This project uses an admin-only login screen, so we alias `login` to `admin.login`.
Route::get('/login', function (): RedirectResponse {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/language/{locale}', function (Request $request, string $locale): RedirectResponse {
    $supported = array_keys((array) config('locales.supported', []));
    if (! in_array($locale, $supported, true)) {
        $locale = (string) config('locales.default', config('app.locale'));
    }

    $request->session()->put('locale', $locale);

    return back()->withCookie(cookie()->forever('locale', $locale));
})->name('language.switch');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])
            ->middleware('throttle:admin-login')
            ->name('login.store');
    });

    Route::post('logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::middleware('manage.users')->group(function () {
            Route::get('settings', function (): RedirectResponse {
                return redirect()->route('admin.settings.general.edit');
            })->name('settings.edit');
            Route::get('settings/general', [AdminSettingController::class, 'editGeneral'])->name('settings.general.edit');
            Route::put('settings/general', [AdminSettingController::class, 'updateGeneral'])->name('settings.general.update');
            Route::get('settings/contact', [AdminSettingController::class, 'editContact'])->name('settings.contact.edit');
            Route::put('settings/contact', [AdminSettingController::class, 'updateContact'])->name('settings.contact.update');
            Route::get('settings/social', [AdminSettingController::class, 'editSocial'])->name('settings.social.edit');
            Route::put('settings/social', [AdminSettingController::class, 'updateSocial'])->name('settings.social.update');
            Route::get('settings/home-why', [AdminSettingController::class, 'editHomeWhy'])->name('settings.homeWhy.edit');
            Route::put('settings/home-why', [AdminSettingController::class, 'updateHomeWhy'])->name('settings.homeWhy.update');

            Route::get('banners', [AdminHeroBannerController::class, 'edit'])->name('banners.edit');
            Route::put('banners', [AdminHeroBannerController::class, 'update'])->name('banners.update');
            Route::post('banners/apply/{banner}', [AdminHeroBannerController::class, 'apply'])->name('banners.apply');

            Route::resource('users', AdminUserController::class)->except(['show']);
        });

        Route::get('media/picker', [AdminMediaController::class, 'picker'])->name('media.picker');
        Route::get('media/by-ids', [AdminMediaController::class, 'byIds'])->name('media.byIds');
        Route::delete('media/bulk', [AdminMediaController::class, 'bulkDestroy'])->name('media.bulkDestroy');
        Route::get('media/{media}/usages', [AdminMediaController::class, 'usages'])->name('media.usages');
        Route::resource('media', AdminMediaController::class)->only(['index', 'store', 'destroy', 'show']);

        Route::resource('tours', AdminTourController::class)->except(['show']);
        Route::resource('posts', AdminPostController::class)->except(['show']);
        Route::resource('destinations', AdminDestinationController::class)->except(['show']);
        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::patch('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');

        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    });
});

Route::name('')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('tours')->name('tours.')->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/{tour:slug}', [TourController::class, 'show'])->name('show');
        Route::post('/{tour:slug}/book', [BookingController::class, 'store'])
            ->middleware('throttle:bookings')
            ->name('book');
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
    });

    Route::prefix('destinations')->name('destinations.')->group(function () {
        Route::get('/{destination:slug}', [DestinationController::class, 'show'])->name('show');
    });
});
