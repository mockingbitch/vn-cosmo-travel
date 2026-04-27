<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MainNavigationService
{
    public function build(Request $request): array
    {
        $slugs = $this->collectSlugsFromConfig();

        $bySlug = $slugs->isNotEmpty()
            ? Destination::query()->whereIn('slug', $slugs->all())->get()->keyBy('slug')
            : collect();

        return [
            'primary' => $this->buildPrimary($request),
            'dailyMega' => $this->buildDailyMega($bySlug),
            'cruise' => $this->buildCruise(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildPrimary(Request $request): array
    {
        $out = [];
        foreach (config('navigation.primary', []) as $row) {
            $id = (string) $row['id'];
            $type = (string) $row['type'];
            $label = __((string) $row['label_key']);
            $href = '#';
            if ($type === 'link') {
                if (isset($row['tour_params'])) {
                    $href = route('tours.index', array_filter((array) $row['tour_params']));
                } elseif (isset($row['route'])) {
                    $base = route((string) $row['route'], (array) ($row['params'] ?? []));
                    $hash = isset($row['hash']) ? '#'.ltrim((string) $row['hash'], '#') : '';
                    $href = $base.$hash;
                }
            }
            $out[] = array_merge($row, [
                'label' => $label,
                'href' => $href,
                'active' => $this->isPrimaryActive($request, $row, $id),
            ]);
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function isPrimaryActive(Request $request, array $row, string $id): bool
    {
        if (($row['type'] ?? '') === 'mega' && ($row['panel'] ?? '') === 'daily') {
            if (! $request->routeIs('tours.index')) {
                return false;
            }
            $d = (string) $request->query('duration', '');

            return $d === '' || $d === '1-3';
        }

        if (($row['type'] ?? '') === 'dropdown' && ($row['panel'] ?? '') === 'cruise') {
            if (! $request->routeIs('tours.index')) {
                return false;
            }
            $dest = (string) $request->query('destination', '');

            return in_array($dest, ['ha-long-bay', 'hanoi', 'ho-chi-minh-city'], true);
        }

        if ($id === 'hanoi_day') {
            return $request->routeIs('tours.index') && $request->query('destination') === 'hanoi';
        }

        if ($id === 'package') {
            return $request->routeIs('tours.index')
                && in_array((string) $request->query('duration', ''), ['4-7', '8+'], true);
        }

        if ($id === 'about') {
            // In-page #hash is not sent to the server; keep off unless you add a dedicated route.
            return false;
        }

        return false;
    }

    private function buildDailyMega(Collection $bySlug): array
    {
        $rows = config('navigation.mega_rows', []);
        $resolved = [];
        foreach ($rows as $rowSet) {
            $resolvedRow = [];
            foreach ($rowSet as $section) {
                $slugs = (array) ($section['slugs'] ?? []);
                $items = [];
                foreach ($slugs as $slug) {
                    $slug = (string) $slug;
                    $dest = $bySlug->get($slug);
                    if (! $dest instanceof Destination) {
                        continue;
                    }
                    $items[] = [
                        'label' => $dest->localizedName(),
                        'href' => route('tours.index', ['destination' => $dest->slug]),
                    ];
                }
                if ($items === []) {
                    continue;
                }
                $resolvedRow[] = [
                    'title' => __((string) $section['title_key']),
                    'items' => $items,
                ];
            }
            if ($resolvedRow !== []) {
                $resolved[] = $resolvedRow;
            }
        }

        $featured = config('navigation.mega_featured', []);
        $featuredOut = null;
        if (is_array($featured) && $featured !== []) {
            $featuredOut = [
                'image' => (string) ($featured['image'] ?? ''),
                'image_alt' => __((string) ($featured['image_alt_key'] ?? 'nav.mega.featured_alt')),
                'title' => __((string) ($featured['title_key'] ?? '')),
                'subtitle' => __((string) ($featured['subtitle_key'] ?? '')),
                'href' => route(
                    (string) ($featured['route'] ?? 'tours.index'),
                    (array) ($featured['params'] ?? [])
                ),
            ];
        }

        return [
            'rows' => $resolved,
            'featured' => $featuredOut,
        ];
    }

    /**
     * @return list<array{label: string, href: string}>
     */
    private function buildCruise(): array
    {
        $out = [];
        foreach (config('navigation.cruise', []) as $row) {
            $out[] = [
                'label' => __((string) $row['label_key']),
                'href' => route('tours.index', (array) ($row['tour_params'] ?? [])),
            ];
        }

        return $out;
    }

    private function collectSlugsFromConfig(): Collection
    {
        $slugs = collect();

        foreach (config('navigation.mega_rows', []) as $rowSet) {
            foreach ($rowSet as $section) {
                foreach ((array) ($section['slugs'] ?? []) as $slug) {
                    $slugs->push((string) $slug);
                }
            }
        }

        return $slugs->unique()->values();
    }
}
