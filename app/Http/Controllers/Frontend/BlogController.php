<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use App\ViewModels\PostCardViewModel;
use App\ViewModels\SeoViewModel;

class BlogController extends Controller
{
    public function __construct(
        private readonly PostService $postService,
    ) {
    }

    public function index()
    {
        $posts = $this->postService->paginateLatest(9);
        $posts->setCollection($posts->getCollection()->map(fn ($p) => new PostCardViewModel($p)));

        return view('pages.blog.index', [
            'seo' => new SeoViewModel(
                title: __('seo.blog_index.title'),
                description: __('seo.blog_index.description'),
            ),
            'posts' => $posts,
        ]);
    }

    public function show(Post $post)
    {
        $post = $this->postService->detail($post->slug);
        $related = $this->postService->related($post, 3)->map(fn ($p) => new PostCardViewModel($p));

        return view('pages.blog.show', [
            'seo' => new SeoViewModel(
                title: $post->title.' — '.__('Blog suffix'),
                description: \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 155),
            ),
            'post' => $post,
            'relatedPosts' => $related,
        ]);
    }
}
