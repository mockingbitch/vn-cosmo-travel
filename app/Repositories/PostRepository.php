<?php

namespace App\Repositories;

use App\Contracts\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PostRepository implements PostRepositoryInterface
{
    public function paginateLatest(int $perPage = 9): LengthAwarePaginator
    {
        return Post::query()
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function latest(int $limit = 3): Collection
    {
        return Post::query()
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function findBySlugOrFail(string $slug): Post
    {
        return Post::query()
            ->with(['category'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function related(int $postId, ?int $categoryId, int $limit = 3): Collection
    {
        return Post::query()
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->where('id', '!=', $postId)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function adminPaginate(int $perPage = 15): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function adminCreate(array $data): Post
    {
        return Post::query()->create($data);
    }

    public function adminUpdate(Post $post, array $data): Post
    {
        $post->update($data);

        return $post->fresh();
    }

    public function adminDelete(Post $post): void
    {
        $post->delete();
    }
}

