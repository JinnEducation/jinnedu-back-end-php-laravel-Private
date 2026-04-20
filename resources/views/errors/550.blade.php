<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 55 Q35 40 55 55 Q75 70 90 55" stroke="#1B449C" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <path d="M20 40 Q35 25 55 40 Q75 55 90 40" stroke="#7D95C7" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <path d="M20 70 Q35 55 55 70 Q75 85 90 70" stroke="#7D95C7" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.4"/>
                        <circle cx="20" cy="55" r="5" fill="#1B449C"/>
                        <circle cx="90" cy="55" r="5" fill="#1B449C"/>
                        <line x1="55" y1="20" x2="55" y2="30" stroke="#1B449C" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="3 4"/>
                        <line x1="55" y1="80" x2="55" y2="90" stroke="#1B449C" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="3 4"/>
                        <circle cx="55" cy="55" r="8" fill="#E9EDF6" stroke="#1B449C" stroke-width="2"/>
                        <path d="M52 52 L55 55 L59 50" stroke="#1B449C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Connection Error', __('site.Connection Error')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'connection_error_msg', __('site.connection_error_msg')) }}
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
