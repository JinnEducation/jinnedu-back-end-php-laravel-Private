<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


    <!-- Swiper -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/swiper-bundle.min.css') }}" />
    <script src="{{ asset('front/assets/js/swiper-bundle.min.js') }}"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/all.min.css') }}">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/tailwind.css') }}">
</head>

<body class="bg-gray-50">

    <!-- Universal RTL/LTR Header -->
    <header class="fixed top-0 bottom-0 left-0 z-50 w-full bg-white shadow-sm h-[120px]">
        <!-- Top Navigation Bar -->
        <div class="text-white bg-primary">
            <div class="container px-4 py-2 mx-auto">
                <div class="hidden justify-between items-center lg:flex">
                    <!-- Search Bar -->
                    <div class="flex-1 max-w-[285px]">
                        <div class="relative group">
                            <input type="text" placeholder="search what you need"
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
                                <span class="text-sm font-medium">EN</span>
                                <i class="text-xs transition-transform duration-300 transform fas fa-chevron-down"></i>
                            </button>
                            <div
                                class="absolute top-full invisible z-50 mt-2 w-32 bg-white rounded-lg border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                                <div class="py-2">
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">English</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">العربية</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Français</a>
                                </div>
                            </div>
                        </div>

                        <!-- Currency Dropdown -->
                        <div class="relative currency-dropdown">
                            <button
                                class="flex gap-1 items-center px-2 py-1 rounded-md transition-colors duration-300 hover:bg-primary-700">
                                <span class="text-sm font-medium">USD</span>
                                <i class="text-xs transition-transform duration-300 transform fas fa-chevron-down"></i>
                            </button>
                            <div
                                class="absolute top-full invisible z-50 mt-2 w-32 bg-white rounded-lg border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                                <div class="py-2">
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">USD
                                        ($)</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">EUR
                                        (€)</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">GBP
                                        (£)</a>
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
                            <input type="text" placeholder="Search what you need"
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
                        <img src="{{ asset('front/assets/imgs/logo.png') }}" alt="JINN Education" class="w-auto h-10">
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden items-center lg:flex text-[15px]">
                        <!-- Home Link -->
                        <a href="index.html"
                            class="px-3 py-2 font-bold rounded-lg transition-all duration-300 text-primary-600 hover:text-primary-600 hover:bg-white hover:shadow-sm">
                            HOME
                        </a>

                        <!-- Categories Dropdown -->
                        <div class="relative nav-dropdown">
                            <button
                                class="flex gap-1 items-center px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm group">
                                <span>Categories</span>
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
                                                <span>Programming</span>
                                            </div>
                                            <i class="text-xs fas fa-chevron-right group-hover:text-white"></i>
                                        </a>
                                        <!-- Subcategory -->
                                        <div
                                            class="absolute top-0 invisible w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-x-2 start-full ms-2">
                                            <div class="py-2">
                                                <a href="#"
                                                    class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">Web
                                                    Development</a>
                                                <a href="#"
                                                    class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">Mobile
                                                    Apps</a>
                                                <a href="#"
                                                    class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">Data
                                                    Science</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Design with Subcategory -->
                                    <div class="relative border-b border-gray-100 subcategory-item group/sub">
                                        <a href="#"
                                            class="flex justify-between items-center px-4 py-3 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 group hover:rounded-xl">
                                            <div class="flex gap-3 items-center">
                                                <span>Design</span>
                                            </div>
                                            <i class="text-xs fas fa-chevron-right group-hover:text-white"></i>
                                        </a>
                                        <div
                                            class="absolute top-0 invisible w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-x-2 start-full ms-2">
                                            <div class="py-2">
                                                <a href="#"
                                                    class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">UI/UX
                                                    Design</a>
                                                <a href="#"
                                                    class="block px-4 py-2 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6">Graphic
                                                    Design</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Business -->
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-black border-b border-gray-100 transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 hover:rounded-md">
                                        <span>Business</span>
                                    </a>

                                    <!-- Languages -->
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-black transition-all duration-300 hover:bg-primary hover:text-white hover:ps-6 hover:rounded-md">
                                        <span>Languages</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- K-12 Link -->
                        <a href="k-12.html"
                            class="px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm">
                            K-12
                        </a>

                        <!-- Blog Link -->
                        <a href="{{ route('site.blog') }}"
                            class="px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm">
                            Blog
                        </a>

                        <!-- Classes Dropdown -->
                        <div class="relative nav-dropdown">
                            <button
                                class="flex gap-1 items-center px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm group">
                                <span>Classes</span>
                                <i
                                    class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                            </button>
                            <div
                                class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                                <div class="py-3">
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Live
                                        Classes</a>
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Group
                                        Classes</a>
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Private
                                        Classes</a>
                                </div>
                            </div>
                        </div>

                        <!-- Events Link -->
                        <a href="#"
                            class="px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm">
                            events
                        </a>

                        <!-- Help Dropdown -->
                        <div class="relative nav-dropdown">
                            <button
                                class="flex gap-1 items-center px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm group">
                                <span>Help</span>
                                <i
                                    class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                            </button>
                            <div
                                class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                                <div class="py-3">
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Help
                                        Center</a>
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Contact
                                        Us</a>
                                    <a href="#"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">FAQ</a>
                                </div>
                            </div>
                        </div>

                        <!-- About Dropdown -->
                        <div class="relative nav-dropdown">
                            <button
                                class="flex gap-1 items-center px-3 py-2 font-medium text-gray-700 rounded-lg transition-all duration-300 hover:text-primary hover:font-bold hover:bg-white hover:shadow-sm group">
                                <span>About</span>
                                <i
                                    class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                            </button>
                            <div
                                class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 start-0">
                                <div class="py-3">
                                    <a href="about.html"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">About
                                        Us</a>
                                    <a href="our-team.html"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Our
                                        Team</a>
                                    <a href="careers.html"
                                        class="block px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">Careers</a>
                                </div>
                            </div>
                        </div>

                    </nav>

                    <!-- Auth Section -->
                    <div class="hidden gap-4 items-center lg:flex">

                        <!-- Guest User (Login/Signup) -->
                        <div class="flex items-center guest-auth text-[15px]">
                            <button type="button"
                                data-open="#loginModal"
                                class="overflow-hidden relative px-2 py-0 font-medium text-gray-700 transition-all duration-300 hover:text-primary-600 group rtl:border-black rtl:border-l-2">
                                <span class="relative z-10">Login</span>
                                <span
                                    class="absolute bottom-0 w-0 h-0.5 transition-all duration-500 start-0 bg-primary-600 group-hover:w-full"></span>
                            </button>
                            <a href="./signup.html"
                                class="overflow-hidden relative px-2 py-0 font-medium text-gray-700 transition-all duration-300 ltr:border-black ltr:border-l-2 hover:text-primary-600 group">
                                <span class="relative z-10">Sign Up</span>
                                <span
                                    class="absolute bottom-0 w-0 h-0.5 transition-all duration-500 start-0 bg-primary-600 group-hover:w-full"></span>
                            </a>
                        </div>

                        <!-- Logged In User (Hidden by default) -->
                        <div class="hidden relative user-menu-mobile">
                            <button
                                class="flex gap-2 items-center p-2 text-gray-700 rounded-lg transition-all duration-300 hover:text-primary-600 hover:bg-white hover:shadow-sm group">
                                <img src="{{ asset('front/assets/imgs/user-avatar.jpg') }}" alt="User"
                                    class="w-8 h-8 rounded-full">
                                <span class="font-medium">John Doe</span>
                                <i
                                    class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                            </button>

                            <div
                                class="absolute top-full invisible z-50 mt-2 w-48 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 transition-all duration-300 transform translate-y-2 end-0">
                                <div class="py-3">
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-user text-primary-600"></i>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-book text-primary-600"></i>
                                        <span>My Courses</span>
                                    </a>
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-primary-600 hover:ps-6">
                                        <i class="fas fa-cog text-primary-600"></i>
                                        <span>Settings</span>
                                    </a>
                                    <hr class="my-2 border-gray-100">
                                    <a href="#"
                                        class="flex gap-3 items-center px-4 py-3 text-red-600 transition-all duration-300 hover:bg-red-50 hover:ps-6">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>

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
                <a href="index.html"
                    class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                    <i class="w-5 text-center fas fa-home text-primary-600 me-2"></i>
                    <span>الرئيسية</span>
                </a>

                <!-- Categories Dropdown -->
                <div class="mobile-dropdown">
                    <button
                        class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                        <div class="flex items-center">
                            <i class="w-5 text-center fas fa-th-large text-primary-600 me-2"></i>
                            <span>الفئات</span>
                        </div>
                        <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-code text-primary-600 me-2"></i>
                            <span>البرمجة</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-palette text-primary-600 me-2"></i>
                            <span>التصميم</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-language text-primary-600 me-2"></i>
                            <span>اللغات</span>
                        </a>
                    </div>
                </div>

                <!-- K-12 Link -->
                <a href="#"
                    class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                    <i class="w-5 text-center fas fa-graduation-cap text-primary-600 me-2"></i>
                    <span>K-12</span>
                </a>

                <!-- Blog Link -->
                <a href="{{ route('site.blog') }}"
                    class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                    <i class="w-5 text-center fas fa-article text-primary-600 me-2"></i>
                    <span>المدونة</span>
                </a>

                <!-- Classes Dropdown -->
                <div class="mobile-dropdown">
                    <button
                        class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                        <div class="flex items-center">
                            <i class="w-5 text-center fas fa-chalkboard-teacher text-primary-600 me-2"></i>
                            <span>الفصول</span>
                        </div>
                        <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-video text-primary-600 me-2"></i>
                            <span>فصول مباشرة</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-users text-primary-600 me-2"></i>
                            <span>فصول جماعية</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-user text-primary-600 me-2"></i>
                            <span>فصول خاصة</span>
                        </a>
                    </div>
                </div>

                <!-- Events Link -->
                <a href="#"
                    class="block px-3 py-2 text-gray-700 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                    <i class="w-5 text-center fas fa-calendar-alt text-primary-600 me-2"></i>
                    <span>الأحداث</span>
                </a>

                <!-- Help Dropdown -->
                <div class="mobile-dropdown">
                    <button
                        class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                        <div class="flex items-center">
                            <i class="w-5 text-center fas fa-question-circle text-primary-600 me-2"></i>
                            <span>المساعدة</span>
                        </div>
                        <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-life-ring text-primary-600 me-2"></i>
                            <span>مركز المساعدة</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-envelope text-primary-600 me-2"></i>
                            <span>اتصل بنا</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-question text-primary-600 me-2"></i>
                            <span>الأسئلة الشائعة</span>
                        </a>
                    </div>
                </div>

                <!-- About Dropdown -->
                <div class="mobile-dropdown">
                    <button
                        class="flex justify-between items-center px-3 py-2 w-full text-gray-700 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50 mobile-dropdown-btn">
                        <div class="flex items-center">
                            <i class="w-5 text-center fas fa-info-circle text-primary-600 me-2"></i>
                            <span>حولنا</span>
                        </div>
                        <i class="text-xs transition-transform duration-300 fas fa-chevron-down"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-building text-primary-600 me-2"></i>
                            <span>من نحن</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-users text-primary-600 me-2"></i>
                            <span>فريقنا</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="w-5 text-center fas fa-briefcase text-primary-600 me-2"></i>
                            <span>الوظائف</span>
                        </a>
                    </div>
                </div>

                <!-- Auth Section -->
                <div class="pt-4 mt-4 space-y-2 border-t border-gray-100 guest-auth">
                    <button type="button"
                        data-open="#loginModal"
                        class="flex justify-center items-center px-3 py-2 text-gray-700 rounded-lg border border-gray-200 transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50">
                        <i class="fas fa-sign-in-alt text-primary-600 me-2"></i>
                        <span>Login</span>
                    </button>
                    <a href="./signup.html"
                        class="flex justify-center items-center px-3 py-2 text-white rounded-lg transition-colors duration-200 bg-primary-600 hover:bg-primary-700">
                        <i class="fas fa-user-plus me-2"></i>
                        <span>Sign Up</span>
                    </a>

                </div>

                <div class="hidden mobile-dropdown user-menu-mobile">
                    <button
                        class="flex gap-2 items-center p-2 text-gray-700 rounded-lg transition-all duration-300 hover:text-primary-600 hover:bg-white hover:shadow-sm group mobile-dropdown-btn">
                        <img src="{{ asset('front/assets/imgs/user-avatar.jpg') }}" alt="User"
                            class="w-8 h-8 rounded-full">
                        <span class="font-medium">John Doe</span>
                        <i
                            class="text-xs transition-transform duration-300 transform fas fa-chevron-down group-hover:rotate-180"></i>
                    </button>
                    <div class="hidden mt-2 space-y-1 mobile-dropdown-content ps-6">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-user text-primary-600"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-book text-primary-600"></i>
                            <span>My Courses</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 nav-mobile-link hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-cog text-primary-600"></i>
                            <span>Settings</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-gray-600 rounded-lg transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>

                <!-- Language Toggle (Mobile Only) -->
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <button id="mobile-direction-toggle"
                        class="flex justify-center items-center px-3 py-2 w-full text-gray-700 rounded-lg border border-gray-200 transition-colors duration-200 hover:text-primary-600 hover:bg-gray-50">
                        <i class="fas fa-globe text-primary-600 me-2"></i>
                        <span>English</span>
                    </button>
                </div>

            </div>
        </div>
    </header>




    <!-- Hero Section -->
    {{ $slot }}




    <!-- Footer -->
    <footer class="bg-[#374151] text-white px-4">
        <!-- المحتوى الرئيسي للفوتر -->
        <div class="container pt-12 pb-6 mx-auto">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-7 lg:gap-12">

                <!-- العمود الأول: الشعار والوصف -->
                <div class="col-span-1 text-center md:col-span-2 md:text-left rtl:md:text-right">
                    <div class="flex justify-center mb-4 lg:justify-start">
                        <img src="{{ asset('front/assets/imgs/logo-white.png') }}" alt="Jinn Education Logo"
                            class="w-32">
                    </div>
                    <p class="text-sm leading-relaxed text-gray-300 md:text-[13px]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam.
                    </p>
                </div>

                <!-- العمود الثاني: قوائم الروابط -->
                <div class="grid grid-cols-2 col-span-1 gap-2 md:col-span-3 lg:grid-cols-3 md:grid-cols-2">
                    <div>
                        <ul class="space-y-2">
                            <li>
                                <a href="index.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="about.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="how-jinn-edu-works.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    How JINN Works
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Live Private Classes
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Live Group Classes
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Categories
                                </a>
                            </li>
                            <li>
                                <a href="k-12.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    K-12
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2">
                            <li>
                                <a href="get-in-touch.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Get In Touch
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Help For Tutor
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Student Guide
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Tutor Guide
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Help Center
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Technical Support
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Free Courses
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2">
                            <li>
                                <a href="terms.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Terms and Conditions
                                </a>
                            </li>
                            <li>
                                <a href="privacy-policy.html"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Privacy and Usage Policy
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Intellectual Property and Copyrights
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                    <span
                                        class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                    Refund Policy
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- العمود الثالث: النشرة الإخبارية -->
                <div class="col-span-1 text-center md:col-span-2 md:text-left rtl:md:text-right">
                    <p class="mb-6 text-sm text-gray-300 md:text-base">
                        Subscribe to our newsletter Updated digest of what's new and exciting from JINN EDU.
                    </p>
                    <!-- نموذج الاشتراك -->
                    <div class="flex gap-3 mx-auto max-w-md md:mx-0">
                        <input type="email" placeholder="Enter your email"
                            class="flex-1 px-4 py-3 min-w-0 placeholder-gray-400 text-black bg-white rounded-lg border border-gray-500 transition-all duration-300 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-opacity-50" />

                        <button
                            class="px-6 py-3 font-medium text-white rounded-lg transition-all duration-300 transform bg-primary hover:bg-primary-700 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-opacity-50">
                            Subscribe
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- أسفل الفوتر -->
        <div class="container px-6 pb-6 mx-auto">
            <!-- خط الفصل -->
            <div class="mb-6 border-t border-gray-600"></div>

            <div class="flex flex-col-reverse gap-4 justify-between items-center md:flex-row">
                <!-- حقوق النشر -->
                <div class="flex items-center text-sm text-gray-400">
                    <i class="mr-2 far fa-copyright rtl:ml-2 rtl:mr-0"></i>
                    2025 JINN EDU. All rights reserved.
                </div>

                <!-- أيقونات السوشال ميديا -->
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary hover:scale-110 group">
                        <i class="text-md fab fa-facebook-f group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-pink-600 hover:scale-110 group">
                        <i class="text-md fab fa-instagram group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-gray-800 hover:scale-110 group">
                        <i class="text-md fab fa-x-twitter group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary-700 hover:scale-110 group">
                        <i class="text-md fab fa-linkedin-in group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-red-600 hover:scale-110 group">
                        <i class="text-md fab fa-youtube group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-purple-600 hover:scale-110 group">
                        <i class="text-md fab fa-discord group-hover:text-white"></i>
                    </a>
                    <a href="#"
                        class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary-900 hover:scale-110 group">
                        <i class="text-md fab fa-telegram-plane group-hover:text-white"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- زر واتساب عائم -->
    <div class="fixed right-6 bottom-6 z-50 rtl:left-6 rtl:right-auto group">
        <a href="https://wa.me/1234567890?text=مرحبا، أحتاج مساعدة" target="_blank"
            class="flex relative justify-center items-center w-14 h-14 text-white bg-green-500 rounded-full shadow-lg transition-all duration-300 transform hover:bg-green-600 hover:scale-110 hover:shadow-xl group">
            <i class="text-2xl fab fa-whatsapp"></i>

            <!-- رسالة التوضيح من الجانب -->
            <div
                class="absolute top-1/2 right-full px-3 py-2 mr-3 text-sm text-white whitespace-nowrap bg-gray-800 rounded-lg shadow-lg opacity-0 transition-all duration-300 transform -translate-y-1/2 pointer-events-none rtl:left-full rtl:right-auto rtl:ml-3 rtl:mr-0 group-hover:opacity-100">
                تواصل معنا عبر واتساب
                <!-- السهم الجانبي -->
                <div
                    class="absolute top-1/2 left-full w-0 h-0 border-t-4 border-b-4 border-l-4 border-transparent transform -translate-y-1/2 rtl:right-full rtl:left-auto rtl:border-r-4 rtl:border-l-0 border-l-gray-800 rtl:border-r-gray-800">
                </div>
            </div>
        </a>
    </div>

    <!-- Direction Toggle Button -->
    <div class="fixed bottom-4 z-50 start-4">
        <button id="direction-toggle"
            class="px-4 py-2 text-white rounded-lg shadow-lg transition-colors duration-300 bg-primary-600 hover:bg-primary-700">
            <i class="fas fa-exchange-alt me-2"></i>
            <span>RTL</span>
        </button>
    </div>

    <!-- ================= Login Modal ================= -->
    <div id="loginModal"
        class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300"
        aria-hidden="true">
        <!-- Wrapper -->
        <div
            class="modal-panel relative w-[400px] max-w-[90%] scale-95 opacity-0 rounded-sm bg-white shadow-2xl transition-all duration-300">
            <!-- Close Button -->
            <button type="button" class="absolute right-4 top-4 text-gray-400 hover:text-gray-700 focus:outline-none"
                data-close id="btn-close-loginModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Body -->
            <div class="px-8 py-16">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-center mb-6">Log in</h2>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" class="w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.90727 0C0.860697 0 0 0.870415 0 1.9288V12.5408C0 13.5991 0.860697 14.4696 1.90727 14.4696H14.7854C15.832 14.4696 16.6927 13.5991 16.6927 12.5408V1.9288C16.6927 0.870415 15.832 0 14.7854 0H1.90727ZM1.90727 1.2058H14.7854C14.8978 1.2058 15.001 1.23211 15.094 1.27527C13.1127 3.13681 10.9999 5.09714 8.9495 7.00869C8.63238 7.3044 8.09723 7.30335 7.77463 7.00634L1.56727 1.29176C1.66783 1.23865 1.78182 1.2058 1.90727 1.2058ZM15.5004 2.54112V12.5408C15.5004 12.952 15.1921 13.2638 14.7854 13.2638H1.90727C1.50063 13.2638 1.19233 12.952 1.19233 12.5408V2.57645L6.97236 7.89773C7.75662 8.61978 8.97542 8.6258 9.75875 7.89538C11.6519 6.10496 13.6085 4.32728 15.5004 2.54112Z"
                                    fill="#AAAAAA" />
                            </svg>
                        </span>
                        <input type="email" id="email" placeholder="name@email.com"
                            class="w-full h-11 pl-10 pr-3 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg width="16" height="21" class="w-5 h-5" viewBox="0 0 16 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.33155 0.000483697C8.89273 0.0817139 9.43757 0.253589 9.94377 0.509167C11.4624 1.2546 12.4738 2.74746 12.6031 4.43398C12.669 5.52464 12.6793 6.61778 12.6343 7.70944C12.6353 8.09974 12.3197 8.41673 11.9294 8.41722C11.9136 8.41722 11.8978 8.41673 11.8819 8.41574C11.4862 8.40237 11.1766 8.07051 11.1905 7.67476C11.1905 7.67328 11.1905 7.67179 11.1905 7.6708C11.1855 6.75548 11.1905 5.84015 11.1905 4.92334C11.2043 3.007 9.66193 1.44183 7.7451 1.42796C6.00311 1.41558 4.52165 2.69595 4.28291 4.4216C4.24131 4.98674 4.23041 5.55336 4.24924 6.11949C4.24032 6.64551 4.24924 7.17152 4.24924 7.69704C4.28391 8.09725 3.98772 8.45041 3.58751 8.48508C3.1873 8.51975 2.83415 8.22356 2.79948 7.82335C2.79601 7.78125 2.79601 7.73914 2.79948 7.69704C2.79948 6.76834 2.79403 5.83966 2.79948 4.91046C2.797 2.45771 4.59744 0.375928 7.02493 0.0242604C7.05168 0.0183168 7.07793 0.00990612 7.10368 0H8.33303L8.33155 0.000483697Z"
                                    fill="#AAAAAA" />
                                <path
                                    d="M7.70312 20.9991C5.8591 20.9991 4.01507 20.9991 2.17105 20.9991C1.00906 21.0348 0.0387763 20.1214 0.00311428 18.9599C0.00212367 18.9208 0.00160974 18.8812 0.00260036 18.842C-0.000866785 16.5334 -0.000866785 14.2248 0.00260036 11.9162C-0.0132494 10.8899 0.712371 10.0013 1.72081 9.81161C1.94271 9.77249 2.16758 9.75514 2.39294 9.7596C5.94478 9.75663 9.49662 9.75515 13.0485 9.75564C13.8201 9.69422 14.5695 10.034 15.0322 10.6551C15.2902 11.0043 15.4299 11.4268 15.4309 11.8607C15.4348 14.2099 15.4388 16.5602 15.4309 18.9094C15.4269 20.0669 14.4853 21.0021 13.3283 20.9981C13.3258 20.9981 13.3229 20.9981 13.3204 20.9981C11.4491 20.9981 9.57686 20.9981 7.7056 20.9981H7.70312V20.9991ZM7.71947 19.5751H13.0668C13.1896 19.5786 13.3125 19.5686 13.4333 19.5459C13.7493 19.4934 13.9806 19.219 13.9782 18.8985C13.9826 18.6934 13.9782 18.4889 13.9782 18.2838C13.9782 16.1808 13.9782 14.0772 13.9782 11.9736C14.0014 11.724 13.8915 11.4803 13.6894 11.3322C13.5185 11.2297 13.3189 11.1841 13.1203 11.2029C9.48771 11.2108 5.85465 11.1886 2.22207 11.2282C1.68615 11.2336 1.44743 11.4684 1.44644 12.0029C1.44644 13.3753 1.44644 14.7478 1.44644 16.1208C1.44644 17.0361 1.44644 17.9515 1.44644 18.8683C1.42316 19.213 1.67675 19.5147 2.02049 19.5513C2.13491 19.5696 2.2508 19.5781 2.3667 19.5766C4.14931 19.5766 5.93141 19.5766 7.71352 19.5766L7.71947 19.5751Z"
                                    fill="#AAAAAA" />
                                <path
                                    d="M8.428 15.4098C8.428 15.6451 8.43295 15.8873 8.428 16.125C8.42304 16.497 8.12784 16.8006 7.75587 16.816C7.37646 16.8487 7.04212 16.5673 7.00943 16.1879C6.9604 15.6723 6.9604 15.1532 7.00943 14.6376C7.04807 14.3127 7.30714 14.0586 7.63256 14.0264C7.93222 13.9952 8.21998 14.1512 8.35768 14.4187C8.40721 14.5158 8.43196 14.6237 8.42899 14.7327C8.42899 14.9571 8.42899 15.1824 8.42899 15.4068L8.428 15.4098Z"
                                    fill="#AAAAAA" />
                            </svg>
                        </span>
                        <input type="password" id="password"
                            class="w-full h-11 pl-10 pr-10 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                            placeholder="Password">
                        <button type="button" data-toggle-password="#password"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <svg class="eye w-5 h-5 hidden" width="17" height="17" viewBox="0 0 17 17"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.22999 14.1898C6.27626 14.1898 4.32252 13.4468 2.83658 11.9609L0.73609 9.86038C-0.245363 8.87893 -0.245363 7.27375 0.73609 6.29229L2.83658 4.1918C5.80846 1.21992 10.6515 1.21992 13.6234 4.1918L15.7239 6.29229C16.7053 7.27375 16.7053 8.87893 15.7239 9.86038L13.6234 11.9609C12.1375 13.4468 10.1837 14.1898 8.22999 14.1898ZM8.22999 3.32959C6.62481 3.32959 5.0288 3.93497 3.80886 5.15491L1.70837 7.2554C1.25892 7.70485 1.25892 8.42948 1.70837 8.87893L3.80886 10.9794C6.24874 13.4193 10.2112 13.4193 12.6511 10.9794L14.7516 8.87893C15.2011 8.42948 15.2011 7.70485 14.7516 7.2554L12.6511 5.15491C11.4312 3.93497 9.83517 3.32959 8.22999 3.32959Z"
                                    fill="black" />
                                <path
                                    d="M8.22933 11.5016C6.3398 11.5016 4.79883 9.96065 4.79883 8.07112C4.79883 6.1816 6.3398 4.64062 8.22933 4.64062C10.1189 4.64062 11.6598 6.1816 11.6598 8.07112C11.6598 9.96065 10.1189 11.5016 8.22933 11.5016ZM8.22933 6.02567C7.10112 6.02567 6.1747 6.94291 6.1747 8.0803C6.1747 9.21768 7.09194 10.1349 8.22933 10.1349C9.36671 10.1349 10.284 9.21768 10.284 8.0803C10.284 6.94291 9.36671 6.02567 8.22933 6.02567Z"
                                    fill="black" />
                            </svg>
                            <svg class="eye-off w-5 h-5" width="17" height="17" viewBox="0 0 17 17"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.22999 14.1898C6.27626 14.1898 4.32252 13.4468 2.83658 11.9609L0.73609 9.86038C-0.245363 8.87893 -0.245363 7.27375 0.73609 6.29229L2.83658 4.1918C5.80846 1.21992 10.6515 1.21992 13.6234 4.1918L15.7239 6.29229C16.7053 7.27375 16.7053 8.87893 15.7239 9.86038L13.6234 11.9609C12.1375 13.4468 10.1837 14.1898 8.22999 14.1898ZM8.22999 3.32959C6.62481 3.32959 5.0288 3.93497 3.80886 5.15491L1.70837 7.2554C1.25892 7.70485 1.25892 8.42948 1.70837 8.87893L3.80886 10.9794C6.24874 13.4193 10.2112 13.4193 12.6511 10.9794L14.7516 8.87893C15.2011 8.42948 15.2011 7.70485 14.7516 7.2554L12.6511 5.15491C11.4312 3.93497 9.83517 3.32959 8.22999 3.32959Z"
                                    fill="black" />
                                <path
                                    d="M8.22933 11.5016C6.3398 11.5016 4.79883 9.96065 4.79883 8.07112C4.79883 6.1816 6.3398 4.64062 8.22933 4.64062C10.1189 4.64062 11.6598 6.1816 11.6598 8.07112C11.6598 9.96065 10.1189 11.5016 8.22933 11.5016ZM8.22933 6.02567C7.10112 6.02567 6.1747 6.94291 6.1747 8.0803C6.1747 9.21768 7.09194 10.1349 8.22933 10.1349C9.36671 10.1349 10.284 9.21768 10.284 8.0803C10.284 6.94291 9.36671 6.02567 8.22933 6.02567Z"
                                    fill="black" />
                                <line x1="14.1086" y1="1.04768" x2="3.25011" y2="15.9781" stroke="black"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-5">
                    <button type="button" class="text-sm text-primary hover:underline"
                        data-open="#forgotModal">Forgot Password ?</button>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                        Remember Me
                    </label>
                </div>

                <!-- Login Button -->
                <button
                    class="w-full h-11 rounded-lg bg-primary text-white font-semibold hover:brightness-95 transition-all duration-150">
                    login
                </button>

                <!-- Separator -->
                <div class="flex items-center my-4">
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <span class="px-3 text-sm font-bold text-black">Or</span>
                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                <!-- Google Button -->
                <button
                    class="relative w-full h-11 rounded-lg border border-gray-300 hover:bg-primary hover:text-white flex items-center justify-center font-medium text-gray-700 transition-all duration-350 courser-p">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google"
                        class="w-5 h-5 absolute left-4">
                    Sign in with Google
                </button>

                <!-- Sign Up -->
                <p class="mt-5 text-center text-sm text-gray-600">
                    New To Jinn? <a href="./signup.html" class="text-primary hover:underline">Sign Up</a>
                </p>
            </div>
        </div>
    </div>

    <!-- ========== Forgot Password Modal (Placeholder) ========== -->
    <div id="forgotModal"
        class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300"
        aria-hidden="true">
        <!-- Wrapper -->
        <div
            class="modal-panel relative w-[400px] max-w-[90%] scale-95 opacity-0 rounded-sm bg-white shadow-2xl transition-all duration-300">
            <!-- Close Button -->
            <button type="button" class="absolute right-4 top-4 text-gray-400 hover:text-gray-700 focus:outline-none"
                data-close id="btn-close-loginModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Body -->
            <div class="px-8 py-16">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-center mb-2">Forgot password</h2>
                <p class="text-sm text-center font-light mb-4">
                    Enter the email address you use on Jinn. We'll send you a link to reset your password.
                </p>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" class="w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.90727 0C0.860697 0 0 0.870415 0 1.9288V12.5408C0 13.5991 0.860697 14.4696 1.90727 14.4696H14.7854C15.832 14.4696 16.6927 13.5991 16.6927 12.5408V1.9288C16.6927 0.870415 15.832 0 14.7854 0H1.90727ZM1.90727 1.2058H14.7854C14.8978 1.2058 15.001 1.23211 15.094 1.27527C13.1127 3.13681 10.9999 5.09714 8.9495 7.00869C8.63238 7.3044 8.09723 7.30335 7.77463 7.00634L1.56727 1.29176C1.66783 1.23865 1.78182 1.2058 1.90727 1.2058ZM15.5004 2.54112V12.5408C15.5004 12.952 15.1921 13.2638 14.7854 13.2638H1.90727C1.50063 13.2638 1.19233 12.952 1.19233 12.5408V2.57645L6.97236 7.89773C7.75662 8.61978 8.97542 8.6258 9.75875 7.89538C11.6519 6.10496 13.6085 4.32728 15.5004 2.54112Z"
                                    fill="#AAAAAA" />
                            </svg>
                        </span>
                        <input type="email" id="email" placeholder="name@email.com"
                            class="w-full h-11 pl-10 pr-3 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>

                <!-- Login Button -->
                <button
                    class="w-full h-11 rounded-lg bg-primary text-white font-semibold hover:brightness-95 transition-all duration-150">
                    login
                </button>

                <!-- Separator -->
                <div class="flex items-center my-4">
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <span class="px-3 text-sm font-bold text-black">Or</span>
                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                <!-- Google Button -->
                <button
                    class="relative w-full h-11 rounded-lg border border-gray-300 hover:bg-primary hover:text-white flex items-center justify-center font-medium text-gray-700 transition-all duration-350 courser-p">
                    eMAIL ME A LOGIN LINK
                </button>

                <!-- Sign Up -->
                <p class="mt-5 text-center text-sm text-gray-600">
                    Back to
                    <button type="button" class="text-primary hover:underline" data-open="#loginModal">Log
                        in</button>
                </p>
            </div>
        </div>
    </div>
    <script src="{{ asset('front/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front/assets/js/main.js') }}"></script>
    <script src="{{ asset('front/assets/js/index.js') }}"></script>
</body>

</html>
