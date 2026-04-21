<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Models\HeroBanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class HeroBannerAdminService
{
    public function __construct(
        private readonly HeroBannerRepositoryInterface $banners,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->banners->adminPaginate($perPage);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, ?UploadedFile $image = null): HeroBanner
    {
        if ($image) {
            $data['image_path'] = $image->storePublicly('hero-banners', ['disk' => 'public']);
        }

        return $this->banners->adminCreate($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(HeroBanner $banner, array $data, ?UploadedFile $image = null): HeroBanner
    {
        if ($image) {
            $data['image_path'] = $image->storePublicly('hero-banners', ['disk' => 'public']);
        }

        return $this->banners->adminUpdate($banner, $data);
    }

    public function delete(HeroBanner $banner): void
    {
        $this->banners->adminDelete($banner);
    }
}

