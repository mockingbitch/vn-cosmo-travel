<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServicePageRequest;
use App\Models\ServicePage;
use App\Services\Admin\ServicePageAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServicePageController extends Controller
{
    public function edit(string $type, ServicePageAdminService $service): View
    {
        $this->assertType($type);

        return view('admin.service-pages.edit', [
            'page' => $service->singleton($type),
            'type' => $type,
            'publicRouteName' => ServicePage::publicRouteName($type),
            'pageTitle' => __(ServicePage::adminTitleLangKey($type)),
        ]);
    }

    public function update(string $type, UpdateServicePageRequest $request, ServicePageAdminService $service): RedirectResponse
    {
        $this->assertType($type);

        $model = $service->singleton($type);
        $service->update($model, $request->validated());

        return redirect()
            ->route('admin.service-pages.edit', ['type' => $type])
            ->with('status', __('flash.service_pages.updated'));
    }

    private function assertType(string $type): void
    {
        if (! ServicePage::isAllowedType($type)) {
            abort(404);
        }
    }
}
