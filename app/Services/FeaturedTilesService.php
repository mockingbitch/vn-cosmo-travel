<?php

namespace App\Services;

use App\Contracts\Interfaces\DestinationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Builds the homepage featured mosaic: config defaults, with per-tile admin
 * overrides from settings layered on top field by field.
 */
class FeaturedTilesService
{
    public const MAX_CHIPS = 6;

    /**
     * Safety ceiling for the stored settings payload, not a design constraint:
     * a card may hold as many tours as an admin wants below this.
     */
    public const MAX_TOURS_PER_TILE = 50;

    /**
     * Mosaic shape per slot. `wide` tiles have room for the blurb + link label;
     * the grid tracks are a fixed height, so spans must add up per row:
     * row 1–2 = lead (2 cols, 2 rows) + two stacked, row 3 = 1 + 2, row 4 = 2 + 1.
     *
     * @var array<int, array{span: string, wide: bool, label: string}>
     */
    public const SLOT_SHAPES = [
        0 => ['span' => 'lg:col-span-2 lg:row-span-2', 'wide' => true, 'label' => 'lead'],
        1 => ['span' => 'lg:col-span-1', 'wide' => false, 'label' => 'small'],
        2 => ['span' => 'lg:col-span-1', 'wide' => false, 'label' => 'small'],
        3 => ['span' => 'lg:col-span-1', 'wide' => false, 'label' => 'small'],
        4 => ['span' => 'lg:col-span-2', 'wide' => true, 'label' => 'wide'],
        5 => ['span' => 'lg:col-span-2', 'wide' => true, 'label' => 'wide'],
        6 => ['span' => 'lg:col-span-1', 'wide' => false, 'label' => 'small'],
    ];

    private const FALLBACK_SHAPE = ['span' => 'lg:col-span-1', 'wide' => false, 'label' => 'small'];

    /** @var Collection<int, string>|null */
    private ?Collection $slugCache = null;

    public function __construct(
        private readonly SettingsService $settings,
        private readonly DestinationRepositoryInterface $destinations,
    ) {}

    /**
     * @return list<array{slug: string, eyebrow: string, title: string, chips: list<string>, description: string, cta_label: string, url: string, image_url: string, tour_ids: list<int>, destination_slug: string, span: string, is_wide: bool, is_lead: bool}>
     */
    public function tiles(): array
    {
        $defaults = $this->defaults();

        /** @var array<int, mixed> $stored */
        $stored = $this->settings->get('content.featured_tiles', []);
        $stored = is_array($stored) ? $stored : [];

        $tiles = [];
        $usedSlugs = [];
        foreach ($defaults as $i => $default) {
            /** @var array<string, mixed> $override */
            $override = is_array($stored[$i] ?? null) ? $stored[$i] : [];

            $title = $this->stringFrom($override['title'] ?? null) ?? $default['title'];
            $slug = $this->uniqueSlug($title, $default, $i, $usedSlugs);
            $usedSlugs[] = $slug;

            $tiles[] = [
                'slug' => $slug,
                'eyebrow' => $this->stringFrom($override['eyebrow'] ?? null) ?? $default['eyebrow'],
                'title' => $title,
                'chips' => $this->chipsFrom($override['chips'] ?? null) ?? $default['chips'],
                'description' => $this->stringFrom($override['description'] ?? null) ?? $default['description'],
                'cta_label' => $this->stringFrom($override['cta_label'] ?? null) ?? (string) __('home.featured_tiles.cta'),
                // Fixed by design: a tile always opens its own /featured page.
                'url' => route('featured', ['p' => $slug]),
                'image_url' => $this->stringFrom($override['image_url'] ?? null) ?? $default['image_url'],
                'tour_ids' => $this->tourIdsFrom($override['tour_ids'] ?? null),
                'destination_slug' => $default['destination_slug'],
                'span' => $this->slotShape($i)['span'],
                'is_wide' => $this->slotShape($i)['wide'],
                'is_lead' => $i === 0,
            ];
        }

        return $tiles;
    }

    /**
     * Config defaults, normalized and padded so every tile slot is present.
     *
     * @return list<array{slug: string, eyebrow: string, title: string, chips: list<string>, description: string, destination_slug: string, image_url: string}>
     */
    public function defaults(): array
    {
        /** @var array<int, mixed> $configured */
        $configured = config('featured_tiles.tiles', []);
        $configured = is_array($configured) ? array_values($configured) : [];

        $defaults = [];
        foreach ($configured as $tile) {
            $tile = is_array($tile) ? $tile : [];
            $chips = $this->chipsFrom($tile['chips'] ?? null) ?? [];

            $defaults[] = [
                'slug' => trim((string) ($tile['slug'] ?? '')),
                'eyebrow' => trim((string) ($tile['eyebrow'] ?? '')),
                'title' => trim((string) ($tile['title'] ?? '')),
                'chips' => $chips,
                'description' => trim((string) ($tile['description'] ?? '')),
                'destination_slug' => trim((string) ($tile['destination_slug'] ?? '')),
                'image_url' => trim((string) ($tile['image_url'] ?? '')),
            ];
        }

        return $defaults;
    }

    /**
     * @return array{span: string, wide: bool, label: string}
     */
    public function slotShape(int $index): array
    {
        return self::SLOT_SHAPES[$index] ?? self::FALLBACK_SHAPE;
    }

    /**
     * Shape of every configured slot, in order (used by the admin form).
     *
     * @return list<array{span: string, wide: bool, label: string}>
     */
    public function slotShapes(): array
    {
        $shapes = [];
        for ($i = 0, $count = $this->tileCount(); $i < $count; $i++) {
            $shapes[] = $this->slotShape($i);
        }

        return $shapes;
    }

    public function tileCount(): int
    {
        return count($this->defaults());
    }

    /**
     * `?p=` key of a tile: slugified title, falling back to the config slug and
     * finally the slot number. A suffix keeps two same-named tiles apart.
     *
     * @param  array{slug: string, title: string}  $default
     * @param  list<string>  $used
     */
    private function uniqueSlug(string $title, array $default, int $index, array $used): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = Str::slug($default['slug'] !== '' ? $default['slug'] : 'tile-'.($index + 1));
        }

        if ($base === '') {
            $base = 'tile-'.($index + 1);
        }

        if (! in_array($base, $used, true)) {
            return $base;
        }

        $suffix = 2;
        while (in_array($base.'-'.$suffix, $used, true)) {
            $suffix++;
        }

        return $base.'-'.$suffix;
    }

    /**
     * The tile behind a `/featured?p=` key, or null when the key is unknown.
     *
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }

        foreach ($this->tiles() as $tile) {
            if ($tile['slug'] === $slug) {
                return $tile;
            }
        }

        return null;
    }

    /**
     * True when that destination exists, so tour fallbacks never query a ghost slug.
     */
    public function destinationExists(string $slug): bool
    {
        return $slug !== '' && $this->destinationSlugs()->contains($slug);
    }

    /**
     * @return Collection<int, string>
     */
    private function destinationSlugs(): Collection
    {
        return $this->slugCache ??= $this->destinations
            ->all()
            ->pluck('slug')
            ->map(fn ($slug): string => (string) $slug)
            ->values();
    }

    /**
     * Curated tour ids for a tile, de-duplicated and capped.
     *
     * @return list<int>
     */
    private function tourIdsFrom(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $ids = [];
        foreach ($raw as $id) {
            $id = (int) $id;
            if ($id <= 0 || in_array($id, $ids, true)) {
                continue;
            }
            $ids[] = $id;
            if (count($ids) >= self::MAX_TOURS_PER_TILE) {
                break;
            }
        }

        return $ids;
    }

    /**
     * Accepts a list or a comma-separated string; null when nothing usable.
     *
     * @return list<string>|null
     */
    private function chipsFrom(mixed $raw): ?array
    {
        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }

        if (! is_array($raw)) {
            return null;
        }

        $chips = [];
        foreach ($raw as $chip) {
            $chip = trim((string) $chip);
            if ($chip === '') {
                continue;
            }
            $chips[] = $chip;
            if (count($chips) >= self::MAX_CHIPS) {
                break;
            }
        }

        return $chips === [] ? null : $chips;
    }

    private function stringFrom(mixed $raw): ?string
    {
        if (! is_string($raw)) {
            return null;
        }

        $trimmed = trim($raw);

        return $trimmed === '' ? null : $trimmed;
    }
}
