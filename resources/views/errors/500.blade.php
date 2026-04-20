<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="15" y="25" width="80" height="55" rx="8" fill="#E9EDF6" stroke="#1B449C" stroke-width="2.5"/>
                        <rect x="15" y="70" width="80" height="10" rx="0" fill="#1B449C" opacity="0.15"/>
                        <rect x="28" y="38" width="54" height="6" rx="3" fill="#7D95C7" opacity="0.6"/>
                        <rect x="28" y="50" width="38" height="6" rx="3" fill="#7D95C7" opacity="0.4"/>
                        <circle cx="85" cy="28" r="14" fill="#1B449C"/>
                        <path d="M85 21V30" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="85" cy="34" r="1.5" fill="white"/>
                        <rect x="30" y="85" width="50" height="6" rx="3" fill="#1B449C" opacity="0.2"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Server Error', __('site.Server Error')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'server_error_msg', __('site.server_error_msg')) }}
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
