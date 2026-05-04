<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'status',
    'translations',
    'created_by',
    'updated_by',
])]
class AboutPage extends Model
{
    public const STATUS_ACTIVE = 'active';

    protected function casts(): array
    {
        return [
            'translations' => 'array',
        ];
    }

    /**
     * Resolved title + HTML for the public page, with sensible fallbacks when a locale is incomplete.
     *
     * @return array{title: string, content: string}
     */
    public function resolvedForLocale(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        /** @var list<string> $supported */
        $supported = array_keys((array) config('locales.supported', []));
        $default = (string) config('locales.default', 'en');
        $fallback = (string) config('locales.fallback', 'en');

        $translations = is_array($this->translations) ? $this->translations : [];

        $pick = function (string $loc) use ($translations): ?array {
            if (! isset($translations[$loc]) || ! is_array($translations[$loc])) {
                return null;
            }
            /** @var array<string, mixed> $block */
            $block = $translations[$loc];
            $title = trim((string) ($block['title'] ?? ''));
            $content = trim((string) ($block['content'] ?? ''));

            if ($title === '' || $content === '') {
                return null;
            }

            return ['title' => $title, 'content' => $content];
        };

        foreach (array_unique([$locale, $default, $fallback]) as $loc) {
            if (in_array($loc, $supported, true)) {
                $block = $pick($loc);
                if ($block !== null) {
                    return $block;
                }
            }
        }

        foreach ($supported as $loc) {
            $block = $pick($loc);
            if ($block !== null) {
                return $block;
            }
        }

        return [
            'title' => (string) __('nav.primary.about_us'),
            'content' => '<p></p>',
        ];
    }

    /**
     * @return array{title: string, content: string}
     */
    public function blockForLocale(string $locale): array
    {
        $translations = is_array($this->translations) ? $this->translations : [];
        if (! isset($translations[$locale]) || ! is_array($translations[$locale])) {
            return ['title' => '', 'content' => ''];
        }

        /** @var array<string, mixed> $block */
        $block = $translations[$locale];

        return [
            'title' => isset($block['title']) ? (string) $block['title'] : '',
            'content' => isset($block['content']) ? (string) $block['content'] : '',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
