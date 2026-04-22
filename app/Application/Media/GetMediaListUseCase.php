<?php

namespace App\Application\Media;

use App\Contracts\Interfaces\MediaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetMediaListUseCase
{
    public function __construct(
        private readonly MediaRepositoryInterface $media,
    ) {}

    /**
     * @return LengthAwarePaginator<\App\Models\Media>
     */
    public function handle(int $perPage = 30, ?string $search = null, ?string $type = null): LengthAwarePaginator
    {
        return $this->media->paginateForAdmin($perPage, $search, $type);
    }
}

