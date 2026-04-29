<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\Admin\MediaAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request, MediaAdminService $media): View
    {
        $search = $request->string('q')->toString();
        $type = $request->string('type')->toString() ?: null;
        $sort = $request->string('sort')->toString() ?: null;

        return view('admin.media.index', [
            'title' => __('Media'),
            'media' => $media->paginate(30, $search !== '' ? $search : null, $type, $sort),
            'q' => $search,
            'type' => $type,
            'sort' => $sort,
        ]);
    }

    /**
     * GET /admin/media/{id} is not a real screen — always send editors to the library list.
     *
     * Resource wildcard is typically `{medium}` (singular of "media"); `media/{media}/usages` uses `{media}`.
     */
    public function show(Request $request): RedirectResponse
    {
        $this->resolveMediaFromResourceRoute($request);

        return redirect()->route('admin.media.index');
    }

    public function store(Request $request, MediaAdminService $media): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'image', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $files = $request->file('files', []);
        $uploaded = $media->upload(is_array($files) ? $files : [$files], $validated['alt_text'] ?? null);

        if ($request->expectsJson()) {
            $uploaded->loadCount('usages');

            return response()->json([
                'data' => $uploaded->map(fn (Media $m) => $this->mediaPickerPayload($m))->values(),
            ]);
        }

        return redirect()->route('admin.media.index')->with('status', __('flash.media.uploaded'));
    }

    public function destroy(Request $request, MediaAdminService $service): RedirectResponse
    {
        $listQuery = array_filter(
            $request->only(['q', 'type', 'page', 'sort']),
            fn ($v) => $v !== null && $v !== '',
        );

        $media = $this->resolveMediaFromResourceRoute($request);
        $service->delete((int) $media->id);

        return redirect()->route('admin.media.index', $listQuery)->with('status', __('flash.media.deleted'));
    }

    public function bulkDestroy(Request $request, MediaAdminService $service): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'min:1'],
        ]);

        $deleted = $service->bulkDelete($validated['ids']);

        $listQuery = array_filter(
            $request->only(['q', 'type', 'page', 'sort']),
            fn ($v) => $v !== null && $v !== '',
        );

        return redirect()
            ->route('admin.media.index', $listQuery)
            ->with('status', __('flash.media.bulk_deleted', ['count' => $deleted]));
    }

    public function picker(Request $request, MediaAdminService $media): JsonResponse
    {
        $search = $request->string('q')->toString();
        $type = $request->string('type')->toString() ?: null;
        $sort = $request->string('sort')->toString() ?: null;

        $p = $media->paginate(24, $search !== '' ? $search : null, $type, $sort);

        return response()->json([
            'data' => $p->getCollection()->map(fn (Media $m) => $this->mediaPickerPayload($m))->values(),
            'next_page_url' => $p->nextPageUrl(),
        ]);
    }

    public function byIds(Request $request): JsonResponse
    {
        $ids = collect(explode(',', (string) $request->query('ids', '')))
            ->map(fn ($v) => (int) trim($v))
            ->filter(fn ($v) => $v > 0)
            ->unique()
            ->take(100)
            ->values();

        $items = Media::query()
            ->withCount('usages')
            ->whereIn('id', $ids)
            ->get()
            ->map(fn (Media $m) => $this->mediaPickerPayload($m))
            ->values();

        return response()->json(['data' => $items]);
    }

    public function usages(Media $media, MediaRepositoryInterface $repo): JsonResponse
    {
        return response()->json([
            'used_count' => (int) ($media->usages()->count()),
            'items' => $repo->usageSummary((int) $media->id),
        ]);
    }

    /**
     * Laravel names the resource route parameter `medium` (singular of "media"), not `media`.
     */
    private function resolveMediaFromResourceRoute(Request $request): Media
    {
        $raw = $request->route('medium') ?? $request->route('media');

        if ($raw instanceof Media) {
            return $raw;
        }

        $id = (int) $raw;
        abort_if($id < 1, 404);

        return Media::query()->findOrFail($id);
    }

    /**
     * @return array<string, mixed>
     */
    private function mediaPickerPayload(Media $m): array
    {
        return [
            'id' => (int) $m->id,
            'file_name' => $m->displayName(),
            'mime_type' => (string) $m->mime_type,
            'size' => (int) $m->size,
            'alt_text' => $m->alt_text,
            'url' => $m->url(),
            'used_count' => (int) ($m->usages_count ?? 0),
        ];
    }
}
