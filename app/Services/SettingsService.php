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
}

