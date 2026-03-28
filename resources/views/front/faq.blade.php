<x-front-layout :title="label_text('global', 'site.FAQ', __('site.FAQ'))">
    <section class="flex overflow-hidden relative items-center py-10 bg-white mt-[120px]">
        <div class="container px-4 mx-auto w-full">
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
                        <span class="text-gray-900">{{ label_text('global', 'site.FAQ', __('site.FAQ')) }}</span>
                    </li>
                </ul>
            </nav>

            <h2 class="text-3xl font-bold">{{ label_text('global', 'site.FAQ', __('site.FAQ')) }}</h2>
        </div>
    </section>

    <section class="pt-8 pb-32">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="mt-6">
                @if ($faqs->isEmpty())
                    <div class="py-16 text-center bg-gray-50 rounded-md">
                        <p class="mb-4 text-lg font-semibold text-gray-700">
                            {{ label_text('global', 'site.No-FAQs-Available', __('site.No FAQs available at the moment.')) }}
                        </p>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary rounded-md shadow hover:bg-primary/90 transition-colors">
                            {{ label_text('global', 'site.Back-To-Home', __('site.Back to home')) }}
                        </a>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($faqs as $index => $faq)
                            @php
                                $translation = $faq->langsAll?->first();
                            @endphp

                            @continue(!$translation)

                            <div class="accordion-item overflow-hidden pb-2 border-b-2 border-gray-200">
                                <button
                                    class="accordion-header flex justify-between items-center py-3 w-full cursor-pointer text-lg px-1">
                                    <div class="flex gap-2 items-center font-bold text-start">
                                        <span>{{ $index + 1 }}.</span>
                                        <span>{{ $translation->question }}</span>
                                    </div>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="icon transition-transform duration-300 {{ $index === 0 ? 'rotate-180' : '' }}">
                                        <path d="M15.8334 7.08203L10.0001 12.9154L4.16675 7.08203" stroke="black"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div
                                    class="accordion-body px-2 pb-3 text-[15px] text-black leading-6 {{ $index === 0 ? '' : 'hidden' }}">
                                    <p>{!! nl2br(e($translation->answer)) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(function() {
                $('.accordion-header').on('click', function() {
                    const body = $(this).next('.accordion-body');
                    const icon = $(this).find('.icon');

                    body.slideToggle(220);
                    icon.toggleClass('rotate-180');
                });
            });
        </script>
    @endpush
</x-front-layout>
