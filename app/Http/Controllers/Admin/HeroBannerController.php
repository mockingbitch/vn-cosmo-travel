<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroBannerRequest;
use App\Http\Requests\Admin\UpdateHeroBannerRequest;
use App\Models\HeroBanner;
use App\Services\Admin\HeroBannerAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    public function index(HeroBannerAdminService $banners): View
    {
        return view('admin.banners.index', [
            'banners' => $banners->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(StoreHeroBannerRequest $request, HeroBannerAdminService $banners): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $banners->create($data, $request->file('image'));

        return redirect()->route('admin.banners.index')->with('status', __('flash.banner.created'));
    }

    public function edit(HeroBanner $banner): View
    {
        return view('admin.banners.edit', [
            'banner' => $banner,
        ]);
    }

    public function update(UpdateHeroBannerRequest $request, HeroBanner $banner, HeroBannerAdminService $banners): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $banners->update($banner, $data, $request->file('image'));

        return redirect()->route('admin.banners.index')->with('status', __('flash.banner.updated'));
    }

    public function destroy(HeroBanner $banner, HeroBannerAdminService $banners): RedirectResponse
    {
        $banners->delete($banner);

        return redirect()->route('admin.banners.index')->with('status', __('flash.banner.deleted'));
    }
}

