<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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

        $candidate = strtolower((string) $request->query('lang', Session::get('app_locale')));
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

        if ($candidate === '') {
            $candidate = config('app.locale');
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
    }
}
