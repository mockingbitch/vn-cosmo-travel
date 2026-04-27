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
     * @return array{title: string, subtitle: string, items: list<array{title: string, desc: string}>}
     */
    public function getHomeWhyForLocale(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $supported = array_keys((array) config('locales.supported', []));
        if (!in_array($locale, $supported, true)) {
            $locale = (string) config('locales.default', 'vi');
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
}

