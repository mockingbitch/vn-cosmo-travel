<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHeroBannerRequest;
use App\Models\HeroBanner;
use App\Services\Admin\HeroBannerAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    public function edit(HeroBannerAdminService $banners): View
    {
        $current = $banners->currentOrNull();

        return view('admin.banners.edit', [
            'title' => __('Hero Banner'),
            'banner' => $current,
            'history' => $banners->history(3),
        ]);
    }

    public function update(UpdateHeroBannerRequest $request, HeroBannerAdminService $banners): RedirectResponse
    {
        $data = $request->validated();

        $banners->updateCurrent($data, $request->file('image'));

        return redirect()->route('admin.banners.edit')->with('status', __('flash.banner.updated'));
    }

    public function apply(HeroBanner $banner, HeroBannerAdminService $banners): RedirectResponse
    {
        if ($banner->is_current) {
            return redirect()->route('admin.banners.edit');
        }

        $banners->applyHistory($banner);

        return redirect()->route('admin.banners.edit')->with('status', __('flash.banner.applied'));
    }
}

