<?php

namespace App\Contracts\Interfaces;

use Illuminate\Support\Collection;

interface SettingRepositoryInterface
{
    /**
     * @return Collection<string, mixed>
     */
    public function allKeyed(): Collection;

    public function set(string $key, mixed $value): void;
}

