<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Models\HeroBanner;
use App\Models\Media;
use App\Models\MediaUsage;
use App\Models\Post;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaAdminService
{
    public function __construct(
        private readonly MediaRepositoryInterface $media,
    ) {}

    /**
     * @return LengthAwarePaginator<Media>
     */
    public function paginate(int $perPage = 30, ?string $search = null, ?string $type = null, ?string $sort = null): LengthAwarePaginator
    {
        return $this->media->paginateForAdmin($perPage, $search, $type);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return Collection<int, Media>
     */
    public function upload(array $files, ?string $altText = null): Collection
    {
        return collect($files)
            ->filter(fn ($f) => $f instanceof UploadedFile)
            ->map(fn (UploadedFile $file) => $this->media->storeUploadedFile($file, $altText))
            ->values();
    }

    public function delete(int $mediaId): void
    {
        $m = $this->media->findOrFail($mediaId);

        DB::transaction(function () use ($m, $mediaId): void {
            MediaUsage::query()->where('media_id', $mediaId)->delete();

            Tour::query()->where('thumbnail_media_id', $mediaId)->update([
                'thumbnail_media_id' => null,
                'thumbnail' => null,
            ]);

            Post::query()->where('thumbnail_media_id', $mediaId)->update([
                'thumbnail_media_id' => null,
            ]);

            HeroBanner::query()->where('media_id', $mediaId)->update([
                'media_id' => null,
            ]);

            Storage::disk('public')->delete($m->file_path);

            $m->delete();
        });
    }

    /**
     * Delete many media records in one transaction. Returns the count of records actually removed.
     *
     * @param  array<int, int>  $mediaIds
     */
    public function bulkDelete(array $mediaIds): int
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $mediaIds), fn ($v) => $v > 0)));
        if ($ids === []) {
            return 0;
        }

        return DB::transaction(function () use ($ids): int {
            /** @var \Illuminate\Database\Eloquent\Collection<int, Media> $items */
            $items = Media::query()->whereIn('id', $ids)->get();
            if ($items->isEmpty()) {
                return 0;
            }

            $existingIds = $items->pluck('id')->map(fn ($v) => (int) $v)->all();

            MediaUsage::query()->whereIn('media_id', $existingIds)->delete();

            Tour::query()->whereIn('thumbnail_media_id', $existingIds)->update([
                'thumbnail_media_id' => null,
                'thumbnail' => null,
            ]);

            Post::query()->whereIn('thumbnail_media_id', $existingIds)->update([
                'thumbnail_media_id' => null,
            ]);

            HeroBanner::query()->whereIn('media_id', $existingIds)->update([
                'media_id' => null,
            ]);

            foreach ($items as $item) {
                if (! empty($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }
            }

            Media::query()->whereIn('id', $existingIds)->delete();

            return count($existingIds);
        });
    }
}
