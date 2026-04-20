<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="55" cy="55" r="40" stroke="#7D95C7" stroke-width="2.5"/>
                        <path d="M55 28V58" stroke="#1B449C" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="55" cy="70" r="4" fill="#1B449C"/>
                        <path d="M40 20 Q55 10 70 20" stroke="#1B449C" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M28 40 Q18 55 28 70" stroke="#7D95C7" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M82 40 Q92 55 82 70" stroke="#7D95C7" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Page Expired', __('site.Page Expired')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'page_expired_msg', __('site.page_expired_msg')) }}
                    </p>
                </div>

                <div class="flex gap-4 items-center mt-2">
                    <button onclick="window.location.reload()" class="flex gap-2 justify-center items-center text-[#0553FC]">
                        <i class="fas fa-redo text-sm"></i>
                        <span class="underline">
                            {{ label_text('global', 'Refresh Page', __('site.Refresh Page')) }}
                        </span>
                    </button>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('home') }}" class="flex gap-2 justify-center items-center text-[#0553FC]">
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
        </div>
    </section>

</x-front-layout>
