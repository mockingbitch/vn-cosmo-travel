<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'cta_text',
        'cta_link',
        'sort_order',
        'is_active',
        'is_current',
        'archived_at',
        'title_translations',
        'subtitle_translations',
        'cta_text_translations',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'sort_order' => 'integer',
        'archived_at' => 'datetime',
        'title_translations' => 'array',
        'subtitle_translations' => 'array',
        'cta_text_translations' => 'array',
    ];

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

