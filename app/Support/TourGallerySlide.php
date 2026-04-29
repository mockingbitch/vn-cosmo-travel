<?php

namespace App\Support;

final class TourGallerySlide
{
    public function __construct(
        public readonly string $type,
        public readonly string $src,
        public readonly ?string $embedUrl,
        public readonly ?string $posterUrl,
    ) {}

    public static function fromUrl(string $raw): self
    {
        $raw = trim($raw);
        if ($raw === '') {
            return new self('image', '', null, null);
        }

        $id = self::youtubeVideoId($raw);
        if ($id !== null && preg_match('/^[a-zA-Z0-9_-]{11}$/', $id)) {
            return new self(
                'youtube',
                $raw,
                'https://www.youtube.com/embed/'.$id.'?rel=0&modestbranding=1',
                'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg',
            );
        }

        return new self('image', $raw, null, null);
    }

    /**
     * @return array{type: string, src: string, embedUrl: string|null, posterUrl: string|null}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'src' => $this->src,
            'embedUrl' => $this->embedUrl,
            'posterUrl' => $this->posterUrl,
        ];
    }

    private static function youtubeVideoId(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/watch\?(?:[^#]*&)?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([a-zA-Z0-9_-]{11})~', $url, $m)) {
            return $m[1];
        }

        if (preg_match('~[?&]v=([a-zA-Z0-9_-]{11})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }
}
