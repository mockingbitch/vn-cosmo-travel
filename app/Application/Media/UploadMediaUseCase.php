<?php

namespace App\Application\Media;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class UploadMediaUseCase
{
    public function __construct(
        private readonly MediaRepositoryInterface $media,
    ) {}

    /**
     * @param  array<int, UploadedFile>  $files
     * @return Collection<int, Media>
     */
    public function handle(array $files, ?string $altText = null): Collection
    {
        return collect($files)
            ->filter(fn ($f) => $f instanceof UploadedFile)
            ->map(fn (UploadedFile $file) => $this->media->storeUploadedFile($file, $altText))
            ->values();
    }
}

