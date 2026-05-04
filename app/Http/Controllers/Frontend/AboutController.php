<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Services\SettingsService;
use App\ViewModels\SeoViewModel;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    public function show(SettingsService $settings)
    {
        $about = AboutPage::query()->firstOrFail();

        $aboutDisplay = $about->resolvedForLocale();

        return view('pages.about.show', [
            'seo' => new SeoViewModel(
                title: $aboutDisplay['title'].' — '.$settings->siteNameForLocale(),
                description: Str::limit(strip_tags((string) $aboutDisplay['content']), 155),
            ),
            'aboutDisplay' => $aboutDisplay,
        ]);
    }
}
