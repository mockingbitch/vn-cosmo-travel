<?php

namespace Database\Seeders;

use App\Contracts\Interfaces\SettingRepositoryInterface;
use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Fills `settings` with brand, footer contact, “home why”, and homepage testimonials (English).
     * Run early in DatabaseSeeder so site.name / contact exist before content seeds.
     */
    public function run(): void
    {
        $defaultLocale = (string) config('app.locale', 'en');
        $settings = app(SettingRepositoryInterface::class);

        $settings->set('site.name', 'Vietnam Cosmo Travel');

        $settings->set('contact.email', 'hello@vietnamcosmotravel.com');
        $settings->set('contact.phone', '+84 90 123 4567');
        $settings->set('contact.address', 'District 1, Ho Chi Minh City, Vietnam');
        $settings->set('contact.map_iframe', '');

        $settings->set('social.facebook', null);
        $settings->set('social.instagram', null);
        $settings->set('social.youtube', null);
        $settings->set('social.tiktok', null);

        $settings->set('content.home_why', $this->homeWhyFromLang());

        $settings->set('content.testimonials', app(SettingsService::class)->testimonialsBaseline());

        app(SettingsService::class)->forgetCache();

        app()->setLocale($defaultLocale);
    }

    /**
     * @return array<string, array{title: string, subtitle: string, items: list<array{title: string, desc: string}>}>
     */
    private function homeWhyFromLang(): array
    {
        $out = [];
        $keys = [
            'fast',
            'pricing',
            'curated',
            'secure',
        ];
        foreach (['vi', 'en'] as $locale) {
            app()->setLocale($locale);
            $items = [];
            foreach ($keys as $k) {
                $items[] = [
                    'title' => (string) __("home.why.{$k}.title"),
                    'desc' => (string) __("home.why.{$k}.desc"),
                ];
            }
            $out[$locale] = [
                'title' => (string) __('home.why.title'),
                'subtitle' => (string) __('home.why.subtitle'),
                'items' => $items,
            ];
        }

        return $out;
    }
}
