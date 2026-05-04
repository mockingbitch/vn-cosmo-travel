<?php

namespace App\Services\Admin;

use App\Models\ServicePage;

class ServicePageAdminService
{
    /**
     * @return array<string, array{title: string, content: string}>
     */
    private function defaultTranslations(string $type): array
    {
        $titleEn = match ($type) {
            ServicePage::TYPE_AIRPORT_TAXI => 'Airport taxi',
            ServicePage::TYPE_VISA => 'Visa service',
            ServicePage::TYPE_BUS_FLIGHT_TRAIN => 'Bus / flight / train ticket',
            ServicePage::TYPE_SIM => 'SIM card',
            default => 'Service',
        };
        $titleVi = match ($type) {
            ServicePage::TYPE_AIRPORT_TAXI => 'Taxi sân bay',
            ServicePage::TYPE_VISA => 'Dịch vụ visa',
            ServicePage::TYPE_BUS_FLIGHT_TRAIN => 'Vé xe / máy bay / tàu',
            ServicePage::TYPE_SIM => 'SIM điện thoại',
            default => 'Dịch vụ',
        };

        $hintEn = match ($type) {
            ServicePage::TYPE_AIRPORT_TAXI => 'Describe your airport transfer service — edit in Admin → Settings.',
            default => 'Edit this page in Admin → Settings.',
        };
        $hintVi = match ($type) {
            ServicePage::TYPE_AIRPORT_TAXI => 'Mô tả dịch vụ đưa đón sân bay — chỉnh trong Admin → Cài đặt.',
            default => 'Chỉnh trong Admin → Cài đặt.',
        };

        return [
            'en' => [
                'title' => $titleEn,
                'content' => '<p>'.$hintEn.'</p>',
            ],
            'vi' => [
                'title' => $titleVi,
                'content' => '<p>'.$hintVi.'</p>',
            ],
        ];
    }

    public function singleton(string $type): ServicePage
    {
        if (! ServicePage::isAllowedType($type)) {
            abort(404);
        }

        return ServicePage::query()->firstOrCreate(
            ['type' => $type],
            [
                'status' => ServicePage::STATUS_ACTIVE,
                'translations' => $this->defaultTranslations($type),
            ]
        );
    }

    /** @param  array<string, mixed>  $data */
    public function update(ServicePage $page, array $data): ServicePage
    {
        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $loc => &$block) {
                if (is_array($block) && isset($block['content']) && is_string($block['content'])) {
                    $block['content'] = $this->stripFontFamily($block['content']);
                }
            }
            unset($block);
        }

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $data['status'] = ServicePage::STATUS_ACTIVE;

        $page->update($data);

        return $page->fresh();
    }

    private function stripFontFamily(string $html): string
    {
        $html = preg_replace("~font-family\\s*:\\s*[^;\"']+;?~i", '', $html) ?? $html;
        $html = preg_replace('/\\sface\\s*=\\s*(\"[^\"]*\"|\\\'[^\\\']*\\\'|[^\\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace('/\\sstyle\\s*=\\s*(\"\\s*\"|\\\'\\s*\\\')/i', '', $html) ?? $html;
        $html = preg_replace_callback('/\\sstyle\\s*=\\s*(\"[^\"]*\"|\\\'[^\\\']*\\\')/i', function ($m) {
            $raw = $m[1];
            $quote = $raw[0];
            $inner = substr($raw, 1, -1);
            $inner = preg_replace('/\\s*;\\s*;+/', ';', $inner) ?? $inner;
            $inner = trim((string) $inner, " ;\t\n\r\0\x0B");
            if ($inner === '') {
                return '';
            }

            return ' style='.$quote.$inner.$quote;
        }, $html) ?? $html;

        return $html;
    }
}
