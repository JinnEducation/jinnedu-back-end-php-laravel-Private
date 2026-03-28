<x-front-layout>

    @php
        $currentLocale = app()->getLocale();
        $articleTranslation = $article->langsAll?->firstWhere('lang', $currentLocale) ?? $article->langsAll?->first();
        $audienceTitle = $audience === 'tutor' ? __('site.Help For Tutor') : __('site.Help For Student');
        $audienceRoute = $audience === 'tutor' ? 'site.help_for_tutor' : 'site.help_for_student';
    @endphp

    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <div class="container z-10 px-4 mx-auto w-full">
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <li>
                        <a href="{{ route('home') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">{{ label_text('global', 'Home', __('site.Home')) }}</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i
                                class="font-light fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                        </span>
                    </li>
                    <li>
                        <a href="{{ route($audienceRoute) }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">{{ label_text('global', 'site.Help', $audienceTitle) }}</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i
                                class="font-light fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                        </span>
                    </li>
                    <li>
                        <span class="text-gray-900">{{ $articleTranslation?->title ?? '' }}</span>
                    </li>
                </ul>
            </nav>

            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <h2 class="text-3xl font-bold">{{ $articleTranslation?->title ?? '' }}</h2>

                <div class="flex items-center gap-2" id="helpRatingWidget"
                    data-rate-url="{{ route('site.rate_help', ['audience' => $audience, 'slug' => $article->slug]) }}">
                    <span class="text-sm text-gray-600">{{ label_text('global', 'site.Your-Rating', __('site.Your Rating')) }}:</span>
                    <div class="flex items-center gap-1" id="ratingStars">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="rating-star text-2xl text-gray-300 transition-colors"
                                data-star="{{ $i }}" aria-label="Rate {{ $i }} star">
                                <i class="fas fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500" id="ratingSummary">
                        {{ number_format((float) $article->average_rating, 1) }}/5 ({{ (int) $article->ratings_count }})
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-12 md:mb-12 md:gap-20 md:grid-cols-3" id="coursesGridBlogs">
                <div class="flex flex-col gap-4 items-start text-justify md:col-span-2 md:text-start">
                    <p>
                        {!! $article->langsAll?->first()?->description !!}
                    </p>
                </div>
                <div class="md:px-6 md:col-span-1">
                    <h3 class="mb-4 text-2xl font-bold">{{ label_text('global', 'site.You might also like', __('site.You might also like')) }}</h3>
                    <div class="grid grid-cols-1 gap-7 mb-12 md:gap-5" id="coursesGridBlog">
                        @foreach ($related as $relatedArticle)
                            @php
                                $relatedTranslation =
                                    $relatedArticle->langsAll?->firstWhere('lang', $currentLocale) ??
                                    $relatedArticle->langsAll?->first();
                            @endphp

                            @continue(!$relatedTranslation)

                            <div
                                class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-blogs-card help-card hover:shadow-2xl hover:-translate-y-2">
                                <div class="p-8">
                                    <div class="flex justify-center mb-8">
                                        <div class="w-[70px] h-[70px] flex items-center justify-center text-primary overflow-hidden">
                                        @if ($relatedArticle->icon)
                                            <img src="{{ $relatedArticle->icon_url }}" alt="{{ $relatedTranslation->title }}"
                                                    class="w-full h-full object-contain">
                                        @elseif ($relatedArticle->icon_svg)
                                            {!! $relatedArticle->icon_svg !!}
                                        @else
                                                <i class="fas fa-life-ring text-5xl"></i>
                                        @endif
                                    </div>
                                    </div>

                                    <h3 class="mb-3 text-3xl font-bold text-center text-gray-900">{{ $relatedTranslation->title }}</h3>

                                    <p class="mb-8 text-sm text-center text-gray-600 leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($relatedTranslation->description ?? ''), 120) }}
                                    </p>

                                    <div class="flex justify-center gap-5 items-center mb-8">
                                        <div class="flex justify-start gap-2 items-center">
                                            <i class="fas fa-star text-[#FFC700]"></i>
                                            <span class="text-lg text-black">{{ number_format((float) $relatedArticle->average_rating, 1) }} / 5</span>
                                        </div>
                                        <span class="text-lg text-gray-400">({{ (int) $relatedArticle->ratings_count }})</span>
                                    </div>

                                    <a href="{{ route('site.show_help', ['audience' => $audience, 'slug' => $relatedArticle->slug]) }}"
                                        class="cursor-pointer block text-center w-full py-3 text-base font-semibold text-white rounded-lg transition-all duration-300 bg-primary hover:bg-primary-700 hover:shadow-lg transform hover:scale-105 active:scale-95">
                                        {{ label_text('global', 'site.Learn More', __('site.Learn More')) }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <div class="flex gap-1 justify-center items-center" id="paginationBlogs">
                            <button
                                class="flex justify-center items-center w-8 h-8 rounded-full transition-all duration-200 cursor-pointer text-primary hover:text-white hover:bg-primary"
                                data-page="prev">
                                <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i>
                            </button>

                            <div id="pagesNumbers" class="flex gap-1"></div>

                            <button
                                class="flex justify-center items-center w-8 h-8 rounded-full transition-all duration-200 cursor-pointer text-primary hover:text-white hover:bg-primary"
                                data-page="next">
                                <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('front/assets/js/article.js') }}"></script>
        <script>
            $(function() {
                const stars = $('#ratingStars .rating-star');
                const summary = $('#ratingSummary');
                const rateUrl = $('#helpRatingWidget').data('rate-url');
                const csrf = "{{ csrf_token() }}";
                let selected = 0;

                function paint(active) {
                    stars.each(function() {
                        const value = parseInt($(this).data('star'), 10);
                        $(this).toggleClass('text-[#FFC700]', value <= active);
                        $(this).toggleClass('text-gray-300', value > active);
                    });
                }

                stars.on('mouseenter', function() {
                    paint(parseInt($(this).data('star'), 10));
                });

                $('#ratingStars').on('mouseleave', function() {
                    paint(selected);
                });

                stars.on('click', function() {
                    const starsValue = parseInt($(this).data('star'), 10);
                    if (!rateUrl) return;

                    $.ajax({
                        url: rateUrl,
                        method: 'POST',
                        data: {
                            stars: starsValue,
                            _token: csrf,
                        },
                        success: function(res) {
                            selected = starsValue;
                            paint(selected);
                            summary.text((res.average_rating ?? 0) + '/5 (' + (res.ratings_count ?? 0) + ')');
                        },
                    });
                });
            });
        </script>
    @endpush

</x-front-layout>
