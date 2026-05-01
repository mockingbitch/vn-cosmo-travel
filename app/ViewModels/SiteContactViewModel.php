<?php

namespace App\ViewModels;

use App\Services\SettingsService;

class SiteContactViewModel
{
    public function __construct(
        private readonly SettingsService $settings,
    ) {}

    public function email(): ?string
    {
        return $this->stringOrNull('contact.email');
    }

    public function phone(): ?string
    {
        return $this->stringOrNull('contact.phone');
    }

    /**
     * Link for the phone row: WhatsApp (wa.me) when digits can be parsed; otherwise tel: with spaces stripped.
     */
    public function phoneChatHref(): string
    {
        $phone = $this->phone();
        if ($phone === null) {
            return '#';
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits !== '') {
            if (str_starts_with($digits, '84')) {
                $normalized = $digits;
            } elseif (str_starts_with($digits, '0')) {
                $normalized = '84'.substr($digits, 1);
            } elseif (strlen($digits) === 9 && str_starts_with($digits, '9')) {
                $normalized = '84'.$digits;
            } else {
                $normalized = $digits;
            }

            return 'https://wa.me/'.$normalized;
        }

        return 'tel:'.preg_replace('/\s+/', '', $phone);
    }

    public function address(): ?string
    {
        return $this->stringOrNull('contact.address');
    }

    public function mapIframe(): ?string
    {
        return $this->stringOrNull('contact.map_iframe');
    }

    public function facebook(): ?string
    {
        return $this->stringOrNull('social.facebook');
    }

    public function instagram(): ?string
    {
        return $this->stringOrNull('social.instagram');
    }

    public function youtube(): ?string
    {
        return $this->stringOrNull('social.youtube');
    }

    public function tiktok(): ?string
    {
        return $this->stringOrNull('social.tiktok');
    }

    /**
     * @return array<int, array{label: string, url: string, icon: string}>
     */
    public function socialLinks(): array
    {
        $links = [];
        if ($this->facebook()) {
            $links[] = ['label' => 'Facebook', 'url' => (string) $this->facebook(), 'icon' => 'facebook'];
        }
        if ($this->instagram()) {
            $links[] = ['label' => 'Instagram', 'url' => (string) $this->instagram(), 'icon' => 'instagram'];
        }
        if ($this->youtube()) {
            $links[] = ['label' => 'YouTube', 'url' => (string) $this->youtube(), 'icon' => 'youtube'];
        }
        if ($this->tiktok()) {
            $links[] = ['label' => 'TikTok', 'url' => (string) $this->tiktok(), 'icon' => 'tiktok'];
        }

        return $links;
    }

    public function hasContactInfo(): bool
    {
        return $this->email() !== null || $this->phone() !== null || $this->address() !== null;
    }

    public function hasMap(): bool
    {
        return $this->mapIframe() !== null;
    }

    private function stringOrNull(string $key): ?string
    {
        $value = $this->settings->get($key);
        if (! is_string($value)) {
            return null;
        }
        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
