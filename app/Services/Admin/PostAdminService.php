<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PostAdminService
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->posts->adminPaginate($perPage);
    }

    public function create(array $data): Post
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title']);

        return $this->posts->adminCreate($data);
    }

    public function update(Post $post, array $data): Post
    {
        $title = $data['title'] ?? $post->title;
        $slugInput = array_key_exists('slug', $data) ? $data['slug'] : $post->slug;
        $data['slug'] = $this->uniqueSlug($slugInput, $title, $post->id);

        return $this->posts->adminUpdate($post, $data);
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
}
