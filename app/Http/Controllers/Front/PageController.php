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
            ->with(['langs', 'imageInfo'])
            ->where('content_type', 2)
            ->where('slug', $slug)
            ->firstOrFail();

        $locale = app()->getLocale();
        $language = Language::query()
            ->where('shortname', $locale)
            ->first();

        $pageLang = $language
            ? $page->langs->firstWhere('language_id', $language->id)
            : null;

        if (! $pageLang) {
            $pageLang = $page->langs->first();
        }

        return view('front.pages', [
            'page' => $page,
            'pageLang' => $pageLang,
        ]);
    }
}
