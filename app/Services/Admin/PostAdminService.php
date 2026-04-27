<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Services\Media\MediaUsageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PostAdminService
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
        private readonly MediaUsageService $mediaUsage,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->posts->adminPaginate($perPage);
    }

    public function create(array $data): Post
    {
        if (array_key_exists('content', $data) && is_string($data['content'])) {
            $data['content'] = $this->stripFontFamily($data['content']);
        }

        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title']);

        $post = $this->posts->adminCreate($data);
        $this->mediaUsage->syncSingle(
            array_key_exists('thumbnail_media_id', $data) ? (int) $data['thumbnail_media_id'] : null,
            $post,
            'thumbnail',
        );

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        if (array_key_exists('content', $data) && is_string($data['content'])) {
            $data['content'] = $this->stripFontFamily($data['content']);
        }

        $title = $data['title'] ?? $post->title;
        $slugInput = array_key_exists('slug', $data) ? $data['slug'] : $post->slug;
        $data['slug'] = $this->uniqueSlug($slugInput, $title, $post->id);

        $updated = $this->posts->adminUpdate($post, $data);
        if (array_key_exists('thumbnail_media_id', $data)) {
            $this->mediaUsage->syncSingle(
                $data['thumbnail_media_id'] !== null ? (int) $data['thumbnail_media_id'] : null,
                $updated,
                'thumbnail',
            );
        }

        return $updated;
    }

    public function delete(Post $post): void
    {
        $this->posts->adminDelete($post);
    }

    private function uniqueSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug !== null && $slug !== '' ? $slug : $title);
        if ($base === '') {
            $base = 'post';
        }

        $candidate = $base;
        $i = 2;
        while (
            Post::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }

    private function stripFontFamily(string $html): string
    {
        // Remove inline font-family declarations from style attributes.
        $html = preg_replace("~font-family\\s*:\\s*[^;\"']+;?~i", '', $html) ?? $html;

        // Remove deprecated <font face="..."> attributes.
        $html = preg_replace('/\\sface\\s*=\\s*(\"[^\"]*\"|\\\'[^\\\']*\\\'|[^\\s>]+)/i', '', $html) ?? $html;

        // Clean up empty style attributes left behind.
        $html = preg_replace('/\\sstyle\\s*=\\s*(\"\\s*\"|\\\'\\s*\\\')/i', '', $html) ?? $html;

        // Normalize stray semicolons/spaces inside style="".
        $html = preg_replace_callback('/\\sstyle\\s*=\\s*(\"[^\"]*\"|\\\'[^\\\']*\\\')/i', function ($m) {
            $raw = $m[1];
            $quote = $raw[0];
            $inner = substr($raw, 1, -1);
            $inner = preg_replace('/\\s*;\\s*;+/',';', $inner) ?? $inner;
            $inner = trim((string) $inner, " ;\t\n\r\0\x0B");
            if ($inner === '') {
                return '';
            }
            return ' style='.$quote.$inner.$quote;
        }, $html) ?? $html;

        return $html;
    }
}
