<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="25" y="48" width="60" height="46" rx="6" fill="#E9EDF6" stroke="#1B449C" stroke-width="2.5"/>
                        <path d="M38 48V36C38 27.163 45.163 20 54 20H56C64.837 20 72 27.163 72 36V48" stroke="#1B449C" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="55" cy="68" r="8" fill="#1B449C"/>
                        <rect x="52" y="68" width="6" height="10" rx="2" fill="white"/>
                        <circle cx="55" cy="65" r="3" fill="white"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Access Denied', __('site.Access Denied')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'access_denied_msg', __('site.access_denied_msg')) }}
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
