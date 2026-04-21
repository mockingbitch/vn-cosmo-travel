<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\Destination;
use App\Services\Admin\DestinationAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(DestinationAdminService $destinations): View
    {
        return view('admin.destinations.index', [
            'destinations' => $destinations->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(StoreDestinationRequest $request, DestinationAdminService $destinations): RedirectResponse
    {
        $destinations->create($request->validated());

        return redirect()->route('admin.destinations.index')->with('status', __('flash.destination.created'));
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', [
            'destination' => $destination,
        ]);
    }

    public function update(UpdateDestinationRequest $request, Destination $destination, DestinationAdminService $destinations): RedirectResponse
    {
        $destinations->update($destination, $request->validated());

        return redirect()->route('admin.destinations.index')->with('status', __('flash.destination.updated'));
    }

    public function destroy(Destination $destination, DestinationAdminService $destinations): RedirectResponse
    {
        $destinations->delete($destination);

        return redirect()->route('admin.destinations.index')->with('status', __('flash.destination.deleted'));
    }
}
