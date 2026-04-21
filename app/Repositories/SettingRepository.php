<?php

namespace App\Repositories;

use App\Contracts\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingRepository implements SettingRepositoryInterface
{
    public function allKeyed(): Collection
    {
        return Setting::query()
            ->get(['key', 'value'])
            ->mapWithKeys(fn (Setting $s) => [$s->key => $s->value]);
    }

    public function set(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}

