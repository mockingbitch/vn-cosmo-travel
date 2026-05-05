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
    public function index(Request $request, PostAdminService $posts, CategoryRepositoryInterface $categories): View
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'status' => trim((string) $request->query('status', '')),
            'category_id' => trim((string) $request->query('category_id', '')),
        ];

        return view('admin.posts.index', [
            'posts' => $posts->paginate(15, $filters),
            'filters' => $filters,
            'categories' => $categories->all(),
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

        if ($request->filled('q')) {
            $query['q'] = trim((string) $request->input('q'));
        }

        if ($request->filled('status')) {
            $query['status'] = (string) $request->input('status');
        }

        if ($request->filled('category_id')) {
            $query['category_id'] = (string) $request->input('category_id');
        }

        return $query;
    }

    public function destroy(Post $post, PostAdminService $posts): RedirectResponse
    {
        $posts->delete($post);

        return redirect()->route('admin.posts.index')->with('status', __('flash.post.deleted'));
    }
}
