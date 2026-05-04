<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'type',
    'status',
    'translations',
    'created_by',
    'updated_by',
])]
class ServicePage extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const TYPE_AIRPORT_TAXI = 'airport-taxi';

    public const TYPE_VISA = 'visa-service';

    public const TYPE_BUS_FLIGHT_TRAIN = 'bus-flight-train-ticket';

    public const TYPE_SIM = 'sim-card';

    /** @return list<string> */
    public static function allowedTypes(): array
    {
        return [
            self::TYPE_AIRPORT_TAXI,
            self::TYPE_VISA,
            self::TYPE_BUS_FLIGHT_TRAIN,
            self::TYPE_SIM,
        ];
    }

    /** Regex fragment for Route::where('type', …). */
    public static function allowedTypesRoutePattern(): string
    {
        return implode('|', self::allowedTypes());
    }

    public static function isAllowedType(string $type): bool
    {
        return in_array($type, self::allowedTypes(), true);
    }

    public static function publicRouteName(string $type): string
    {
        return match ($type) {
            self::TYPE_AIRPORT_TAXI => 'airport-taxi',
            self::TYPE_VISA => 'visa-service',
            self::TYPE_BUS_FLIGHT_TRAIN => 'bus-flight-train-ticket',
            self::TYPE_SIM => 'sim-card',
            default => '',
        };
    }

    public static function adminTitleLangKey(string $type): string
    {
        return 'admin.service_pages.page_title_'.str_replace('-', '_', $type);
    }

    protected function casts(): array
    {
        return [
            'translations' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'type';
    }

    public function fallbackNavLabelKey(): string
    {
        return match ($this->type) {
            self::TYPE_AIRPORT_TAXI => 'nav.sub.airport_taxi',
            self::TYPE_VISA => 'nav.sub.visa_service',
            self::TYPE_BUS_FLIGHT_TRAIN => 'nav.sub.bus_flight_train',
            self::TYPE_SIM => 'nav.sub.sim_card',
            default => 'nav.primary.other_service',
        };
    }

    /**
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
            'title' => (string) __($this->fallbackNavLabelKey()),
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
