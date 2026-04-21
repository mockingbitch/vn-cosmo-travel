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
    public function update(array $data, array $files = []): void
    {
        $this->settings->set('site.name', $data['site_name'] ?? null);
        $this->settings->set('contact.email', $data['contact_email'] ?? null);
        $this->settings->set('contact.phone', $data['contact_phone'] ?? null);
        $this->settings->set('contact.address', $data['contact_address'] ?? null);
        $this->settings->set('contact.map_iframe', $data['google_map_iframe'] ?? null);
        $this->settings->set('social.facebook', $data['facebook'] ?? null);
        $this->settings->set('social.instagram', $data['instagram'] ?? null);
        $this->settings->set('social.youtube', $data['youtube'] ?? null);
        $this->settings->set('social.tiktok', $data['tiktok'] ?? null);

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
}

