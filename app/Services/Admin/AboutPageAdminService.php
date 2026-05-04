<?php

namespace App\Services\Admin;

use App\Models\AboutPage;

class AboutPageAdminService
{
    /**
     * @return array<string, array{title: string, content: string}>
     */
    private static function defaultTranslations(): array
    {
        return [
            'en' => [
                'title' => 'About us',
                'content' => '<p>Tell your story here — edit this page in Admin → Settings → About us.</p>',
            ],
            'vi' => [
                'title' => 'Về chúng tôi',
                'content' => '<p>Viết giới thiệu tại đây — chỉnh trong Admin → Cài đặt → Về chúng tôi.</p>',
            ],
        ];
    }

    public function singleton(): AboutPage
    {
        $existing = AboutPage::query()->first();
        if ($existing instanceof AboutPage) {
            return $existing;
        }

        return AboutPage::query()->create([
            'status' => AboutPage::STATUS_ACTIVE,
            'translations' => self::defaultTranslations(),
        ]);
    }

    /** @param  array<string, mixed>  $data */
    public function update(AboutPage $page, array $data): AboutPage
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

        $data['status'] = AboutPage::STATUS_ACTIVE;

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
