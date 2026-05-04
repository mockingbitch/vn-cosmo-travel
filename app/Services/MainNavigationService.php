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
            'dropdownPanels' => $this->buildDropdownPanels(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildPrimary(Request $request): array
    {
        $out = [];
        foreach (config('navigation.primary', []) as $row) {
            $id = (string) ($row['id'] ?? '');
            $type = (string) ($row['type'] ?? '');
            $label = __((string) ($row['label_key'] ?? ''));
            $href = '#';
            if ($type === 'link') {
                $href = $this->resolvePrimaryLinkHref($row);
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
    private function resolvePrimaryLinkHref(array $row): string
    {
        if (! empty($row['external_url'])) {
            return (string) $row['external_url'];
        }
        if (isset($row['tour_params'])) {
            return route('tours.index', array_filter((array) $row['tour_params']));
        }
        if (isset($row['route'])) {
            $base = route((string) $row['route'], (array) ($row['params'] ?? []));
            $hash = isset($row['hash']) ? '#'.ltrim((string) $row['hash'], '#') : '';

            return $base.$hash;
        }

        return '#';
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function resolveDropdownLineHref(array $row): string
    {
        return $this->resolvePrimaryLinkHref($row);
    }

    /**
     * @return array<string, list<array{label: string, href: string}>>
     */
    private function buildDropdownPanels(): array
    {
        $panels = config('navigation.dropdown_panels', []);
        if (! is_array($panels)) {
            return [];
        }

        $out = [];
        foreach ($panels as $key => $rows) {
            if (! is_array($rows)) {
                continue;
            }
            $items = [];
            foreach ($rows as $line) {
                if (! is_array($line)) {
                    continue;
                }
                $lk = (string) ($line['label_key'] ?? '');
                $items[] = [
                    'label' => $lk !== '' ? __($lk) : '',
                    'href' => $this->resolveDropdownLineHref($line),
                ];
            }
            $out[(string) $key] = $items;
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

        if (($row['type'] ?? '') === 'dropdown') {
            return $this->isDropdownTriggerActive($request, (string) ($row['panel'] ?? ''));
        }

        if ($id === 'package') {
            return $request->routeIs('tours.index')
                && in_array((string) $request->query('duration', ''), ['4-7', '8+'], true);
        }

        if ($id === 'about') {
            return $request->routeIs('about');
        }

        if (($row['type'] ?? '') === 'link' && ! empty($row['tour_params']) && is_array($row['tour_params'])) {
            if (! $request->routeIs('tours.index')) {
                return false;
            }
            $params = array_filter($row['tour_params'], fn ($v) => $v !== null && $v !== '');
            foreach ($params as $k => $v) {
                if ((string) $request->query($k, '') !== (string) $v) {
                    return false;
                }
            }

            return $params !== [];
        }

        return false;
    }

    private function isDropdownTriggerActive(Request $request, string $panel): bool
    {
        if ($panel === 'other_service') {
            return $this->isOtherServiceDropdownActive($request);
        }

        if ($panel === '' || ! $request->routeIs('tours.index')) {
            return false;
        }

        $lines = config('navigation.dropdown_panels.'.$panel, []);

        return is_array($lines)
            && $this->someTourLineMatchesRequest($request, $lines);
    }

    private function isOtherServiceDropdownActive(Request $request): bool
    {
        $lines = config('navigation.dropdown_panels.other_service', []);
        if (! is_array($lines)) {
            return false;
        }

        foreach ($lines as $line) {
            if (! is_array($line)) {
                continue;
            }
            $routeName = $line['route'] ?? null;
            if (! is_string($routeName) || $routeName === '') {
                continue;
            }
            if ($request->routeIs($routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<mixed>  $lines
     */
    private function someTourLineMatchesRequest(Request $request, array $lines): bool
    {
        foreach ($lines as $line) {
            if (! is_array($line)) {
                continue;
            }
            $tp = array_filter((array) ($line['tour_params'] ?? []));
            if ($tp === []) {
                continue;
            }
            $ok = true;
            foreach ($tp as $k => $v) {
                if ((string) $request->query($k, '') !== (string) $v) {
                    $ok = false;
                    break;
                }
            }
            if ($ok) {
                return true;
            }
        }

        return false;
    }

    private function buildDailyMega(Collection $bySlug): array
    {
        $rows = config('navigation.mega_rows', []);
        $resolved = [];
        foreach ($rows as $rowSet) {
            if (! is_array($rowSet)) {
                continue;
            }
            $resolvedRow = [];
            foreach ($rowSet as $section) {
                if (! is_array($section)) {
                    continue;
                }
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
                    'title' => __((string) ($section['title_key'] ?? '')),
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

    private function collectSlugsFromConfig(): Collection
    {
        $slugs = collect();

        foreach (config('navigation.mega_rows', []) as $rowSet) {
            if (! is_array($rowSet)) {
                continue;
            }
            foreach ($rowSet as $section) {
                if (! is_array($section)) {
                    continue;
                }
                foreach ((array) ($section['slugs'] ?? []) as $slug) {
                    $slugs->push((string) $slug);
                }
            }
        }

        foreach (config('navigation.dropdown_panels', []) as $rows) {
            if (! is_array($rows)) {
                continue;
            }
            foreach ($rows as $line) {
                if (! is_array($line)) {
                    continue;
                }
                $d = data_get($line, 'tour_params.destination');
                if ($d !== null && $d !== '') {
                    $slugs->push((string) $d);
                }
            }
        }

        return $slugs->unique()->values();
    }
}
