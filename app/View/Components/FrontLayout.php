<?php

namespace App\View\Components;

use App\Models\Language;
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

        $requestedLocale = null;
        $sessionLocale = null;

        if (! app()->runningInConsole()) {
            $requestedLocale = strtolower((string) request()->query('lang'));
            if ($requestedLocale !== '' && in_array($requestedLocale, $availableLocales, true)) {
                Session::put('app_locale', $requestedLocale);
            }

            $sessionLocale = strtolower((string) Session::get('app_locale'));
        }

        $sessionLocale ??= '';
        $this->locale = $sessionLocale !== '' && in_array($sessionLocale, $availableLocales, true)
            ? $sessionLocale
            : (in_array(strtolower(app()->getLocale()), $availableLocales, true)
                ? strtolower(app()->getLocale())
                : (reset($availableLocales) ?: config('app.locale')));

        if ($this->locale) {
            app()->setLocale($this->locale);
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
}
