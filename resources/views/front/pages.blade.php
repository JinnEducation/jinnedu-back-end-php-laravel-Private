@php
    $pageTitle = $pageLang?->title ?? ($page->name ?? __('Page'));
    $pageSummary = $pageLang?->summary;
    $pageDescription = $pageLang?->description;
@endphp

<x-front-layout :title="$pageTitle">
    <!-- Hero Section -->
    <section
        class="flex overflow-hidden relative items-center py-10 bg-white shadow-[0_-5px_50px_0_rgba(0,0,0,0.25)] bg-gray-50 mt-[120px]">
        <!-- Main Container -->
        <div class="container px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <!-- Home -->
                    <li>
                        <a href="{{ route('home') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    {{-- <li>
                        <a class="transition-colors text-primary-600 hover:text-primary-700">{{ $pageTitle }}</a>
                    </li> --}}
                    {{-- <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li> --}}
                    <!-- Current Page -->
                    <li>
                        <span class="text-gray-900">{{ $pageTitle }}</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="text-3xl font-bold">{{ $pageTitle }}</h2>
            @if ($pageSummary)
                <p class="mt-4 text-base leading-7 text-gray-600">
                    {{ $pageSummary }}
                </p>
            @endif

            {{-- @if ($page->imageInfo?->path)
                <div class="relative overflow-hidden rounded-2xl md:w-2/5">
                    <img src="{{ asset($page->imageInfo->path) }}" alt="{{ $pageTitle }}"
                        class="object-cover w-full h-full max-h-64">
                </div>
            @endif --}}
        </div>
    </section>

    <!-- About Us Content -->
    <section class="pt-8 pb-32">
        <div class="container px-4 mx-auto lg:px-20">
            @if ($pageDescription)
                <article class="space-y-6 leading-7 text-gray-700 rtl:text-right">
                    {!! $pageDescription !!}
                </article>
            @else
                <p class="text-center text-gray-500">
                    {{ __('Content will be available soon.') }}
                </p>
            @endif
        </div>
    </section>
</x-front-layout>
