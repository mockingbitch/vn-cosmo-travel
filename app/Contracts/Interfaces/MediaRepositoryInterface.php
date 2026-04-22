<?php

namespace App\Contracts\Interfaces;

use App\Models\Media;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface MediaRepositoryInterface
{
    public function storeUploadedFile(UploadedFile $file, ?string $altText = null): Media;

    /**
     * @return LengthAwarePaginator<Media>
     */
    public function paginateForAdmin(
        int $perPage = 30,
        ?string $search = null,
        ?string $type = null,
    ): LengthAwarePaginator;

    public function findOrFail(int $id): Media;

    public function usedCount(int $mediaId): int;

    /**
     * @return Collection<int, array{model_type: string, model_id: int, field: string|null, count: int}>
     */
    public function usageSummary(int $mediaId): Collection;
}

