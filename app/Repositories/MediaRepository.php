<?php

namespace App\Repositories;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Models\Media;
use App\Models\MediaUsage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MediaRepository implements MediaRepositoryInterface
{
    public function storeUploadedFile(UploadedFile $file, ?string $altText = null): Media
    {
        $ext = strtolower((string) ($file->getClientOriginalExtension() ?: $file->extension() ?: ''));
        $ext = $ext !== '' ? $ext : 'bin';

        // ddmmyy (ngày tháng năm 2 chữ số) + 10 ký tự ngẫu nhiên, ví dụ 290426_abcxyzdefg.jpg
        $stamp = now()->format('dmy');
        $suffix = Str::lower(Str::random(10));
        $safeFileName = $stamp.'_'.$suffix.'.'.$ext;
        $path = $file->storePubliclyAs('media', $safeFileName, ['disk' => 'public']);

        return Media::query()->create([
            // Keep the DB filename identical to what is stored on disk.
            'file_name' => $safeFileName,
            'file_path' => $path,
            'mime_type' => (string) ($file->getClientMimeType() ?? 'application/octet-stream'),
            'size' => (int) $file->getSize(),
            'alt_text' => $altText,
        ]);
    }

    public function paginateForAdmin(int $perPage = 30, ?string $search = null, ?string $type = null): LengthAwarePaginator
    {
        $q = Media::query()
            ->withCount('usages')
            ->latest('id');

        if (is_string($search) && $search !== '') {
            $q->where('file_name', 'like', '%'.$search.'%');
        }

        if ($type === 'image') {
            $q->where('mime_type', 'like', 'image/%');
        } elseif ($type === 'video') {
            $q->where('mime_type', 'like', 'video/%');
        }

        return $q->paginate($perPage)->withQueryString();
    }

    public function findOrFail(int $id): Media
    {
        return Media::query()->withCount('usages')->findOrFail($id);
    }

    public function usedCount(int $mediaId): int
    {
        return MediaUsage::query()->where('media_id', $mediaId)->count();
    }

    public function usageSummary(int $mediaId): Collection
    {
        return DB::table('media_usages')
            ->selectRaw('model_type, model_id, field, count(*) as count')
            ->where('media_id', $mediaId)
            ->groupBy('model_type', 'model_id', 'field')
            ->orderBy('model_type')
            ->orderBy('model_id')
            ->get()
            ->map(fn ($r) => [
                'model_type' => (string) $r->model_type,
                'model_id' => (int) $r->model_id,
                'field' => $r->field !== null ? (string) $r->field : null,
                'count' => (int) $r->count,
            ]);
    }
}
