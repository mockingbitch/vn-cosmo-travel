<?php

namespace App\Services;

use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PostService
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
    ) {
    }

    public function paginateLatest(int $perPage = 9): LengthAwarePaginator
    {
        return $this->posts->paginateLatest($perPage);
    }

    public function latest(int $limit = 3): Collection
    {
        return $this->posts->latest($limit);
    }

    public function detail(string $slug): Post
    {
        return $this->posts->findBySlugOrFail($slug);
    }

    public function related(Post $post, int $limit = 3): Collection
    {
        return $this->posts->related($post->id, $post->category_id, $limit);
    }
}

