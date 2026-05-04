<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Requests\Admin\UpdatePostStatusRequest;
use App\Models\Post;
use App\Services\Admin\PostAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(PostAdminService $posts): View
    {
        return view('admin.posts.index', [
            'posts' => $posts->paginate(15),
        ]);
    }

    public function create(CategoryRepositoryInterface $categories): View
    {
        return view('admin.posts.create', [
            'categories' => $categories->all(),
        ]);
    }

    public function store(StorePostRequest $request, PostAdminService $posts): RedirectResponse
    {
        $posts->create($request->validated());

        return redirect()->route('admin.posts.index')->with('status', __('flash.post.created'));
    }

    public function edit(Post $post, CategoryRepositoryInterface $categories): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $categories->all(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post, PostAdminService $posts): RedirectResponse
    {
        $posts->update($post, $request->validated());

        return redirect()->route('admin.posts.index')->with('status', __('flash.post.updated'));
    }

    public function updateStatus(UpdatePostStatusRequest $request, Post $post, PostAdminService $posts): RedirectResponse
    {
        $posts->updateStatus($post, $request->validated('status'));

        return redirect()
            ->route('admin.posts.index', $this->postListQueryFromRequest($request))
            ->with('status', __('flash.post.updated'));
    }

    /**
     * @return array<string, int>
     */
    private function postListQueryFromRequest(Request $request): array
    {
        $query = [];

        if ($request->filled('page')) {
            $page = (int) $request->input('page');
            if ($page > 0) {
                $query['page'] = $page;
            }
        }

        return $query;
    }

    public function destroy(Post $post, PostAdminService $posts): RedirectResponse
    {
        $posts->delete($post);

        return redirect()->route('admin.posts.index')->with('status', __('flash.post.deleted'));
    }
}
