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
                        <a href="index.html"
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
                        <span class="text-gray-900">{{ label_text('global', 'Course', __('site.Categories')) }}</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-7 text-3xl font-bold">
                {{ label_text('global', 'Categories', __('site.Categories')) }}
            </h2>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 gap-6 px-4 md:px-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <a href="{{ route('site.courses', ['category_id' => null]) }}"
                    class="group h-full rounded-3xl bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex h-full flex-col">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600">
                            <i class="fas fa-layer-group text-base"></i>
                        </div>

                        <div class="mt-5">
                            <h3 class="text-2xl font-bold text-slate-900">
                                {{ label_text('global', 'All Courses', __('site.All Courses')) }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-500">
                                {{ label_text('global', 'Browse the full list of available courses.', __('site.Browse the full list of available courses')) }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center">
                            <p class="mt-3 text-sm font-medium text-slate-400">
                                {{ $courses->count() ?? 0 }} {{ label_text('global', 'this-courses', __('site.this-courses')) }}
                            </p>
                            <span
                                class="mt-auto ml-auto inline-flex text-primary-600 transition-transform duration-300 group-hover:translate-x-1">
                                <i
                                    class="fas fa-arrow-right text-lg {{ $currentLocale == 'ar' ? 'rotate-180' : '' }}"></i>
                            </span>
                        </div>

                    </div>
                </a>

                @foreach ($categories as $category)
                    @php
                        $coursesCount = $courses->where('category_id', $category->id)->count();
                    @endphp
                    <a href="{{ route('site.courses', ['category_id' => $category->id]) }}"
                        class="group h-full rounded-3xl bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="flex h-full flex-col">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600">
                                <i class="fas fa-book-open text-base"></i>
                            </div>

                            <div class="mt-5">
                                <h3 class="text-2xl font-bold text-slate-900">
                                    {{ label_text('course_categories', $category->name, __('course_categories.' . $category->name)) }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    {{ label_text('global', 'Explore courses in this category.', __('site.Explore courses in this category')) }}
                                </p>

                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-3 text-sm font-medium text-slate-400">
                                    {{ $coursesCount ?? 0 }} {{ label_text('global', 'this-courses', __('site.this-courses')) }}
                                </p>
                                <span
                                    class="mt-auto ml-auto inline-flex text-primary-600 transition-transform duration-300 group-hover:translate-x-1">
                                    <i
                                        class="fas fa-arrow-right text-lg {{ $currentLocale == 'ar' ? 'rotate-180' : '' }}"></i>
                                </span>
                            </div>

                        </div>
                    </a>

                @endforeach
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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
                        <svg class="copy-icon w-6 h-6 text-gray-600 group-hover:text-blue-600 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
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
        <script src="{{ asset('front/assets/js/courses.js') }}"></script>
    @endpush

</x-front-layout>