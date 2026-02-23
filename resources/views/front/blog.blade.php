<x-front-layout>

    @php
        $currentLocale = app()->getLocale();
    @endphp

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <!-- Home -->
                    <li>
                        <a href="{{ route('home') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">{{ label_text('global', 'Home', __('auth.Home')) }}</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i
                                class="font-light fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
                        </span>
                    </li>
                    <!-- Current Page -->
                    <li>
                        <span class="text-gray-900">{{ label_text('global', 'Blog', __('site.Blog')) }}</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-7 text-3xl font-bold">
                {{ label_text('global', 'Explore-Our-Blogs', __('site.Explore Our Blogs')) }}</h2>

            <!-- Category Filter -->
            <div class="relative mx-[-30px]" dir="ltr">
                <!-- سهم يسار -->
                <div id="left-arrow"
                    class="flex absolute inset-y-0 left-0 items-center opacity-0 transition-opacity pointer-events-none md:-left-10 ps-2 text-primary">
                    <!-- Heroicons: chevron-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

                <div id="filter-container"
                    class="flex overflow-x-auto overflow-y-hidden relative flex-nowrap gap-2 px-8 whitespace-nowrap scrollbar-hide cursor-grab active:cursor-grabbing">
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-white bg-primary border border-primary transition-all duration-300 text-md category-blogs-btn active hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="all" id="all-blogs-btn">
                        {{ label_text('global', 'All', __('auth.All')) }}
                    </button>
                    @foreach ($categories as $category)
                        @php
                            $categoryTranslation =
                                $category->langsAll?->firstWhere('lang', $currentLocale) ??
                                $category->langsAll?->first();
                        @endphp

                        @continue(!$categoryTranslation)

                        <button
                            class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                            data-type="{{ $categoryTranslation->slug }}">
                            {{ $categoryTranslation->name }}
                        </button>
                    @endforeach

                </div>

                <!-- سهم يمين -->
                <div id="right-arrow"
                    class="flex absolute inset-y-0 right-0 items-center opacity-0 transition-opacity pointer-events-none md:-right-10 pe-2 text-primary">
                    <!-- Heroicons: chevron-right -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 gap-10 mb-12 md:grid-cols-2 lg:grid-cols-3" id="coursesGridBlogs">
                @forelse ($blogs as $blog)
                    @php
                        $blogTranslation =
                            $blog->langsAll?->firstWhere('lang', $currentLocale) ?? $blog->langsAll?->first();
                        $categoryTranslation =
                            $blog->category?->langsAll?->firstWhere('lang', $currentLocale) ??
                            $blog->category?->langsAll?->first();
                        $categorySlug = $categoryTranslation?->slug;
                    @endphp

                    @continue(!$categorySlug || !$blogTranslation)

                    <div class="cursor-pointer block bg-white rounded-md shadow-lg transition-all duration-300 course-blogs-card hover:shadow-lg hover:scale-102"
                        data-type="{{ $categorySlug }}">
                        <div class="overflow-hidden relative h-54"
                            onclick="window.location.href=`{{ route('site.showBlog', $blogTranslation->slug) }}`">
                            <img src="{{ $blog->image_url }}" alt="{{ $blogTranslation->title }}"
                                class="object-cover w-full h-full" />
                        </div>
                        <div class="p-4 pb-0">
                            <h3 class="mb-2 font-bold text-black text-[15px]"
                                onclick="window.location.href=`{{ route('site.showBlog', $blogTranslation->slug) }}`">
                                {{ $blogTranslation->title }}
                            </h3>
                            <p class="mb-4 text-[13px] text-gray-400"
                                onclick="window.location.href=`{{ route('site.showBlog', $blogTranslation->slug) }}`">
                                {{ \Illuminate\Support\Str::limit(strip_tags($blogTranslation->description ?? ''), 120) }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-star text-[#FFC700]"></i>
                                    <span class="text-sm text-gray-800 me-1">0/5</span>
                                    <span class="text-sm text-gray-500">(0)</span>
                                </div>
                                <a href="{{ route('site.showBlog', $blogTranslation->slug) }}"
                                    class="text-sm font-medium text-[#0553FC] underline hover:text-primary hover:mr-3 rtl:hover:ml-3 transition-all duration-300">{{ label_text('global', 'Read More', __('site.Read More')) }}</a>
                            </div>
                            <div class="py-2 mt-3 border-t border-[#E5E7EB]">
                                <div class="flex justify-between items-center transition-all duration-300">
                                    <a href="#" data-url="{{ route('site.showBlog', $blogTranslation->slug) }}"
                                        class="share-btn p-1.5 rounded-full transition-all duration-300 hover:scale-105 hover:ml-1 rtl:hover:mr-1">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.894 7.46942C15.6073 7.47286 16.3064 7.27001 16.908 6.88508C17.5097 6.50015 17.9884 5.94941 18.287 5.29857C18.5857 4.64773 18.6917 3.92429 18.5923 3.21458C18.493 2.50487 18.1925 1.83887 17.7268 1.29606C17.2611 0.753244 16.6498 0.356549 15.9658 0.153311C15.2819 -0.0499258 14.5542 -0.0511209 13.8695 0.149869C13.1849 0.350858 12.5723 0.745543 12.1049 1.28682C11.6374 1.8281 11.3347 2.49311 11.2331 3.20249L6.20629 6.53574C5.67256 6.0519 5.01065 5.73396 4.30075 5.62045C3.59085 5.50694 2.86343 5.60272 2.20661 5.8962C1.54978 6.18967 0.991745 6.66825 0.600098 7.27394C0.20845 7.87964 0 8.58646 0 9.30878C0 10.0311 0.20845 10.7379 0.600098 11.3436C0.991745 11.9493 1.54978 12.4279 2.20661 12.7214C2.86343 13.0148 3.59085 13.1106 4.30075 12.9971C5.01065 12.8836 5.67256 12.5657 6.20629 12.0818L11.2238 15.4431C11.3555 16.3327 11.8022 17.1447 12.4819 17.7299C13.1616 18.3151 14.0286 18.6342 14.9237 18.6285C15.8187 18.6228 16.6816 18.2927 17.3539 17.6989C18.0262 17.1051 18.4626 16.2874 18.583 15.3962C18.7034 14.5049 18.4997 13.6 18.0093 12.8476C17.5189 12.0952 16.7747 11.5459 15.9136 11.3006C15.0524 11.0554 14.1321 11.1306 13.3218 11.5125C12.5114 11.8945 11.8655 12.5575 11.5026 13.3796L7.23767 10.5226C7.53056 9.73692 7.53056 8.8713 7.23767 8.08565L11.5026 5.22858C11.7908 5.89227 12.2649 6.45745 12.867 6.8553C13.4691 7.25315 14.1734 7.46651 14.894 7.46942ZM14.894 1.86732C15.2616 1.86732 15.6209 1.97684 15.9265 2.18203C16.2321 2.38722 16.4703 2.67886 16.6109 3.02008C16.7516 3.3613 16.7884 3.73676 16.7167 4.09899C16.645 4.46123 16.468 4.79396 16.2081 5.05512C15.9482 5.31627 15.6171 5.49412 15.2566 5.56617C14.8961 5.63823 14.5225 5.60125 14.1829 5.45991C13.8433 5.31857 13.5531 5.07923 13.3489 4.77214C13.1447 4.46506 13.0357 4.10402 13.0357 3.73469C13.0357 3.23943 13.2315 2.76446 13.58 2.41426C13.9285 2.06406 14.4012 1.86732 14.894 1.86732ZM3.74399 11.2042C3.37644 11.2042 3.01715 11.0946 2.71155 10.8894C2.40594 10.6843 2.16776 10.3926 2.0271 10.0514C1.88645 9.71018 1.84965 9.33472 1.92135 8.97248C1.99306 8.61025 2.17005 8.27752 2.42994 8.01636C2.68983 7.75521 3.02096 7.57736 3.38144 7.5053C3.74193 7.43325 4.11558 7.47023 4.45514 7.61157C4.79471 7.7529 5.08494 7.99225 5.28914 8.29934C5.49334 8.60642 5.60233 8.96746 5.60233 9.33679C5.60233 9.83204 5.40654 10.307 5.05803 10.6572C4.70953 11.0074 4.23685 11.2042 3.74399 11.2042ZM14.894 13.0715C15.2616 13.0715 15.6209 13.181 15.9265 13.3862C16.2321 13.5914 16.4703 13.8831 16.6109 14.2243C16.7516 14.5655 16.7884 14.941 16.7167 15.3032C16.645 15.6654 16.468 15.9982 16.2081 16.2593C15.9482 16.5205 15.6171 16.6983 15.2566 16.7704C14.8961 16.8424 14.5225 16.8054 14.1829 16.6641C13.8433 16.5228 13.5531 16.2834 13.3489 15.9763C13.1447 15.6693 13.0357 15.3082 13.0357 14.9389C13.0357 14.4436 13.2315 13.9687 13.58 13.6185C13.9285 13.2683 14.4012 13.0715 14.894 13.0715Z"
                                                fill="#1B449C" />
                                        </svg>
                                    </a>

                                    <div class="flex gap-1 items-center">
                                        <i class="text-lg">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.50002 19C4.26134 19 0 14.7387 0 9.50002C0 4.26134 4.26134 0 9.50002 0C14.7387 0 19 4.26134 19 9.50002C19 14.7387 14.7379 19 9.50002 19ZM9.50002 1.52C5.0996 1.52 1.52 5.0996 1.52 9.50002C1.52 13.9004 5.0996 17.48 9.50002 17.48C13.9004 17.48 17.48 13.9004 17.48 9.50002C17.48 5.0996 13.8996 1.52 9.50002 1.52ZM9.4392 10.1992H4.56C4.35846 10.1992 4.16513 10.1191 4.0226 9.97659C3.88008 9.83407 3.80001 9.64079 3.80001 9.4392C3.80001 9.23765 3.88008 9.04433 4.0226 8.9018C4.16513 8.75927 4.35846 8.6792 4.56 8.6792H8.6792V3.04002C8.6792 2.83843 8.75927 2.64513 8.9018 2.5026C9.04433 2.36007 9.23765 2.28 9.4392 2.28C9.64079 2.28 9.83407 2.36007 9.97659 2.5026C10.1191 2.64513 10.1992 2.83843 10.1992 3.04002V9.4392C10.1992 9.64079 10.1191 9.83407 9.97659 9.97659C9.83407 10.1191 9.64079 10.1992 9.4392 10.1992Z"
                                                    fill="#1B449C" />
                                            </svg>
                                        </i>
                                        <span class="text-sm text-gray-400">{{ $blog->date }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col col-span-full justify-center items-center py-16 text-center bg-gray-50 rounded-md">
                        <p class="mb-4 text-lg font-semibold text-gray-700">
                            {{ label_text('global', 'No-Blogs-Available', __('auth.No blogs available at the moment.')) }}
                        </p>
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary rounded-md shadow hover:bg-primary/90 transition-colors">
                            {{ label_text('global', 'Back-To-Home', __('auth.Back to home')) }}
                        </a>
                    </div>
                @endforelse

            </div>

            <!-- Pagination Section -->
            <div class="flex justify-between items-center">
                <!-- Per Page على اليسار -->
                <div class="flex items-center">
                    <div class="relative">
                        <select id="perPageSelect"
                            class="py-3.5 pr-10 pl-24 text-black rounded-md border border-gray-200 appearance-none cursor-pointer text-md hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-32">
                            <option value="6">6</option>
                            <option value="9">9</option>
                            <option value="12">12</option>
                        </select>
                        <!-- PER PAGE من الشمال -->
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <span
                                class="text-sm font-medium text-gray-700">{{ label_text('global', 'Per-Page', __('auth.PER PAGE')) }}</span>
                        </div>
                        <!-- السهم من اليمين -->
                        <div class="flex absolute inset-y-0 right-0 items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pagination في المنتصف -->
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

                <!-- فراغ على اليمين للتوازن -->
                <div class="hidden w-32 md:block"></div>
            </div>
        </div>
    </section>

    <!--  Popup  -->
    <div id="sharePopup"
        class="popup-overlay fixed inset-0 bg-slate-950/50 bg-opacity-50 hidden items-center justify-center z-50"
        style="display: none;">
        <div class="popup-content bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    {{ label_text('global', 'share', __('site.share')) }}
                </h3>
                <button class="close-popup text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- URL Box with Copy Button -->
            <div class="mb-6">
                <div
                    class="flex items-center gap-3 bg-gray-50 border-2 border-gray-200 rounded-xl p-4 hover:border-blue-400 transition-all">
                    <input type="text" id="shareUrl" readonly
                        class="flex-1 bg-transparent text-gray-700 text-sm outline-none select-all" value="">
                    <button id="copyBtn"
                        class="copy-button flex-shrink-0 p-2 hover:bg-gray-200 rounded-lg transition-all group">
                        <!-- Copy Icon (default) -->
                        <svg class="copy-icon w-6 h-6 text-gray-600 group-hover:text-blue-600 transition"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                        <!-- Check Icon (success) -->
                        <svg class="check-icon w-6 h-6 text-green-500 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="space-y-3">
                <p class="text-sm text-gray-500 mb-4">
                    {{ label_text('global', 'Share via:', __('site.Share via:')) }}
                </p>

                <div class="grid grid-cols-4 gap-4">
                    <!-- WhatsApp -->
                    <a href="#"
                        class="whatsapp-share social-icon flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-green-50 group">
                        <div
                            class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">WhatsApp</span>
                    </a>

                    <!-- Facebook -->
                    <a href="#"
                        class="facebook-share social-icon flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-blue-50 group">
                        <div
                            class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Facebook</span>
                    </a>

                    <!-- Twitter -->
                    <a href="#"
                        class="twitter-share social-icon flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-sky-50 group">
                        <div
                            class="w-14 h-14 bg-sky-500 rounded-full flex items-center justify-center group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Twitter</span>
                    </a>

                    <!-- Telegram -->
                    <a href="#"
                        class="telegram-share social-icon flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-blue-50 group">
                        <div
                            class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Telegram</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('front/assets/js/blog.js') }}"></script>
    @endpush

</x-front-layout>
