<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        if (app()->runningInConsole()) {
            return;
        }

        $request = request();
        if (! $request) {
            return;
        }

        URL::forceRootUrl($request->getSchemeAndHttpHost());

        $availableLocales = [];

        if (function_exists('locales')) {
            $availableLocales = array_map('strtolower', locales());
        }

        if (Schema::hasTable('languages')) {
            $dbLocales = Language::query()
                ->where('status', 1)
                ->pluck('shortname')
                ->filter()
                ->map(static fn ($locale) => strtolower($locale))
                ->all();
            $availableLocales = array_unique(array_merge($availableLocales, $dbLocales));
        }

        $availableLocales = array_values($availableLocales);

        $candidate = null;

        $pathCandidate = strtolower((string) $request->segment(1));
        if (
            $pathCandidate !== '' &&
            preg_match('/^[a-z]{2}(?:-[a-z0-9]{2,4})?$/', $pathCandidate) &&
            ($availableLocales === [] || in_array($pathCandidate, $availableLocales, true))
        ) {
            $candidate = $pathCandidate;
        }

        if (! $candidate) {
            $queryCandidate = strtolower((string) $request->query('lang'));
            if (
                $queryCandidate !== '' &&
                ($availableLocales === [] || in_array($queryCandidate, $availableLocales, true))
            ) {
                $candidate = $queryCandidate;
            }
        }

        if (! $candidate) {
            $sessionLocale = strtolower((string) Session::get('app_locale'));
            if (
                $sessionLocale !== '' &&
                ($availableLocales === [] || in_array($sessionLocale, $availableLocales, true))
            ) {
                $candidate = $sessionLocale;
            }
        }

        if (! $candidate) {
            $candidate = strtolower((string) config('app.locale'));
        }

        if ($candidate && ($availableLocales === [] || in_array($candidate, $availableLocales, true))) {
            Session::put('app_locale', $candidate);
            app()->setLocale($candidate);
            $request->headers->set('lang', $candidate);
        } elseif ($availableLocales !== [] && $fallback = reset($availableLocales)) {
            Session::put('app_locale', $fallback);
            app()->setLocale($fallback);
            $request->headers->set('lang', $fallback);
        }

        $resolvedLocale = (string) app()->getLocale();
        if ($resolvedLocale !== '') {
            URL::defaults(['locale' => $resolvedLocale]);
        }
    }
}
