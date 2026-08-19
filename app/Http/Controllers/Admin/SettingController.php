<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactSettingsRequest;
use App\Http\Requests\Admin\UpdateFeaturedTilesSettingsRequest;
use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Http\Requests\Admin\UpdateHomeWhySettingsRequest;
use App\Http\Requests\Admin\UpdateSocialSettingsRequest;
use App\Http\Requests\Admin\UpdateTestimonialsSettingsRequest;
use App\Services\Admin\SettingAdminService;
use App\Services\Admin\TourAdminService;
use App\Services\FeaturedTilesService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function editGeneral(SettingsService $settings): View
    {
        return view('admin.settings.general', [
            'settings' => $settings->all(),
        ]);
    }

    public function editContact(SettingsService $settings): View
    {
        return view('admin.settings.contact', [
            'settings' => $settings->all(),
        ]);
    }

    public function editSocial(SettingsService $settings): View
    {
        return view('admin.settings.social', [
            'settings' => $settings->all(),
        ]);
    }

    public function editHomeWhy(SettingsService $settings): View
    {
        return view('admin.settings.home-why', [
            'settings' => $settings->all(),
        ]);
    }

    public function editFeaturedTiles(SettingsService $settings, FeaturedTilesService $featuredTiles, TourAdminService $tours): View
    {
        $tiles = $featuredTiles->tiles();

        // Ids to hydrate: what the form last submitted (validation bounce) or what is saved.
        $oldTiles = old('featured_tiles');
        $oldTiles = is_array($oldTiles) ? $oldTiles : [];
        $tourIds = [];
        foreach ($tiles as $i => $tile) {
            $fromOld = $oldTiles[$i]['tour_ids'] ?? null;
            $ids = is_array($fromOld) ? $fromOld : $tile['tour_ids'];
            foreach ($ids as $id) {
                $tourIds[] = (int) $id;
            }
        }

        return view('admin.settings.featured-tiles', [
            'settings' => $settings->all(),
            'tiles' => $tiles,
            'tileDefaults' => $featuredTiles->defaults(),
            'tileShapes' => $featuredTiles->slotShapes(),
            'tourLookup' => $tours->pickerPayloadsById($tourIds),
            'tourCount' => $tours->pickableCount(),
        ]);
    }

    public function editTestimonials(SettingsService $settings): View
    {
        return view('admin.settings.testimonials', [
            'settings' => $settings->all(),
        ]);
    }

    public function updateGeneral(UpdateGeneralSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateGeneral($request->validated(), [
            'logo' => $request->file('logo'),
            'favicon' => $request->file('favicon'),
        ]);

        return redirect()->route('admin.settings.general.edit')->with('status', __('flash.settings.updated'));
    }

    public function updateContact(UpdateContactSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateContact($request->validated());

        return redirect()->route('admin.settings.contact.edit')->with('status', __('flash.settings.updated'));
    }

    public function updateSocial(UpdateSocialSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateSocial($request->validated());

        return redirect()->route('admin.settings.social.edit')->with('status', __('flash.settings.updated'));
    }

    public function updateHomeWhy(UpdateHomeWhySettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateHomeWhy($request->validated());

        return redirect()->route('admin.settings.homeWhy.edit')->with('status', __('flash.settings.updated'));
    }

    public function updateFeaturedTiles(UpdateFeaturedTilesSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateFeaturedTiles($request->validated());

        return redirect()->route('admin.settings.featuredTiles.edit')->with('status', __('flash.settings.updated'));
    }

    public function updateTestimonials(UpdateTestimonialsSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->updateTestimonials($request->validated());

        return redirect()->route('admin.settings.testimonials.edit')->with('status', __('flash.settings.updated'));
    }
}
