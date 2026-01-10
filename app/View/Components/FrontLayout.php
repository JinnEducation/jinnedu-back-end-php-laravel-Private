<?php

namespace App\View\Components;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class FrontLayout extends Component
{
    public string $title;

    /**
     * All active languages ordered by priority.
     */
    public Collection $languages;

    /**
     * Precomputed URLs for switching between locales.
     */
    public array $languageUrls;

    /**
     * Currently resolved locale code.
     */
    public string $locale;

    /**
     * Current language record (if available).
     */
    public ?Language $currentLanguage;

    /**
     * Text direction (ltr / rtl) for the active language.
     */
    public string $direction;

    public function __construct(?string $title = null)
    {
        $this->title = $title ?? config('app.name');

        $this->languages = collect();
        $this->languageUrls = [];
        $this->currentLanguage = null;
        $this->direction = 'ltr';
        $availableLocales = [];

        if (! app()->runningInConsole() && Schema::hasTable('languages')) {
            $this->languages = Language::query()
                ->where('status', 1)
                ->orderByDesc('main')
                ->orderBy('name')
                ->get(['id', 'name', 'shortname', 'direction', 'icon']);

            $availableLocales = $this->languages
                ->pluck('shortname')
                ->filter()
                ->map(static fn ($locale) => strtolower($locale))
                ->unique()
                ->values()
                ->all();
        }

        $availableLocales = array_values($availableLocales);

        $this->locale = $this->resolveLocale($availableLocales);
        app()->setLocale($this->locale);

        if (! app()->runningInConsole()) {
            $request = request();
            if ($request) {
                Session::put('app_locale', $this->locale);
                $request->headers->set('lang', $this->locale);
                $this->languageUrls = $this->buildLanguageUrls($request);
            }
        }

        $this->currentLanguage = $this->languages->firstWhere('shortname', $this->locale)
            ?? $this->languages->firstWhere('main', 1)
            ?? $this->languages->first();

        if ($this->currentLanguage && $this->currentLanguage->direction) {
            $this->direction = $this->currentLanguage->direction;
        } elseif (in_array($this->locale, ['ar', 'he', 'fa', 'ur'], true)) {
            $this->direction = 'rtl';
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.front');
    }

    private function resolveLocale(array $availableLocales): string
    {
        $candidates = [];

        if (! app()->runningInConsole()) {
            $request = request();

            if ($request) {
                $pathSegment = strtolower((string) $request->segment(1));
                if ($this->looksLikeLocale($pathSegment) && $pathSegment !== '') {
                    $candidates[] = $pathSegment;
                }

                $queryLocale = strtolower((string) $request->query('lang'));
                if ($queryLocale !== '') {
                    $candidates[] = $queryLocale;
                }
            }
        }

        $sessionLocale = strtolower((string) Session::get('app_locale'));
        if ($sessionLocale !== '') {
            $candidates[] = $sessionLocale;
        }

        $appLocale = strtolower((string) app()->getLocale());
        if ($appLocale !== '') {
            $candidates[] = $appLocale;
        }

        $defaultLocale = strtolower((string) config('app.locale'));
        $fallbackLocale = strtolower((string) config('app.fallback_locale', $defaultLocale));

        $candidates[] = $defaultLocale;
        if ($fallbackLocale !== '' && $fallbackLocale !== $defaultLocale) {
            $candidates[] = $fallbackLocale;
        }

        foreach ($candidates as $candidate) {
            if (
                $candidate !== '' &&
                ($availableLocales === [] || in_array($candidate, $availableLocales, true))
            ) {
                return $candidate;
            }
        }

        if ($availableLocales !== []) {
            return $availableLocales[0];
        }

        return $defaultLocale ?: 'ar';
    }

    private function buildLanguageUrls(Request $request): array
    {
        $queryParams = $request->query();
        unset($queryParams['lang']);

        $segments = $request->segments();
        $languageUrls = [];

        foreach ($this->languages as $language) {
            $short = strtolower((string) $language->shortname);

            if ($short === '') {
                continue;
            }

            $pathSegments = $segments;
            if (
                $pathSegments !== [] &&
                $this->looksLikeLocale(strtolower((string) $pathSegments[0]))
            ) {
                array_shift($pathSegments);
            }

            array_unshift($pathSegments, $short);
            $pathSegments = array_filter($pathSegments, static fn ($segment) => $segment !== null && $segment !== '');

            $path = implode('/', $pathSegments);
            $url = url($path === '' ? '/' : $path);

            if ($queryParams !== []) {
                $url .= '?'.http_build_query($queryParams);
            }

            $languageUrls[$short] = $url;
        }

        return $languageUrls;
    }

    private function looksLikeLocale(string $value): bool
    {
        return $value !== '' && (bool) preg_match('/^[a-z]{2}(?:-[a-z0-9]{2,4})?$/', $value);
    }
}
