<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactSettingsRequest;
use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Http\Requests\Admin\UpdateHomeWhySettingsRequest;
use App\Http\Requests\Admin\UpdateSocialSettingsRequest;
use App\Services\Admin\SettingAdminService;
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
}
