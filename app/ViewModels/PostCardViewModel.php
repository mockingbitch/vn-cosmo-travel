<?php

namespace App\ViewModels;

use App\Models\Post;
use Illuminate\Support\Str;

class PostCardViewModel
{
    public function __construct(
        public readonly Post $post,
    ) {
    }

    public function title(): string
    {
        return $this->post->title;
    }

    public function slug(): string
    {
        return $this->post->slug;
    }

    public function excerpt(int $limit = 120): string
    {
        return Str::limit(strip_tags($this->post->content), $limit);
    }

    public function thumbnailUrl(): string
    {
        if ($this->post->thumbnail) {
            return $this->post->thumbnail;
        }

        return 'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=1200&q=80';
    }
}

