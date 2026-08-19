<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourPriceTypeRequest;
use App\Http\Requests\Admin\UpdateTourPriceTypeRequest;
use App\Models\TourPriceType;
use App\Services\Admin\TourPriceTypeAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TourPriceTypeController extends Controller
{
    public function index(TourPriceTypeAdminService $priceTypes): View
    {
        return view('admin.tour-price-types.index', [
            'priceTypes' => $priceTypes->paginate(20),
        ]);
    }

    public function create(TourPriceTypeAdminService $priceTypes): View
    {
        return view('admin.tour-price-types.create', [
            'categories' => $priceTypes->existingCategories(),
        ]);
    }

    public function store(StoreTourPriceTypeRequest $request, TourPriceTypeAdminService $priceTypes): RedirectResponse
    {
        $priceTypes->create($request->validated());

        return redirect()
            ->route('admin.tour-price-types.index')
            ->with('status', __('flash.tour_price_type.created'));
    }

    public function edit(TourPriceType $tourPriceType, TourPriceTypeAdminService $priceTypes): View
    {
        return view('admin.tour-price-types.edit', [
            'priceType' => $tourPriceType,
            'categories' => $priceTypes->existingCategories(),
        ]);
    }

    public function update(UpdateTourPriceTypeRequest $request, TourPriceType $tourPriceType, TourPriceTypeAdminService $priceTypes): RedirectResponse
    {
        $priceTypes->update($tourPriceType, $request->validated());

        return redirect()
            ->route('admin.tour-price-types.index')
            ->with('status', __('flash.tour_price_type.updated'));
    }

    public function destroy(TourPriceType $tourPriceType, TourPriceTypeAdminService $priceTypes): RedirectResponse
    {
        if (! $priceTypes->delete($tourPriceType)) {
            return redirect()
                ->route('admin.tour-price-types.index')
                ->withErrors(['delete' => __('flash.tour_price_type.in_use')]);
        }

        return redirect()
            ->route('admin.tour-price-types.index')
            ->with('status', __('flash.tour_price_type.deleted'));
    }
}
