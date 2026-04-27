<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\SettingRepositoryInterface;
use App\Services\SettingsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingAdminService
{
    public function __construct(
        private readonly SettingRepositoryInterface $settings,
        private readonly SettingsService $settingsService,
    ) {}

    /**
     * @param array<string, mixed> $data
     * @param array<string, UploadedFile|null> $files
     */
    public function updateGeneral(array $data, array $files = []): void
    {
        $this->settings->set('site.name', $data['site_name'] ?? null);

        if (!empty($files['logo']) && $files['logo'] instanceof UploadedFile) {
            $path = $files['logo']->storePublicly('settings', ['disk' => 'public']);
            $this->settings->set('site.logo_path', $path);
        }

        if (!empty($files['favicon']) && $files['favicon'] instanceof UploadedFile) {
            $path = $files['favicon']->storePublicly('settings', ['disk' => 'public']);
            $this->settings->set('site.favicon_path', $path);
        }

        $this->settingsService->forgetCache();
    }

    /** @param array<string, mixed> $data */
    public function updateContact(array $data): void
    {
        $this->settings->set('contact.email', $data['contact_email'] ?? null);
        $this->settings->set('contact.phone', $data['contact_phone'] ?? null);
        $this->settings->set('contact.address', $data['contact_address'] ?? null);
        $this->settings->set('contact.map_iframe', $data['google_map_iframe'] ?? null);

        $this->settingsService->forgetCache();
    }

    /** @param array<string, mixed> $data */
    public function updateSocial(array $data): void
    {
        $this->settings->set('social.facebook', $data['facebook'] ?? null);
        $this->settings->set('social.instagram', $data['instagram'] ?? null);
        $this->settings->set('social.youtube', $data['youtube'] ?? null);
        $this->settings->set('social.tiktok', $data['tiktok'] ?? null);

        $this->settingsService->forgetCache();
    }

    /** @param array<string, mixed> $data */
    public function updateHomeWhy(array $data): void
    {
        if (array_key_exists('home_why', $data) && is_array($data['home_why'])) {
            $this->settings->set('content.home_why', $this->normalizeHomeWhy($data['home_why']));
        }

        $this->settingsService->forgetCache();
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, array{title: string, subtitle: string, items: list<array{title: string, desc: string}>}>
     */
    private function normalizeHomeWhy(array $input): array
    {
        $out = [];
        foreach (['vi', 'en'] as $loc) {
            /** @var array<string, mixed> $b */
            $b = is_array($input[$loc] ?? null) ? $input[$loc] : [];
            $items = [];
            for ($i = 0; $i < 4; $i++) {
                /** @var array<string, mixed> $it */
                $it = is_array($b['items'][$i] ?? null) ? $b['items'][$i] : [];
                $items[] = [
                    'title' => isset($it['title']) ? trim((string) $it['title']) : '',
                    'desc' => isset($it['desc']) ? trim((string) $it['desc']) : '',
                ];
            }
            $out[$loc] = [
                'title' => isset($b['title']) ? trim((string) $b['title']) : '',
                'subtitle' => isset($b['subtitle']) ? trim((string) $b['subtitle']) : '',
                'items' => $items,
            ];
        }

        return $out;
    }
}

