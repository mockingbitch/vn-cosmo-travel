<?php

namespace App\Contracts\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PostRepositoryInterface
{
    public function paginateLatest(int $perPage = 9): LengthAwarePaginator;

    public function latest(int $limit = 3): Collection;

    public function findBySlugOrFail(string $slug): Post;

    public function related(int $postId, ?int $categoryId, int $limit = 3): Collection;

    public function adminPaginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function adminCreate(array $data): Post;

    public function adminUpdate(Post $post, array $data): Post;

    public function adminDelete(Post $post): void;
}

