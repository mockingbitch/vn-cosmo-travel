<?php

namespace App\Application\Media;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Models\Media;
use DomainException;
use Illuminate\Support\Facades\Storage;

class DeleteMediaUseCase
{
    public function __construct(
        private readonly MediaRepositoryInterface $media,
    ) {}

    public function handle(int $mediaId): void
    {
        $used = $this->media->usedCount($mediaId);
        if ($used > 0) {
            throw new DomainException('Media is currently used.');
        }

        $m = $this->media->findOrFail($mediaId);

        // Soft delete the DB record; keep file or remove? We remove the file to avoid orphan storage.
        Storage::disk('public')->delete($m->file_path);
        $m->delete();
    }
}

