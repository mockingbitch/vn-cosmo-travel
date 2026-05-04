<?php

namespace App\Services;

use App\Contracts\Interfaces\SettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private const CACHE_KEY = 'settings:all';

    public function __construct(
        private readonly SettingRepositoryInterface $settings,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        /** @var array<string, mixed> $all */
        $all = Cache::rememberForever(self::CACHE_KEY, function (): array {
            return $this->settings->allKeyed()->all();
        });

        return $all;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Brand name for SEO titles (`site.name` in settings, then `config('app.name')`).
     */
    public function siteNameForLocale(): string
    {
        $name = $this->get('site.name');
        $name = is_string($name) ? trim($name) : '';
        if ($name !== '') {
            return $name;
        }

        return (string) config('app.name', 'App');
    }

    /**
     * @return array{title: string, subtitle: string, items: list<array{title: string, desc: string}>}
     */
    public function getHomeWhyForLocale(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $supported = array_keys((array) config('locales.supported', []));
        if (! in_array($locale, $supported, true)) {
            $locale = (string) config('locales.default', 'en');
        }

        /** @var array<string, mixed> $stored */
        $stored = $this->get('content.home_why', []);
        $stored = is_array($stored) ? $stored : [];
        /** @var array<string, mixed> $block */
        $block = is_array($stored[$locale] ?? null) ? $stored[$locale] : [];

        $keys = ['fast', 'pricing', 'curated', 'secure'];
        $defaultItems = [];
        foreach ($keys as $key) {
            $defaultItems[] = [
                'title' => __("home.why.{$key}.title"),
                'desc' => __("home.why.{$key}.desc"),
            ];
        }

        $items = [];
        for ($i = 0; $i < 4; $i++) {
            /** @var array<string, mixed> $row */
            $row = is_array($block['items'][$i] ?? null) ? $block['items'][$i] : [];
            $title = isset($row['title']) ? trim((string) $row['title']) : '';
            $desc = isset($row['desc']) ? trim((string) $row['desc']) : '';
            $def = $defaultItems[$i];
            $items[] = [
                'title' => $title !== '' ? $title : $def['title'],
                'desc' => $desc !== '' ? $desc : $def['desc'],
            ];
        }

        $t = isset($block['title']) ? trim((string) $block['title']) : '';
        $s = isset($block['subtitle']) ? trim((string) $block['subtitle']) : '';

        return [
            'title' => $t !== '' ? $t : (string) __('home.why.title'),
            'subtitle' => $s !== '' ? $s : (string) __('home.why.subtitle'),
            'items' => $items,
        ];
    }

    /**
     * Homepage testimonials block (English only for visitors).
     *
     * @return array{title: string, subtitle: string, items: list<array{quote: string, author: string, meta: string, image_url: string, scene_alt: string}>}
     */
    public function getTestimonials(): array
    {
        /** @var array<string, mixed> $stored */
        $stored = $this->get('content.testimonials', []);
        $stored = is_array($stored) ? $stored : [];

        $defaults = $this->testimonialsBaseline();

        $title = isset($stored['title']) ? trim((string) $stored['title']) : '';
        $subtitle = isset($stored['subtitle']) ? trim((string) $stored['subtitle']) : '';

        $items = [];
        for ($i = 0; $i < 3; $i++) {
            /** @var array<string, mixed> $row */
            $row = is_array($stored['items'][$i] ?? null) ? $stored['items'][$i] : [];
            $def = $defaults['items'][$i];
            $quote = isset($row['quote']) ? trim((string) $row['quote']) : '';
            $author = isset($row['author']) ? trim((string) $row['author']) : '';
            $meta = isset($row['meta']) ? trim((string) $row['meta']) : '';
            $imageUrl = isset($row['image_url']) ? trim((string) $row['image_url']) : '';
            $sceneAlt = isset($row['scene_alt']) ? trim((string) $row['scene_alt']) : '';

            $items[] = [
                'quote' => $quote !== '' ? $quote : $def['quote'],
                'author' => $author !== '' ? $author : $def['author'],
                'meta' => $meta !== '' ? $meta : $def['meta'],
                'image_url' => $imageUrl !== '' ? $imageUrl : $def['image_url'],
                'scene_alt' => $sceneAlt !== '' ? $sceneAlt : $def['scene_alt'],
            ];
        }

        return [
            'title' => $title !== '' ? $title : $defaults['title'],
            'subtitle' => $subtitle !== '' ? $subtitle : $defaults['subtitle'],
            'items' => $items,
        ];
    }

    /**
     * Baseline English copy + image URLs matching historical homepage defaults (seed / fallback).
     *
     * @return array{title: string, subtitle: string, items: list<array{quote: string, author: string, meta: string, image_url: string, scene_alt: string}>}
     */
    public function testimonialsBaseline(): array
    {
        $urls = array_values((array) config('home.testimonial_scene_urls', []));

        return [
            'title' => 'Testimonials',
            'subtitle' => 'What travelers have said after their trip with us.',
            'items' => [
                [
                    'quote' => 'Airport pickup was on time and our guide’s English was easy to follow. Ha Long looked better than the photos; the old-quarter room was small but clean, as they’d described by email.',
                    'author' => 'Lan A.',
                    'meta' => 'Hanoi & Ha Long · 3 days',
                    'image_url' => (string) ($urls[0] ?? ''),
                    'scene_alt' => 'Travel photo: Ha Long Bay limestone karsts and sea, Vietnam.',
                ],
                [
                    'quote' => 'Booked a 3-day Mekong trip online and got a reply the same day. The local restaurants were a highlight; heavy rain one afternoon meant a shorter boat ride than planned.',
                    'author' => 'Michael C.',
                    'meta' => 'Can Tho & Chau Doc · 3 days',
                    'image_url' => (string) ($urls[1] ?? ''),
                    'scene_alt' => 'Travel photo: boats on a tropical river.',
                ],
                [
                    'quote' => 'We had young kids and worried about a packed schedule, but lunch breaks were sensible and we didn’t start too early. Thanks for moving the kayak slot when our child was unwell on day three.',
                    'author' => 'Thao’s family',
                    'meta' => 'Hue to Da Nang · 5 days',
                    'image_url' => (string) ($urls[2] ?? ''),
                    'scene_alt' => 'Travel photo: turquoise sea and palm-fringed shore.',
                ],
            ],
        ];
    }
}
