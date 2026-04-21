<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys((array) config('locales.supported', []));
        $default = (string) config('locales.default', config('app.locale'));

        $locale = $request->session()->get('locale');

        if (!is_string($locale) || $locale === '') {
            $locale = $request->cookie('locale');
        }

        if (!is_string($locale) || $locale === '') {
            $preferred = $request->getPreferredLanguage($supported);
            $locale = $preferred ?: $default;
        }

        if (!in_array($locale, $supported, true)) {
            $locale = $default;
        }

        App::setLocale($locale);

        return $next($request);
    }
}

