<header class="fixed top-0 bottom-0 left-0 z-50 w-full bg-white shadow-sm h-[120px]">
    <!-- Top Navigation Bar -->
    <div class="text-white bg-primary">
        <div class="container px-4 py-2 mx-auto">
            <div class="hidden justify-between items-center lg:flex">
                <!-- Search Bar -->
                <div class="flex-1 max-w-[285px]">
                    <div class="relative group">
                        <input type="text"
                            placeholder="{{ label_text('global', 'site.search-what-you-need', __('site.search-what-you-need')) }}"
                            class="py-2 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-200 transition-all duration-300 transform placeholder:text-secondary placeholder:text-[12px] placeholder:font-light ps-4 pe-12 focus:outline-none focus:ring-2 focus:ring-white focus:shadow-lg">
                        <button
                            class="flex absolute inset-y-0 items-center transition-colors duration-300 cursor-pointer end-0 pe-4 group-focus-within:bg-primary-50 rounded-e-lg">

                            <!-- الخط الفاصل -->
                            <span class="w-px h-3/4 bg-[#72747561] me-2"></span>

                            <!-- الأيقونة -->
                            <i class="text-primary fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <!-- Language & Currency Dropdowns -->
                <div class="flex items-center">
                    <!-- Language Dropdown -->
                    <div class="relative language-dropdown">
                        <button
                            class="flex gap-1 items-center px-2 py-1 rounded-md transition-colors duration-300 hover:bg-primary-700">
                            <span class="text-sm font-medium">{{ $currentLangShort }}</span>
                            <i class="text-xs transition-transform duration-300 transform fas fa-chevron-down"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-32 bg-white rounded-lg border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                            <div class="py-2">
                                @forelse ($languages as $language)
                                    @php
                                        $short = strtolower($language->shortname ?? '');
                                        $isActive = $short !== '' && $short === strtolower($locale ?? '');
                                        $optionClasses = $isActive
                                            ? 'bg-primary-50 text-primary-600 ps-6'
                                            : 'hover:bg-gray-50 hover:text-primary-600 hover:ps-6';
                                        $switchUrl = $languageUrls[$short] ?? url($short === '' ? '/' : $short);
                                    @endphp
                                    <a href="{{ $switchUrl }}" data-lang="{{ $short }}"
                                        @if (!empty($language->direction)) data-direction="{{ $language->direction }}" @endif
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 {{ $optionClasses }}">
                                        {{ $language->name }}
                                    </a>
                                @empty
                                    <span class="block px-4 py-2 text-sm text-gray-500">
                                        {{ __('No languages available') }}
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Currency Dropdown -->
                    <div class="relative currency-dropdown">
                        <button
                            class="flex gap-1 items-center px-2 py-1 rounded-md transition-colors duration-300 hover:bg-primary-700">
                            <span class="text-sm font-medium">
                                {{ label_text('global', 'site.currency-usd', __('site.USD')) }}
                            </span>
                            <i class="text-xs transition-transform duration-300 transform fas fa-chevron-down"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-32 bg-white rounded-lg border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                            <div class="py-2">
                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.currency-usd-symbol', __('site.USD ($)')) }}
                                </a>
                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.currency-eur-symbol', __('site.EUR (€)')) }}
                                </a>
                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.currency-gbp-symbol', __('site.GBP (£)')) }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex justify-start items-center lg:hidden">
                <button class="p-2 text-white rounded-lg transition-all duration-300 hover:bg-white hover:shadow-sm"
                    id="mobile-search-bar-toggle">
                    <i class="text-xl fas fa-search"></i>
                </button>
                <div class="hidden flex-1 max-w-full" id="mobile-search-bar">
                    <div class="relative group">
                        <input type="text"
                            placeholder="{{ label_text('global', 'site.search-what-you-need', __('site.Search what you need')) }}"
                            class="py-2 w-full text-gray-900 bg-white rounded-lg border border-gray-200 transition-all duration-300 transform ps-4 pe-12 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent focus:shadow-lg">
                        <button
                            class="absolute inset-y-0 px-4 border-l-2 border-[#727475] transition-colors duration-300 end-0 group-focus-within:bg-primary-50 rounded-e-lg">
                            <i class="text-gray-600 fas fa-search group-focus-within:text-primary-600"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="bg-gray-50">
        <div class="container px-4 mx-auto">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('front/assets/imgs/logo.png') }}"
                            alt="{{ label_text('global', 'site.logo-alt', __('site.JINN Education')) }}"
                            class="w-auto h-10">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden items-center lg:flex text-[15px]">
                    <!-- Home Link -->
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        {{ label_text('global', 'site.Home', __('site.Home')) }}
                    </a>

                    <!-- Categories Dropdown -->
                    <div class="relative nav-dropdown">
                        <button class="flex gap-1 items-center nav-link group">
                            <span>{{ label_text('global', 'site.Categories', __('site.Categories')) }}</span>
                            <i
                                class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-64 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                            <div>

                                <!-- Programming with Subcategory -->
                                <div class="relative border-b border-gray-100 subcategory-item group/sub">
                                    <a href="#"
                                        class="flex justify-between items-center px-4 py-3 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:font-bold hover:ps-6 group hover:rounded-xl">
                                        <div class="flex gap-3 items-center">
                                            <span>{{ label_text('global', 'site.Programming', __('site.Programming')) }}</span>
                                        </div>
                                        <i
                                            class="text-xs fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }} group-hover:text-white"></i>
                                    </a>
                                    <!-- Subcategory -->
                                    <div
                                        class="absolute top-0 invisible w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-x-2 start-full ms-2">
                                        <div class="py-2">
                                            <a href="#"
                                                class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">
                                                {{ label_text('global', 'site.Web Development', __('site.Web Development')) }}
                                            </a>
                                            <a href="#"
                                                class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">
                                                {{ label_text('global', 'site.Mobile Apps', __('site.Mobile Apps')) }}
                                            </a>
                                            <a href="#"
                                                class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">
                                                {{ label_text('global', 'site.Data Science', __('site.Data Science')) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- 
                                <!-- Design with Subcategory -->
                                <div class="relative border-b border-gray-100 subcategory-item group/sub">
                                    <a href="#"
                                        class="flex justify-between items-center px-4 py-3 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 group hover:rounded-xl">
                                        <div class="flex gap-3 items-center">
                                            <span>Design</span>
                                        </div>
                                        <i class="text-xs fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} group-hover:text-white"></i>
                                    </a>
                                    <div
                                        class="absolute top-0 invisible w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-x-2 start-full ms-2">
                                        <div class="py-2">
                                            <a href="#"
                                                class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">
                                                UI/UX Design
                                            </a>
                                            <a href="#"
                                                class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">
                                                Graphic Design
                                            </a>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- Business -->
                                <a href="#"
                                    class="flex gap-3 items-center px-4 py-3 text-black border-b border-gray-100 transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 hover:rounded-md">
                                    <span>{{ label_text('global', 'site.Business', __('site.Business')) }}</span>
                                </a>

                                <!-- Languages -->
                                <a href="#"
                                    class="flex gap-3 items-center px-4 py-3 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 hover:rounded-md">
                                    <span>{{ label_text('global', 'site.Languages', __('site.Languages')) }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- K-12 Link -->
                    <a href="#" class="nav-link {{ request()->is('k-12') ? 'active' : '' }}">
                        {{ label_text('global', 'site.K-12', __('site.K-12')) }}
                    </a>

                    <!-- Blog Link -->
                    <a href="{{ route('site.blog') }}"
                        class="nav-link {{ request()->is('blog') || request()->is('blog/*') ? 'active' : '' }}">
                        {{ label_text('global', 'site.Blog', __('site.Blog')) }}
                    </a>

                    <!-- Classes Dropdown -->
                    <div class="relative nav-dropdown">
                        <button class="flex gap-1 items-center nav-link group">
                            <span>{{ label_text('global', 'site.Classes', __('site.Classes')) }}</span>
                            <i
                                class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                            <div class="py-3">
                                <a href="{{ route('site.online_group_classes') }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.online-group-classes', __('site.online group classes')) }}
                                </a>
                                <a href="{{ route('site.online_private_classes') }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.Private-Classes', __('site.Private Classes')) }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Events Link -->
                    <a href="{{ route('site.coming_soon') }}" class="nav-link">
                        {{ label_text('global', 'site.events', __('site.events')) }}
                    </a>

                    <!-- Help Dropdown -->
                    <div class="relative nav-dropdown">
                        <button class="flex gap-1 items-center nav-link group">
                            <span>{{ label_text('global', 'site.helps', __('site.helps')) }}</span>
                            <i
                                class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                            <div class="py-3">
                                <a href="#"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.help-center', __('site.help-center')) }}
                                </a>
                                <a href="#"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">{{ label_text('global', 'site.faq', __('site.faq')) }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- About Dropdown -->
                    <div class="relative nav-dropdown">
                        <button class="flex gap-1 items-center nav-link group">
                            <span>{{ label_text('global', 'site.about', __('site.about')) }}</span>
                            <i
                                class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                        </button>
                        <div
                            class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                            <div class="py-3">
                                <a href="{{ route('site.pages.show', ['slug' => 'about-us']) }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.about-us', __('site.about-us')) }}
                                </a>
                                <a href="{{ route('site.pages.show', ['slug' => 'how-jin-works']) }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.how-jin-works', __('site.how-jin-works')) }}
                                </a>
                                <a href="{{ route('site.pages.show', ['slug' => 'terms-of-use']) }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.terms-of-use', __('site.terms-of-use')) }}
                                </a>
                                <a href="{{ route('site.pages.show', ['slug' => 'Policy']) }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.privacy-policy', __('site.privacy-policy')) }}
                                </a>
                                <a href="{{ route('site.contact', ['']) }}"
                                    class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                    {{ label_text('global', 'site.contact-us', __('site.contact-us')) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Auth Section -->
                <div class="hidden gap-4 items-center lg:flex">

                    @guest
                        <!-- Guest User (Login/Signup) -->
                        <div class="flex items-center guest-auth text-[15px]">
                            <button type="button" data-open="#loginModal" id="btn-open-loginModal"
                                class="cursor-pointer overflow-hidden relative px-2 py-0 font-medium text-gray-700 transition-all	duration-300 hover:text-primary-600 group rtl:border-black rtl:border-l-2">
                                <span
                                    class="relative z-10">{{ label_text('global', 'site.login', __('site.login')) }}</span>
                                <span
                                    class="absolute bottom-0 w-0 h-0.5 transition-all duration-500 start-0 bg-primary-600 group-hover:w-full"></span>
                            </button>
                            <a href="{{ route('register') }}"
                                class="overflow-hidden relative px-2 py-0 font-medium text-gray-700 transition-all duration-300 ltr:border-black ltr:border-l-2 hover:text-primary-600 group">
                                <span
                                    class="relative z-10">{{ label_text('global', 'site.sign-up', __('site.sign-up')) }}</span>
                                <span
                                    class="absolute bottom-0 w-0 h-0.5 transition-all duration-500 start-0 bg-primary-600 group-hover:w-full"></span>
                            </a>
                        </div>
                    @endguest

                    @auth
                        @php
                            $user = Auth::user() ?? null;
                        @endphp
                        <!-- Logged In User (Hidden by default) -->
                        <div class="relative user-menu-mobile">
                            <button
                                class="flex gap-2 items-center p-2 text-gray-700 rounded-lg transition-all duration-300 hover:text-primary-600 hover:bg-white hover:shadow-sm group">
                                <img src="{{ optional($user->profile()->first())->avatar_path ? asset('storage/' . optional($user->profile()->first())->avatar_path) : asset('front/assets/imgs/user-avatar.jpg') }}"
                                    alt="User" class="w-8 h-8 rounded-full">
                                <div class="flex flex-col gap-0.5 items-start">
                                    <span class="font-medium">{{ $user->full_name }}</span>
                                    @if (!$user->email_verified_at)
                                        <span class="text-white bg-red-500 rounded-md px-1 py-0.5"
                                            style="font-size: 10px;">{{ label_text('global', 'site.email-not-verified', __('site.Email Not Verified')) }}</span>
                                    @endif
                                </div>

                                <i
                                    class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                            </button>

                            <div
                                class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                                <div class="py-3">
                                    <a href="{{ route('redirect.dashboard', ['redirect_to' => '']) }}"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-user text-primary-600"></i>
                                        <span>{{ label_text('global', 'site.my-profile', __('site.My Profile')) }}</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-edit text-primary-600"></i>
                                        <span>{{ label_text('global', 'site.Update Profile', __('site.Update Profile')) }}</span>
                                    </a>
                                    <a href="{{ route('redirect.dashboard', ['redirect_to' => '/group-classes/index']) }}"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-book text-primary-600"></i>
                                        <span>
                                            @if ($user->type == 0)
                                                {{ label_text('global', 'site.Courses Managment', __('site.Courses Managment')) }}
                                            @else
                                                {{ label_text('global', 'site.My-Courses', __('site.My Courses')) }}
                                            @endif
                                        </span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-cog text-primary-600"></i>
                                        <span>{{ label_text('global', 'site.Settings', __('site.Settings')) }}</span>
                                    </a>
                                    <hr class="my-2 border-gray-100">
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit"
                                            class="flex gap-3 items-center px-4 py-3 text-red-600 transition-all duration-300 hover:bg-red-50 hover:ps-6">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>{{ label_text('global', 'site.Logout', __('site.Logout')) }}</span>
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle"
                    class="p-2 text-gray-700 rounded-lg transition-all duration-300 lg:hidden hover:text-primary-600 hover:bg-white hover:shadow-sm">
                    <i class="text-xl fas fa-bars"></i>
                </button>

            </div>
        </div>
    </div>
    <!-- Mobile Menu - يوضع داخل الـ header قبل إغلاق header -->
    <div id="mobile-menu"
        class="hidden absolute right-0 left-0 top-full z-50 bg-white border-t border-gray-100 shadow-lg lg:hidden">
        <div class="px-4 py-4 space-y-2 h-[calc(100vh-120px)] overflow-y-auto">

            <!-- Home Link -->
            <a href="{{ route('home') }}"
                class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                <i class="w-5 text-center fas fa-home text-primary-600 me-2"></i>
                <span>{{ label_text('global', 'site.Home', __('site.Home')) }}</span>
            </a>

            <!-- Categories Dropdown -->
            <div class="mobile-dropdown">
                <button
                    class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                    <div class="flex items-center">
                        <i class="w-5 text-center fas fa-th-large text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Categories', __('site.Categories')) }}</span>
                    </div>
                    <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                </button>
                <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-code text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Programming', __('site.Programming')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-palette text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Design', __('site.Design')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-briefcase text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Business', __('site.Business')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-language text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Languages', __('site.Languages')) }}</span>
                    </a>
                </div>
            </div>

            <!-- K-12 Link -->
            <a href="#"
                class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                <i class="w-5 text-center fas fa-graduation-cap text-primary-600 me-2"></i>
                <span>{{ label_text('global', 'site.K-12', __('site.K-12')) }}</span>
            </a>

            <!-- Blog Link -->
            <a href="{{ route('site.blog') }}"
                class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                <i class="w-5 text-center fas fa-article text-primary-600 me-2"></i>
                <span>{{ label_text('global', 'site.Blog', __('site.Blog')) }}</span>
            </a>

            <!-- Classes Dropdown -->
            <div class="mobile-dropdown">
                <button
                    class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                    <div class="flex items-center">
                        <i class="w-5 text-center fas fa-chalkboard-teacher text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Classes', __('site.Classes')) }}</span>
                    </div>
                    <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                </button>
                <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                    <a href="{{ route('site.online_group_classes') }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-video text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Online-Group-Classes', __('site.Online Group Classes')) }}</span>
                    </a>
                    <a href="{{ route('site.online_private_classes') }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-users text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.Group-Classes', __('site.Private Classes')) }}</span>
                    </a>
                </div>
            </div>

            <!-- Events Link -->
            <a href="#"
                class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                <i class="w-5 text-center fas fa-calendar-alt text-primary-600 me-2"></i>
                <span>{{ label_text('global', 'site.events', __('site.events')) }}</span>
            </a>

            <!-- Help Dropdown -->
            <div class="mobile-dropdown">
                <button
                    class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                    <div class="flex items-center">
                        <i class="w-5 text-center fas fa-question-circle text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.helps', __('site.helps')) }}</span>
                    </div>
                    <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                </button>
                <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-life-ring text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.help-center', __('site.help-center')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-envelope text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.contact-us', __('site.contact-us')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-question text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.faq', __('site.faq')) }}</span>
                    </a>
                </div>
            </div>

            <!-- About Dropdown -->
            <div class="mobile-dropdown">
                <button
                    class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                    <div class="flex items-center">
                        <i class="w-5 text-center fas fa-info-circle text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.about', __('site.about')) }}</span>
                    </div>
                    <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                </button>
                <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                    <a href="{{ route('site.pages.show', ['slug' => 'about-us']) }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-building text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.about-us', __('site.about-us')) }}</span>
                    </a>
                    <a href="{{ route('site.pages.show', ['slug' => 'how-jin-works']) }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-users text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.how-jin-works', __('site.how-jin-works')) }}</span>
                    </a>
                    <a href="{{ route('site.pages.show', ['slug' => 'terms-of-use']) }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-file-alt text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.terms-of-use', __('site.terms-of-use')) }}</span>
                    </a>
                    <a href="{{ route('site.pages.show', ['slug' => 'Policy']) }}"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-shield-alt text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.privacy-policy', __('site.privacy-policy')) }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                        <i class="w-5 text-center fas fa-envelope text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.contact-us', __('site.contact-us')) }}</span>
                    </a>
                </div>
            </div>

            @guest
                <div class="pt-4 mt-4 space-y-2 border-t border-gray-100 guest-auth">
                    <button type="button" data-open="#loginModal"
                        class="flex justify-center items-center w-full px-3 py-2 text-gray-700 rounded-lg border border-gray-200 transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50">
                        <i class="fas fa-sign-in-alt text-primary-600 me-2"></i>
                        <span>{{ label_text('global', 'site.login', __('site.login')) }}</span>
                    </button>
                    <a href="{{ route('register') }}"
                        class="flex justify-center items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 bg-primary-600 hover:bg-primary-700">
                        <i class="fas fa-user-plus me-2"></i>
                        <span>{{ label_text('global', 'site.sign-up', __('site.sign-up')) }}</span>
                    </a>
                </div>
            @endguest

            @auth
                @php
                    $user = Auth::user() ?? null;
                @endphp
                <div class="mobile-dropdown user-menu-mobile">
                    <button
                        class="flex gap-2 items-center p-2 text-gray-700 rounded-lg transition-all duration-300 hover:text-primary-600 hover:bg-white hover:shadow-sm group mobile-dropdown-btn">
                        <img src="{{ optional($user->profile()->first())->avatar_path ? asset('storage/' . optional($user->profile()->first())->avatar_path) : asset('front/assets/imgs/user-avatar.jpg') }}"
                            alt="User" class="w-8 h-8 rounded-full">
                        <span
                            class="font-medium">{{ $user->profile()->first()?->first_name . ' ' . $user->profile()->first()?->last_name ?? 'User Name' }}</span>
                        <i
                            class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="{{ route('redirect.dashboard', ['redirect_to' => '']) }}"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-user text-primary-600"></i>
                            <span>{{ label_text('global', 'site.My-Profile', __('site.My Profile')) }}</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-book text-primary-600"></i>
                            <span>{{ label_text('global', 'site.My-Courses', __('site.My Courses')) }}</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-cog text-primary-600"></i>
                            <span>{{ label_text('global', 'site.Settings', __('site.Settings')) }}</span>
                        </a>
                        <hr class="my-2 border-gray-100">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit"
                                class="flex gap-3 items-center px-3 py-2 text-red-600 rounded-lg transition-colors duration-200 hover:bg-red-50 nav-mobile-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ label_text('global', 'site.Logout', __('site.Logout')) }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- Language Dropdown -->
            <div class="mobile-dropdown pt-4 mt-4 border-t border-gray-100">
                <button
                    class="flex justify-center items-center px-3 py-2 w-full text-gray-700 rounded-lg border border-gray-200 transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                    <div class="flex items-center">
                        <i class="w-5 text-center fas fa-globe text-primary-600 me-2"></i>
                        <span>{{ $currentLangShort }}</span>
                    </div>
                    <i class="absolute right-[35px] text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                </button>
                <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                    @forelse ($languages as $language)
                        @php
                            $short = strtolower($language->shortname ?? '');
                            $isActive = $short !== '' && $short === strtolower($locale ?? '');
                            $optionClasses = $isActive
                                ? 'bg-primary-50 text-primary-600 ps-6'
                                : 'hover:bg-gray-50 hover:text-primary-600 hover:ps-6';
                            $switchUrl = $languageUrls[$short] ?? url($short === '' ? '/' : $short);
                        @endphp
                        <a href="{{ $switchUrl }}" data-lang="{{ $short }}"
                            @if (!empty($language->direction)) data-direction="{{ $language->direction }}" @endif
                            class="{{ $optionClasses }} flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-globe text-primary-600 me-2"></i>
                            <span>{{ $language->name }}</span>
                        </a>
                    @empty
                        <span class="block px-4 py-2 text-sm text-gray-500">
                            {{ label_text('global', 'site.No-languages-available', __('site.No languages available')) }}
                        </span>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</header>
