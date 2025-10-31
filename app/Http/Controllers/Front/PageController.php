<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the public page that matches the given slug.
     */
    public function show(Request $request, string $slug): View
    {
        $page = Page::query()
            ->with(['langs.language', 'imageInfo'])
            ->where('content_type', 2)
            ->where('slug', $slug)
            ->firstOrFail();

        $locale = app()->getLocale();
        $language = Language::query()
            ->where('shortname', $locale)
            ->first();

        $pageLang = null;

        if ($language) {
            $pageLang = $page->langs->first(function ($lang) use ($language) {
                $languageId = $lang->language_id
                    ?? $lang->langid
                    ?? optional($lang->language)->id;

                return (int) $languageId === (int) $language->id;
            });
        }

        if (! $pageLang) {
            $pageLang = $page->langs->first(fn ($lang) => (bool) ($lang->main ?? false))
                ?? $page->langs->first();
        }

        return view('front.pages', [
            'page' => $page,
            'pageLang' => $pageLang,
        ]);
    }
}
