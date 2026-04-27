<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'title',
    'subtitle',
    'media_id',
    'cta_text',
    'cta_link',
    'is_current',
    'archived_at',
    'title_translations',
    'subtitle_translations',
    'cta_text_translations',
])]
class HeroBanner extends Model
{
    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'archived_at' => 'datetime',
            'title_translations' => 'array',
            'subtitle_translations' => 'array',
            'cta_text_translations' => 'array',
        ];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function getTitleForLocale(string $locale): string
    {
        $t = (array) ($this->title_translations ?? []);
        $value = $t[$locale] ?? $t['en'] ?? null;

        return is_string($value) && $value !== '' ? $value : (string) $this->title;
    }

    public function getSubtitleForLocale(string $locale): ?string
    {
        $t = (array) ($this->subtitle_translations ?? []);
        $value = $t[$locale] ?? $t['en'] ?? null;

        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $this->subtitle;
    }

    public function getCtaTextForLocale(string $locale): ?string
    {
        $t = (array) ($this->cta_text_translations ?? []);
        $value = $t[$locale] ?? $t['en'] ?? null;

        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $this->cta_text;
    }
}
