<?php

namespace App\Http\Controllers\Admin;

use App\Application\Media\DeleteMediaUseCase;
use App\Application\Media\GetMediaListUseCase;
use App\Application\Media\UploadMediaUseCase;
use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request, GetMediaListUseCase $list): View
    {
        $search = $request->string('q')->toString();
        $type = $request->string('type')->toString() ?: null;

        return view('admin.media.index', [
            'title' => __('Media'),
            'media' => $list->handle(30, $search !== '' ? $search : null, $type),
            'q' => $search,
            'type' => $type,
        ]);
    }

    public function store(Request $request, UploadMediaUseCase $upload): RedirectResponse
    {
        $validated = $request->validate([
            'files' => ['required'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $files = $request->file('files', []);
        $upload->handle(is_array($files) ? $files : [$files], $validated['alt_text'] ?? null);

        return redirect()->route('admin.media.index')->with('status', __('flash.media.uploaded'));
    }

    public function destroy(Media $media, DeleteMediaUseCase $delete): RedirectResponse
    {
        try {
            $delete->handle((int) $media->id);
            return redirect()->route('admin.media.index')->with('status', __('flash.media.deleted'));
        } catch (\DomainException $e) {
            return redirect()->route('admin.media.index')->with('status', __('flash.media.in_use'));
        }
    }

    public function picker(Request $request, GetMediaListUseCase $list): JsonResponse
    {
        $search = $request->string('q')->toString();
        $type = $request->string('type')->toString() ?: null;

        $p = $list->handle(24, $search !== '' ? $search : null, $type);

        return response()->json([
            'data' => $p->getCollection()->map(fn (Media $m) => [
                'id' => (int) $m->id,
                'file_name' => (string) $m->file_name,
                'mime_type' => (string) $m->mime_type,
                'size' => (int) $m->size,
                'alt_text' => $m->alt_text,
                'url' => $m->url(),
                'used_count' => (int) ($m->usages_count ?? 0),
            ])->values(),
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
            ->map(fn (Media $m) => [
                'id' => (int) $m->id,
                'file_name' => (string) $m->file_name,
                'mime_type' => (string) $m->mime_type,
                'size' => (int) $m->size,
                'alt_text' => $m->alt_text,
                'url' => $m->url(),
                'used_count' => (int) ($m->usages_count ?? 0),
            ])
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
}

