<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Models\Media;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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
        return $this->media->paginateForAdmin($perPage, $search, $type, $sort);
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
        $used = $this->media->usedCount($mediaId);
        if ($used > 0) {
            throw new DomainException('Media is currently used.');
        }

        $m = $this->media->findOrFail($mediaId);

        Storage::disk('public')->delete($m->file_path);
        $m->delete();
    }
}

