<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HeroBannerSeeder extends Seeder
{
    /**
     * Seeds one current hero banner and archived history rows (newest archive first in admin history).
     * Insert oldest history first so IDs rise toward the live banner.
     */
    public function run(): void
    {
        DB::transaction(function () {
            HeroBanner::query()->delete();

            $now = Carbon::now();

            foreach ($this->definitions() as $row) {
                HeroBanner::query()->create($this->toModelAttributes($row, $now));
            }
        });
    }

    /**
     * @return list<array{
     *     archived_days_ago: int|null,
     *     title: array{en: string, vi: string},
     *     subtitle: array{en: string, vi: string},
     *     cta: array{text: array{en: string, vi: string}, link: string},
     *     media_id: int|null
     * }>
     */
    private function definitions(): array
    {
        return [
            [
                'archived_days_ago' => 21,
                'title' => [
                    'en' => 'Tailor-made Vietnam tours that feel effortless.',
                    'vi' => 'Tour Việt Nam thiết kế riêng, nhẹ nhàng và thoải mái.',
                ],
                'subtitle' => [
                    'en' => 'Tell us your dates and travel style. We propose a comfortable itinerary, fast.',
                    'vi' => 'Chia sẻ ngày đi và phong cách du lịch. Chúng tôi đề xuất lịch trình phù hợp, nhanh chóng.',
                ],
                'cta' => [
                    'text' => ['en' => 'Plan with us', 'vi' => 'Lên kế hoạch cùng chúng tôi'],
                    'link' => '/#booking',
                ],
                'media_id' => null,
            ],
            [
                'archived_days_ago' => 7,
                'title' => [
                    'en' => 'Modern Vietnam travel — clear pricing, quick replies.',
                    'vi' => 'Du lịch Việt Nam hiện đại — giá minh bạch, phản hồi nhanh.',
                ],
                'subtitle' => [
                    'en' => 'From Hanoi to Ho Chi Minh City: curated routes with flexible pacing.',
                    'vi' => 'Từ Hà Nội đến TP. Hồ Chí Minh: hành trình tuyển chọn, nhịp độ linh hoạt.',
                ],
                'cta' => [
                    'text' => ['en' => 'Browse tours', 'vi' => 'Xem danh sách tour'],
                    'link' => '/tours',
                ],
                'media_id' => null,
            ],
            [
                'archived_days_ago' => null,
                'title' => [
                    'en' => 'Discover Vietnam with tours built for comfort, culture, and unforgettable moments.',
                    'vi' => 'Khám phá Việt Nam với tour thoải mái, đậm văn hoá và khoảnh khắc khó quên.',
                ],
                'subtitle' => [
                    'en' => 'Choose a destination and duration, book in minutes — we follow up with a curated plan.',
                    'vi' => 'Chọn điểm đến và thời lượng, đặt tour trong vài phút — chúng tôi phản hồi với lịch trình phù hợp.',
                ],
                'cta' => [
                    'text' => ['en' => 'Explore tours', 'vi' => 'Khám phá tour'],
                    'link' => '/tours',
                ],
                'media_id' => null,
            ],
        ];
    }

    private function toModelAttributes(array $row, Carbon $now): array
    {
        $isCurrent = $row['archived_days_ago'] === null;
        $archivedAt = $isCurrent
            ? null
            : $now->copy()->subDays($row['archived_days_ago']);

        $title = $this->normalizeTranslations($row['title']);
        $subtitle = $this->normalizeTranslations($row['subtitle']);
        $ctaText = $this->normalizeTranslations($row['cta']['text']);

        return [
            // Keep legacy columns (title/subtitle/cta_text) as EN-backed values.
            'title' => $title['en'],
            'subtitle' => $subtitle['en'],
            'cta_text' => $ctaText['en'],

            // Bilingual JSON translations.
            'title_translations' => $title,
            'subtitle_translations' => $subtitle,
            'cta_text_translations' => $ctaText,

            'cta_link' => $row['cta']['link'],
            'media_id' => $row['media_id'],
            'is_current' => $isCurrent,
            'archived_at' => $archivedAt,
        ];
    }

    /**
     * @param array{en?: string|null, vi?: string|null} $value
     * @return array{en: string, vi: string}
     */
    private function normalizeTranslations(array $value): array
    {
        $en = $value['en'] ?? '';
        $vi = $value['vi'] ?? '';

        return [
            'en' => is_string($en) ? $en : '',
            'vi' => is_string($vi) ? $vi : '',
        ];
    }
}
