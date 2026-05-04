<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServicePage;
use App\Services\SettingsService;
use App\ViewModels\SeoViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class ServicePageController extends Controller
{
    public function airportTaxi(SettingsService $settings): View
    {
        return $this->renderPage(ServicePage::TYPE_AIRPORT_TAXI, $settings);
    }

    public function visaService(SettingsService $settings): View
    {
        return $this->renderPage(ServicePage::TYPE_VISA, $settings);
    }

    public function busFlightTrainTicket(SettingsService $settings): View
    {
        return $this->renderPage(ServicePage::TYPE_BUS_FLIGHT_TRAIN, $settings);
    }

    public function simCard(SettingsService $settings): View
    {
        return $this->renderPage(ServicePage::TYPE_SIM, $settings);
    }

    private function renderPage(string $type, SettingsService $settings): View
    {
        $record = ServicePage::query()
            ->where('type', $type)
            ->firstOrFail();

        $display = $record->resolvedForLocale();

        return view('pages.service-pages.show', [
            'seo' => new SeoViewModel(
                title: $display['title'].' — '.$settings->siteNameForLocale(),
                description: Str::limit(strip_tags((string) $display['content']), 155),
            ),
            'display' => $display,
        ]);
    }
}
