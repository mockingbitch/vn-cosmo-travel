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
     * Digits-only WhatsApp number from the contact phone setting; VN local
     * numbers get the 84 country code. Null when no digits can be parsed.
     */
    public function whatsappNumber(): ?string
    {
        $phone = $this->phone();
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '84')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '84'.substr($digits, 1);
        }

        if (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            return '84'.$digits;
        }

        return $digits;
    }

    /**
     * wa.me link for WhatsApp-only entry points (floating button, CTAs), with
     * an optional prefilled message. Null when the setting holds no number.
     */
    public function whatsappUrl(?string $message = null): ?string
    {
        $number = $this->whatsappNumber();
        if ($number === null) {
            return null;
        }

        $url = 'https://wa.me/'.$number;
        $message = $message !== null ? trim($message) : '';

        return $message === '' ? $url : $url.'?text='.rawurlencode($message);
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

        $number = $this->whatsappNumber();
        if ($number !== null) {
            return 'https://wa.me/'.$number;
        }

        return 'tel:'.preg_replace('/\s+/', '', $phone);
    }

    /**
     * @return list<string>
     */
    public function addresses(): array
    {
        $stored = $this->settings->get('contact.addresses');
        if (is_array($stored)) {
            $addresses = [];
            foreach ($stored as $row) {
                $value = trim((string) $row);
                if ($value !== '') {
                    $addresses[] = $value;
                }
            }

            return $addresses;
        }

        $legacy = $this->stringOrNull('contact.address');

        return $legacy !== null ? [$legacy] : [];
    }

    public function address(): ?string
    {
        $addresses = $this->addresses();

        return $addresses[0] ?? null;
    }

    public function mapIframe(): ?string
    {
        return $this->stringOrNull('contact.map_iframe');
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public function socialLinks(): array
    {
        $stored = $this->settings->get('social.links');
        if (is_array($stored)) {
            $links = [];
            foreach ($stored as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $label = isset($row['label']) ? trim((string) $row['label']) : '';
                $url = isset($row['url']) ? trim((string) $row['url']) : '';
                if ($url === '') {
                    continue;
                }
                $links[] = [
                    'label' => $label !== '' ? $label : $url,
                    'url' => $url,
                ];
            }

            return $links;
        }

        $legacy = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
        ];
        $links = [];
        foreach ($legacy as $key => $label) {
            $url = $this->stringOrNull('social.'.$key);
            if ($url !== null) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links;
    }

    public function hasContactInfo(): bool
    {
        return $this->email() !== null
            || $this->phone() !== null
            || $this->addresses() !== [];
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
