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
                <div class="flex flex-col gap-8 justify-center items-center h-full text-center pt-8">
                    <div>
                        <svg width="97" height="97" viewBox="0 0 97 97" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_115_1008)">
                                <path d="M20.1992 48.114L22.8051 49.2569L23.9511 46.6584L21.3454 45.5156L20.1992 48.114Z"
                                    fill="#1B449C" />
                                <path d="M22.4902 42.9167L25.0962 44.0595L26.2424 41.4611L23.6364 40.3184L22.4902 42.9167Z"
                                    fill="#1B449C" />
                                <path
                                    d="M18.4109 27.6839L21.7513 31.0147L11.1872 54.9627L0 66.1177V73.5478L10.6675 84.1847H18.1191L19.4214 82.8862L25.7397 89.186L27.7528 87.1788L21.4345 80.8789L25.4604 76.8646L37.6025 88.9714L33.5764 92.9857L29.8067 89.2266L27.7935 91.2338L33.5762 96.9998L41.6282 88.9712L36.5302 83.888L40.2008 80.2281V71.4906L37.9444 69.2406L53.3223 62.4955L56.6571 65.8206L62.6961 59.7988L24.4493 21.6621L18.4109 27.6839ZM16.94 81.3459H11.8467L2.84691 72.3719V67.2932L12.3827 57.785L26.4757 71.8374L16.94 81.3459ZM37.3543 72.6663V79.0524L34.5176 81.8809L27.4735 74.8571L29.3061 73.0299L35.1485 70.4673L37.3543 72.6663ZM56.6575 61.8061L34.4983 39.7105L32.4852 41.718L51.1628 60.3418L28.8065 70.1479L14.0773 55.4611L23.9111 33.1685L30.336 39.5751L32.3491 37.5678L22.4367 27.6841L24.4497 25.6768L58.6706 59.7992L56.6575 61.8061Z"
                                    fill="#1B449C" />
                                <path d="M10.4082 63.7692L12.4211 65.7762L14.4343 63.769L12.4213 61.7617L10.4082 63.7692Z"
                                    fill="#1B449C" />
                                <path
                                    d="M44.186 17.1964H38.4492V20.081H44.1925C49.7719 19.861 49.7994 11.691 44.1773 11.5128L42.7254 11.5114C40.888 11.4785 40.8863 8.73973 42.7269 8.70772H48.4182V5.86914H42.7269C37.1282 6.01691 37.1266 14.1977 42.7229 14.35L44.1747 14.3513C46.0141 14.3805 46.0201 17.1558 44.186 17.1964Z"
                                    fill="#7D95C7" />
                                <path
                                    d="M51.2598 11.5463V14.4038C51.2598 17.5341 53.814 20.081 56.9534 20.081C60.0928 20.081 62.647 17.5341 62.647 14.4038V11.5463C62.647 8.41596 60.0928 5.86914 56.9534 5.86914C53.814 5.86914 51.2598 8.41596 51.2598 11.5463ZM59.8001 11.5463V14.4038C59.8001 15.9691 58.5232 17.2424 56.9534 17.2424C55.3836 17.2424 54.1067 15.9691 54.1067 14.4038V11.5463C54.1067 9.98103 55.3836 8.70772 56.9534 8.70772C58.5232 8.70772 59.8001 9.98103 59.8001 11.5463Z"
                                    fill="#7D95C7" />
                                <path
                                    d="M65.4941 14.4038C65.4941 17.5341 68.0483 20.081 71.1878 20.081C74.3272 20.081 76.8814 17.5341 76.8814 14.4038V11.5463C76.8814 8.41596 74.3272 5.86914 71.1878 5.86914C68.0483 5.86914 65.4941 8.41596 65.4941 11.5463V14.4038ZM68.3409 11.5463C68.3409 9.98103 69.6178 8.70772 71.1876 8.70772C72.7574 8.70772 74.0343 9.98103 74.0343 11.5463V14.4038C74.0343 15.9691 72.7574 17.2424 71.1876 17.2424C69.6178 17.2424 68.3409 15.9691 68.3409 14.4038V11.5463Z"
                                    fill="#7D95C7" />
                                <path
                                    d="M88.2463 20.081H91.1214V5.86914H88.2747V14.8373L82.431 5.86914H79.7148V20.081H82.5616V11.2701L88.2463 20.081Z"
                                    fill="#7D95C7" />
                                <path d="M63.5117 0H66.3584V2.83858H63.5117V0Z" fill="#7D95C7" />
                                <path
                                    d="M69.2043 0V2.83858H94.153V22.902H69.6514L64.8827 33.5946L60.1138 22.902H35.6125V2.83858H60.6639V0H32.7656V25.7408H58.2641L64.8827 40.5803L71.501 25.7408H96.9997V0L69.2043 0Z"
                                    fill="#7D95C7" />
                                <path d="M79.6875 30.7141L86.3049 35.6865L88.0181 33.4197L81.4007 28.4473L79.6875 30.7141Z"
                                    fill="#7D95C7" />
                                <path d="M71.1777 38.931L76.1645 45.5296L78.4381 43.8213L73.4516 37.2227L71.1777 38.931Z"
                                    fill="#7D95C7" />
                                <path d="M75.7617 35.0045L81.6207 40.8467L83.6339 38.8394L77.7748 32.9971L75.7617 35.0045Z"
                                    fill="#7D95C7" />
                            </g>
                            <defs>
                                <clipPath id="clip0_115_1008">
                                    <rect width="97" height="97" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <p>
                            {{ __('Content will be available soon.') }}
                        </p>
                        <a href="{{ route('home') }}" class="flex gap-2 justify-center items-center mt-7 text-[#0553FC]">
                            <span class="underline">{{ __('Return to Homepage') }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-front-layout>
