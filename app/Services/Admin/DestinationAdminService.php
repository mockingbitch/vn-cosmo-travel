<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class DestinationAdminService
{
    public function __construct(
        private readonly DestinationRepositoryInterface $destinations,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->destinations->adminPaginate($perPage);
    }

    public function create(array $data): Destination
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['name']);

        return $this->destinations->adminCreate($data);
    }

    public function update(Destination $destination, array $data): Destination
    {
        $name = $data['name'] ?? $destination->name;
        $slugInput = array_key_exists('slug', $data) ? $data['slug'] : $destination->slug;
        $data['slug'] = $this->uniqueSlug($slugInput, $name, $destination->id);

        return $this->destinations->adminUpdate($destination, $data);
    }

    public function delete(Destination $destination): void
    {
        $this->destinations->adminDelete($destination);
    }

    private function uniqueSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug !== null && $slug !== '' ? $slug : $name);
        if ($base === '') {
            $base = 'destination';
        }

        $candidate = $base;
        $i = 2;
        while (
            Destination::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
}
