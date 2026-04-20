<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="56" stroke="#1B449C" stroke-width="3" stroke-dasharray="8 4"/>
                        <text x="60" y="52" text-anchor="middle" font-family="Poppins, sans-serif" font-size="28" font-weight="800" fill="#1B449C">404</text>
                        <path d="M38 72 Q60 58 82 72" stroke="#7D95C7" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <circle cx="44" cy="62" r="4" fill="#7D95C7"/>
                        <circle cx="76" cy="62" r="4" fill="#7D95C7"/>
                        <path d="M50 85 L60 78 L70 85" stroke="#1B449C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Page Not Found', __('site.Page Not Found')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'page_not_found_msg', __('site.page_not_found_msg')) }}
                    </p>
                </div>

                <a href="{{ route('home') }}" class="flex gap-2 justify-center items-center mt-2 text-[#0553FC]">
                    <span class="underline">
                        {{ label_text('global', 'Return to Homepage', __('site.Return to Homepage')) }}
                    </span>
                    @if (app()->getLocale() == 'en')
                        <i class="fas fa-arrow-right"></i>
                    @else
                        <i class="fas fa-arrow-left"></i>
                    @endif
                </a>

            </div>
        </div>
    </section>

</x-front-layout>
