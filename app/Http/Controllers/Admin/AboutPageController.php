<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageRequest;
use App\Services\Admin\AboutPageAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function edit(AboutPageAdminService $about): View
    {
        return view('admin.about.edit', [
            'about' => $about->singleton(),
        ]);
    }

    public function update(UpdateAboutPageRequest $request, AboutPageAdminService $about): RedirectResponse
    {
        $page = $about->singleton();
        $about->update($page, $request->validated());

        return redirect()->route('admin.about.edit')->with('status', __('flash.about.updated'));
    }
}
