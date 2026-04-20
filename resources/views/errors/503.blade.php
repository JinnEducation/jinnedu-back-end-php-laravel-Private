<x-front-layout>

    <section class="pt-8 pb-32 mt-[120px]">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="flex flex-col gap-8 justify-center items-center min-h-[60vh] text-center pt-8">

                <div>
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M55 18C34.565 18 18 34.565 18 55C18 75.435 34.565 92 55 92C75.435 92 92 75.435 92 55C92 34.565 75.435 18 55 18Z" stroke="#7D95C7" stroke-width="2.5"/>
                        <path d="M42 55 L55 42 L68 55 L55 68 Z" fill="#1B449C" opacity="0.2" stroke="#1B449C" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M55 42 L55 55" stroke="#1B449C" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="55" cy="62" r="2.5" fill="#1B449C"/>
                        <path d="M30 30 L80 80" stroke="#7D95C7" stroke-width="1.5" stroke-dasharray="4 4" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="flex flex-col gap-3 items-center max-w-md">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ label_text('global', 'Service Unavailable', __('site.Service Unavailable')) }}
                    </h1>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ label_text('global', 'service_unavailable_msg', __('site.service_unavailable_msg')) }}
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
