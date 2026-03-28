<x-front-layout :title="label_text('global', 'site.Help For Tutor', __('site.Help For Tutor'))">
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-10">
        <div class="container z-10 px-4 mx-auto w-full">
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <li>
                        <a href="{{ route('home') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">{{ label_text('global', 'site.Home', __('site.Home')) }}</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i
                                class="font-light fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                        </span>
                    </li>
                    <li>
                        <span class="text-gray-900">{{ label_text('global', 'site.Help For Tutor', __('site.Help For Tutor')) }}</span>
                    </li>
                </ul>
            </nav>

            <h2 class="mb-2 text-3xl font-bold">{{ label_text('global', 'site.Help For Tutor', __('site.Help For Tutor')) }}</h2>
            <p class="text-gray-600">{{ label_text('global', 'site.Explore Help Articles', __('site.Explore Help Articles')) }}</p>
        </div>
    </section>

    <section class="pt-6 pb-12 md:pb-20">
        <div class="container mx-auto px-4">
            @if ($articles->isEmpty())
                <div class="py-16 text-center bg-gray-50 rounded-md">
                    <p class="mb-4 text-lg font-semibold text-gray-700">
                        {{ label_text('global', 'site.No-help-articles', __('site.No help articles available at the moment.')) }}
                    </p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary rounded-md shadow hover:bg-primary/90 transition-colors">
                        {{ label_text('global', 'site.Back-To-Home', __('site.Back to home')) }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 mb-12 md:gap-10"
                    id="helpGridGroupClasses">
                    @foreach ($articles as $article)
                        @php
                            $translation = $article->langsAll?->first();
                        @endphp

                        @continue(!$translation || !$article->slug)

                        <div
                            class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 help-card hover:shadow-2xl hover:-translate-y-2">
                            <div class="p-8">
                                <div class="flex justify-center mb-8">
                                    <div class="w-[70px] h-[70px] flex items-center justify-center text-primary overflow-hidden">
                                        @if ($article->icon)
                                            <img src="{{ $article->icon_url }}" alt="{{ $translation->title }}"
                                                class="w-full h-full object-contain">
                                        @elseif ($article->icon_svg)
                                            {!! $article->icon_svg !!}
                                        @else
                                            <i class="fas fa-life-ring text-5xl"></i>
                                        @endif
                                    </div>
                                </div>

                                <h3 class="mb-3 text-3xl font-bold text-center text-gray-900">{{ $translation->title }}</h3>

                                <p class="mb-8 text-sm text-center text-gray-600 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($translation->description ?? ''), 140) }}
                                </p>

                                <div class="flex justify-center gap-5 items-center mb-8">
                                    <div class="flex justify-start gap-2 items-center">
                                        <i class="fas fa-star text-[#FFC700]"></i>
                                        <span class="text-lg text-black">{{ number_format((float) $article->average_rating, 1) }} / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">({{ (int) $article->ratings_count }})</span>
                                </div>

                                <a href="{{ route('site.show_help', ['audience' => 'tutor', 'slug' => $article->slug]) }}"
                                    class="cursor-pointer block text-center w-full py-3 text-base font-semibold text-white rounded-lg transition-all duration-300 bg-primary hover:bg-primary-700 hover:shadow-lg transform hover:scale-105 active:scale-95">
                                    {{ label_text('global', 'site.Learn More', __('site.Learn More')) }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="relative">
                            <select id="perPageSelect"
                                class="py-3.5 pr-10 pl-24 text-black rounded-md border border-gray-200 appearance-none cursor-pointer text-md hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-32">
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="9">9</option>
                            </select>
                            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                <span class="text-sm font-medium text-gray-700">{{ label_text('global', 'Per-Page', __('auth.PER PAGE')) }}</span>
                            </div>
                            <div class="flex absolute inset-y-0 right-0 items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-1 justify-center items-center" id="paginationBlogs">
                        <button
                            class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
                            data-page="prev">
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i>
                        </button>

                        <div id="pagesNumbers" class="flex gap-1"></div>

                        <button
                            class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
                            data-page="next">
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                        </button>
                    </div>

                    <div class="hidden w-32 md:block"></div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('front/assets/js/help_for_student.js') }}"></script>
    @endpush
</x-front-layout>
