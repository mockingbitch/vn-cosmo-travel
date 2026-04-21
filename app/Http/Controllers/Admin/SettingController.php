<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Services\Admin\SettingAdminService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(SettingsService $settings): View
    {
        return view('admin.settings.edit', [
            'settings' => $settings->all(),
        ]);
    }

    public function update(UpdateSettingsRequest $request, SettingAdminService $settings): RedirectResponse
    {
        $settings->update($request->validated(), [
            'logo' => $request->file('logo'),
            'favicon' => $request->file('favicon'),
        ]);

        return redirect()->route('admin.settings.edit')->with('status', __('flash.settings.updated'));
    }
}

