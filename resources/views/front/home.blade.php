<x-front-layout>


    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] min-h-screen-hero shadow-sm">
        <!-- Background Shape 1 - Bottom Left -->
        <div class="absolute bottom-[-7px] left-0 pointer-events-none animate-float" id="shape1">
            <img src="{{ asset('front/assets/imgs/hero/shape2.png') }}" alt="">
        </div>

        <!-- Background Shape 2 - Top Right -->
        <div class="absolute -top-[10px] right-0 w-auto pointer-none:nter-events-none h-shape1 animate-float-reverse"
            id="shape2">
            <img src="{{ asset('front/assets/imgs/hero/shape1.png') }}" alt="" class="h-full">

        </div>

        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <div class="hero-main-container grid gap-8 lg:gap-12 items-center min-h-[600px]">

                <!-- Text Content Section -->
                <div class="order-2 lg:order-1">
                    <div class="hero-text-container relative h-[330px] overflow-hidden">

                        <!-- Slide 1 -->
                        <div class="flex absolute inset-0 justify-center items-center opacity-100 transition-opacity transition-transform duration-700 ease-out translate-y-0 hero-slide lg:justify-start rtl:lg:justify-end"
                            data-slide="0">
                            <div
                                class="slide-inner w-[75%] lg:w-full mx-auto lg:mx-0 text-center lg:text-left rtl:lg:text-right">
                                <h1 class="mb-6 text-[25px] font-black leading-[1.5] text-gray-900 lg:text-5xl">
                                    Learn better, faster, and smarter with us.
                                </h1>
                                <p class="max-w-xl leading-relaxed text-gray-600 text-md md:mb-8 rtl:lg:text-right">
                                    Learn with speed, think with clarity, grow with confidence.
                                </p>
                                <!-- Dots Indicators -->
                                <div class="flex z-20 gap-2 justify-center my-4 lg:hidden dots-container">
                                    <button class="w-2 h-2 rounded-full transition-all duration-300 bg-primary hero-dot"
                                        data-slide="0"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="1"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="2"></button>
                                </div>
                                <div class="hero-cta">
                                    <button
                                        class="overflow-hidden relative px-10 py-2 text-lg font-semibold text-white rounded-lg transition-all duration-300 transform md:px-12 md:py-4 bg-primary group hover:bg-primary-700 hover:ml-2 hover:rtl:mr-2 hover:shadow-xl">
                                        <span class="relative z-10">Get Started</span>
                                        <div
                                            class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="flex hidden absolute inset-0 justify-center items-center opacity-0 transition-opacity transition-transform duration-700 ease-out translate-y-16 hero-slide lg:justify-start rtl:lg:justify-end"
                            data-slide="1">
                            <div
                                class="slide-inner w-[75%] lg:w-full mx-auto lg:mx-0 text-center lg:text-left rtl:lg:text-right">
                                <h1 class="mb-6 text-[25px] font-black leading-[1.5] text-gray-900 lg:text-5xl">
                                    Learn better, faster, and smarter with us.
                                </h1>
                                <p class="max-w-xl leading-relaxed text-gray-600 text-md md:mb-8">
                                    Learn with speed, think with clarity, grow with confidence.
                                </p>
                                <!-- Dots Indicators -->
                                <div class="flex z-20 gap-2 justify-center my-4 lg:hidden dots-container">
                                    <button class="w-2 h-2 rounded-full transition-all duration-300 bg-primary hero-dot"
                                        data-slide="0"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="1"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="2"></button>
                                </div>
                                <div class="hero-cta">
                                    <button
                                        class="overflow-hidden relative px-10 py-2 text-lg font-semibold text-white bg-green-600 rounded-lg transition-all duration-300 transform md:px-12 md:py-4 group hover:bg-green-700 hover:ml-2 hover:rtl:mr-2 hover:shadow-xl">
                                        <span class="relative z-10">Find a Teacher</span>
                                        <div
                                            class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="flex hidden absolute inset-0 justify-center items-center opacity-0 transition-opacity transition-transform duration-700 ease-out translate-y-16 hero-slide lg:justify-start rtl:lg:justify-end"
                            data-slide="2">
                            <div
                                class="slide-inner w-[75%] lg:w-full mx-auto lg:mx-0 text-center lg:text-left rtl:lg:text-right">
                                <h1 class="mb-6 text-[25px] font-black leading-[1.5] text-gray-900 lg:text-5xl">
                                    Learn better, faster, and smarter with us.
                                </h1>
                                <p class="max-w-xl leading-relaxed text-gray-600 text-md md:mb-8">
                                    Learn with speed, think with clarity, grow with confidence.
                                </p>
                                <!-- Dots Indicators -->
                                <div class="flex z-20 gap-2 justify-center my-4 lg:hidden dots-container">
                                    <button class="w-2 h-2 rounded-full transition-all duration-300 bg-primary hero-dot"
                                        data-slide="0"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="1"></button>
                                    <button
                                        class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                                        data-slide="2"></button>
                                </div>
                                <div class="hero-cta">
                                    <button
                                        class="overflow-hidden relative px-10 py-2 text-lg font-semibold text-white bg-purple-600 rounded-lg transition-all duration-300 transform md:px-12 md:py-4 group hover:bg-purple-700 hover:ml-2 hover:rtl:mr-2 hover:shadow-xl">
                                        <span class="relative z-10">Join Now</span>
                                        <div
                                            class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Image Section -->
                <div class="order-1 lg:order-2 lg:aspect-[1/1] relative">
                    <div class="flex overflow-hidden justify-center items-center hero-image-container md:items-end">

                        <!-- Slide 1 Image -->
                        <div class="flex justify-center items-center h-full opacity-100 transition-all duration-500 ease-out scale-100 translate-x-0 hero-image"
                            data-slide="0">
                            <img src="{{ asset('front/assets/imgs/hero/girl1.png') }}" alt="طالبة تحمل لابتوب"
                                class="object-contain w-full max-w-full h-full">
                        </div>

                        <!-- Slide 2 Image -->
                        <div class="flex hidden justify-center items-center h-full opacity-0 transition-all duration-500 ease-out scale-95 translate-x-0 hero-image"
                            data-slide="1">
                            <img src="{{ asset('front/assets/imgs/hero/img2.png') }}" alt="مدرس يشرح"
                                class="object-contain w-full max-w-full h-full">
                        </div>

                        <!-- Slide 3 Image -->
                        <div class="flex hidden justify-center items-center h-full opacity-0 transition-all duration-500 ease-out scale-95 translate-x-0 hero-image"
                            data-slide="2">
                            <img src="{{ asset('front/assets/imgs/hero/img3.png') }}" alt="طالب يدرس"
                                class="object-contain w-full max-w-full h-full">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button
            class="absolute left-4 top-1/2 z-20 p-3 bg-[#EFF2F8] border border-[#1B449C12] rounded-full shadow-lg transition-all duration-300 transform -translate-y-1/2 hero-nav-btn hover:shadow-xl group"
            id="hero-prev">
            <svg class="w-6 h-6 text-black transition-colors duration-300 group-hover:text-white" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <div
                class="absolute inset-0 rounded-full transition-transform duration-300 scale-0 bg-primary group-hover:scale-100 -z-10">
            </div>
        </button>

        <button
            class="absolute right-4 top-1/2 z-20 p-3 bg-[#EFF2F8] border border-[#1B449C12] rounded-full shadow-lg transition-all duration-300 transform -translate-y-1/2 hero-nav-btn hover:shadow-xl group"
            id="hero-next">
            <svg class="w-6 h-6 text-black transition-colors duration-300 group-hover:text-white" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <div
                class="absolute inset-0 rounded-full transition-transform duration-300 scale-0 bg-primary group-hover:scale-100 -z-10">
            </div>
        </button>


        <!-- Dots Indicators -->
        <div
            class="hidden absolute left-1/2 z-20 gap-2 transform -translate-x-1/2 lg:flex bottom-37 md:bottom-8 dots-container">
            <button class="w-2 h-2 rounded-full transition-all duration-300 bg-primary hero-dot"
                data-slide="0"></button>
            <button class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                data-slide="1"></button>
            <button class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-300 hero-dot"
                data-slide="2"></button>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-[#fcfcfc]">
        <div class="container mx-auto">

            <!-- Section Title -->
            <div class="mb-12 text-center">
                <h2 class="mb-4 h-section">Our Numbers</h2>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 gap-6 px-4 md:px-8 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Educational Services - Blue -->
                <div
                    class="p-8 bg-[#E4EDF9] rounded-md transition-all duration-300 cursor-pointer stats-card group hover:bg-primary-200 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="flex-none mr-4 rtl:mr-0 rtl:ml-4">
                            <!-- <i class="text-4xl transition-colors duration-300 text-primary fas fa-cog group-hover:text-primary-700"></i> -->
                            <img src="{{ asset('front/assets/imgs/our_number/approve.png') }}"
                                alt="Educational Services">
                        </div>
                        <div class="flex flex-1 justify-start text-center md:text-left md:rtl:text-right">
                            <div class="flex flex-col justify-center">
                                <span class="mb-2 text-3xl font-black text-center text-[#1B449C] transition-colors duration-300 lg:text-left lg:rtl:text-right translate-x-[-28px]">
                                    {{ $stats['services'] }} 
                                </span>
                                <span
                                    class="text-[16px] md:text-[12px] font-medium tracking-wide text-black uppercase">EDUCATIONAL
                                    SERVICES</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Count - Purple -->
                <div
                    class="p-8 bg-[#F0ECFA] rounded-md transition-all duration-300 cursor-pointer stats-card group hover:bg-purple-200 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 rtl:mr-0 rtl:ml-4">
                            <!-- <i class="text-4xl text-purple-600 transition-colors duration-300 fas fa-graduation-cap group-hover:text-purple-700"></i> -->
                            <img src="{{ asset('front/assets/imgs/our_number/student.png') }}" alt="Student">
                        </div>
                        <div class="flex flex-1 justify-start text-center md:text-left md:rtl:text-right">
                            <div class="flex flex-col justify-center">

                                <span class="mb-2 text-3xl font-black text-center text-[#7242B8] transition-colors duration-300 lg:text-left lg:rtl:text-right">
                                    {{ $stats['students'] }}

                              
                                </span>
                                <span
                                    class="text-lg font-medium tracking-wide text-black uppercase md:text-sm">STUDENTS
                                    COUNT</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tutors Count - Dark Gray/Slate -->
                <div
                    class="p-8 rounded-md transition-all duration-300 cursor-pointer stats-card group bg-[#E7EBEE] hover:bg-slate-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 rtl:mr-0 rtl:ml-4">
                            <!-- <i class="text-4xl transition-colors duration-300 fas fa-user-tie text-slate-700 group-hover:text-slate-800"></i> -->
                            <img src="{{ asset('front/assets/imgs/our_number/teacher.png') }}" alt="Tutor">
                        </div>
                        <div class="flex flex-1 justify-start text-center md:text-left md:rtl:text-right">
                            <div class="flex flex-col justify-center">

                                <span class="mb-2 text-3xl font-black text-center transition-colors duration-300 lg:text-left lg:rtl:text-right text-[#1C3C55]">
                                    {{ $stats['tutors'] }}

                                </span>
                                <span class="text-lg font-medium tracking-wide text-black uppercase md:text-sm">TUTORS
                                    COUNT</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Count - Yellow/Amber -->
                <div
                    class="p-8 bg-[#FEF6E1] rounded-md transition-all duration-300 cursor-pointer stats-card group hover:bg-amber-200 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 rtl:mr-0 rtl:ml-4 md:w-auto w-[60px]">
                            <!-- <i class="p-2 text-4xl text-amber-600 rounded transition-colors duration-300 fas fa-laptop group-hover:text-amber-700"></i> -->
                            <img src="{{ asset('front/assets/imgs/our_number/monitor.png') }}" alt="Course">
                        </div>
                        <div class="flex flex-1 justify-start text-center md:text-left md:rtl:text-right">
                            <div class="flex flex-col justify-center">

                                <span class="text-center lg:text-left lg:rtl:text-right mb-2 text-3xl font-black text-[#EAC634] transition-colors duration-300">
                                    {{ $stats['courses'] }}


                           
                                </span>
                                <span class="text-lg font-medium tracking-wide text-black uppercase md:text-sm">COURSES
                                    COUNT</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Recent Courses Section -->
    <section class="px-4 py-16">
        <div class="container mx-auto">

            <!-- Section Title -->
            <h2 class="mb-6 text-center h-section">Recent courses</h2>

            <!-- Category Filter -->
            <div class="relative mb-6 md:mb-12 mx-[-30px]" dir="ltr">
                <!-- سهم يسار -->
                <div id="left-arrow"
                    class="flex absolute inset-y-0 left-0 items-center opacity-0 transition-opacity pointer-events-none ps-2 text-primary bg-[#fffffff0]">
                    <!-- Heroicons: chevron-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

                <div id="filter-container"
                    class="flex md:justify-center overflow-x-auto scroll-smooth relative flex-nowrap gap-2 px-2 whitespace-nowrap md:px-8 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">


                    <a href="{{ url()->current() }}"
                        class="px-2 py-3 font-bold transition-all duration-300 text-primary lg:px-5 text-md category-btn {{ empty($categoryId) ? 'active' : 'text-black' }} hover:text-primary hover:scale-105 hover:font-bold">
                        All categories
                    </a>


                    @foreach ($categories as $cat)
                    @php
                    $catTitle = $cat->langs->first()->title ?? $cat->name ?? 'Category';
                    $isActive = (int)$categoryId === (int)$cat->id;
                    @endphp
                    <a href="{{ url()->current() }}?category_id={{ $cat->id }}"
                        class="px-2 py-3 font-medium text-black transition-all duration-300 lg:px-5 text-md category-btn hover:text-primary hover:scale-105 hover:font-bold {{ $isActive ? 'text-primary active' : '' }}"
                        data-type="{{ $catTitle }}">
                        {{ $catTitle }}
                    </a>
                    @endforeach

                </div>

                <!-- سهم يمين -->
                <div id="right-arrow"
                    class="flex absolute inset-y-0 right-0 items-center opacity-0 transition-opacity pointer-events-none pe-2 text-primary bg-[#fffffff0]">
                    <!-- Heroicons: chevron-right -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>


            <!-- Courses Grid -->
            <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="coursesGrid">


                @foreach ($courses as $course)
                @php
                $langRow = $course->langs->first();
                $title   = $course->name ?? 'Course';
                $about = $langRow->about ?? '';

                $lessons = (int)($course->lessons ?? 0);
                $classLength = (int)($course->class_length ?? 0);
                $totalMinutes = $lessons * $classLength;
                if ($totalMinutes >= 60) {
                $h = intdiv($totalMinutes, 60);
                $m = $totalMinutes % 60;
                $timeLabel = $m ? "{$h} total hours" : "{$h} total hours";
                } else {
                $timeLabel = "{$totalMinutes} minutes";
                }
                $img = $course->imageInfo?->path ? asset($course->imageInfo->path)
                : 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=400&h=250&fit=crop';
                $showUrl = route('courses.show', $course->slug ?? $course->id);
                @endphp
                <!-- Course Card 1 - Design (Original - No Changes) -->
                <div class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Design">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <a href="{{ $showUrl }}">
                            <img src="{{ $img }}" alt="{{ $title }}" class="object-cover w-full h-full">
                        </a>
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">{{ $title }}</h3>

                        <p class="mb-4 text-[13px] text-black">
                            {{ \Illuminate\Support\Str::limit(strip_tags($about), 110) }}
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center h-[45px] transition-all duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">{{ $timeLabel }}</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">Free</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Course Card 2 - AI -->


                <!-- Course Card 3 - Marketing -->


                <!-- Course Card 4 - Business -->


                <!-- Hidden Course Cards for Load More -->
                <!-- Course Card 5 - Programming -->


                <!-- Course Card 6 - Design -->


                <!-- Course Card 7 - AI -->


                <!-- Course Card 8 - Business -->


            </div>

            <!-- Load More Button -->
            <div class="text-center">
                <button id="loadMoreBtn"
                    class="overflow-hidden relative px-9 py-4 text-[15px] text-white rounded-lg transition-all duration-300 transform bg-primary group hover:bg-primary-700 hover:-translate-y-2 hover:shadow-xl">
                    <span class="relative z-10">Load More</span>
                    <div
                        class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                    </div>
                </button>
            </div>

        </div>
    </section>

    <!-- Upgrade Skills Section -->
    <section class="px-2 py-5 bg-white md:py-16 md:px-20 lg:px-4">
        <div class="container mx-auto">

            <!-- Main Container with Shadow -->
            <div class="overflow-hidden bg-white rounded-md shadow-lg">

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-5 min-h-[400px]">

                    <!-- Text Section with Grid Background -->
                    <div
                        class="flex relative order-2 col-span-3 items-center p-3 md:p-8 lg:p-12 lg:order-1 rtl:order-3">
                        <!-- Grid Pattern Background -->
                        <!-- Grid Lines Background -->
                        <div class="absolute inset-0 pattern-skills-section"></div>

                        <!-- Text Content -->
                        <div class="relative z-10 max-w-lg">
                            <h2 class="mb-6 text-3xl font-bold leading-tight text-gray-900 lg:text-4xl">
                                Upgrade Your Skills with<br>
                                Free Courses
                            </h2>

                            <p class="mb-8 text-lg leading-relaxed text-black">
                                Learn anytime, anywhere with expert-led courses designed for you.
                            </p>

                            <button
                                class="overflow-hidden relative px-8 py-4 text-lg text-white rounded-lg transition-all duration-300 transform bg-primary group hover:bg-primary-700 hover:ml-2 hover:rtl:mr-2 hover:shadow-xl">
                                <span class="relative z-10">Explore 400+ Free Courses</span>
                                <div
                                    class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="overflow-hidden relative order-1 col-span-2 lg:order-2 rtl:order-1">
                        <img src="{{ asset('front/assets/imgs/Skills/Rectangle 19041.png') }}"
                            alt="Student learning online"
                            class="w-full h-full object-cover min-h-[200px] md:min-h-[300px] lg:min-h-[400px]">

                        <!-- Gradient Overlay for better text readability on mobile -->
                        <div class="absolute inset-0 bg-gradient-to-t to-transparent from-black/20 lg:hidden"></div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Find a Tutor Section -->
    <section class="px-0 py-16 bg-white md:px-20 lg:px-4">
        <div class="mx-auto lg:container">
            <!-- Filter Form -->
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-lg md:p-8">

                <!-- Section Title -->
                <div class="flex items-center mb-8">
                    <i class="mr-3 text-xl text-primary fas fa-search rtl:mr-0 rtl:ml-3"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Find a Tutor</h2>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-6 md:grid-cols-3 lg:grid-cols-5">

                    <!-- What to Learn -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">What to
                            Learn?</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Subject</option>
                                <option>Mathematics</option>
                                <option>English</option>
                                <option>Science</option>
                                <option>History</option>
                                <option>Programming</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Price
                            Range</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>5 USD - 50 USD</option>
                                <option>Under 10 USD</option>
                                <option>10 USD - 25 USD</option>
                                <option>25 USD - 50 USD</option>
                                <option>50 USD - 100 USD</option>
                                <option>100 USD+</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Native Language -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Native
                            Language</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Native Language</option>
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                                <option>Arabic</option>
                                <option>Chinese</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Availability Time -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Availability
                            Time</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Any Time</option>
                                <option>Morning (6AM - 12PM)</option>
                                <option>Afternoon (12PM - 6PM)</option>
                                <option>Evening (6PM - 10PM)</option>
                                <option>Night (10PM - 6AM)</option>
                                <option>Weekends Only</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>
                    <!-- Specializations -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Specializations</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Specializations</option>
                                <option>Test Preparation</option>
                                <option>Academic Support</option>
                                <option>Language Learning</option>
                                <option>Professional Development</option>
                                <option>Creative Arts</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Country</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Country</option>
                                <option>United States</option>
                                <option>United Kingdom</option>
                                <option>Canada</option>
                                <option>Australia</option>
                                <option>Germany</option>
                                <option>France</option>
                                <option>Other</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Also Speaks -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Also Speaks</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Also Speaks</option>
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                                <option>Arabic</option>
                                <option>Chinese</option>
                                <option>Japanese</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Sort By</label>
                        <div class="relative">
                            <select
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option>Sort By</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Rating: High to Low</option>
                                <option>Most Popular</option>
                                <option>Newest First</option>
                                <option>Experience</option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Tutor full name</label>
                        <div class="relative">
                            <input type="text"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                        </div>
                    </div>

                </div>

                <!-- Search Button -->
                <div class="flex justify-center mt-8">
                    <button id="search-button"
                        class="overflow-hidden relative px-8 py-4 text-lg text-white rounded-lg transition-all duration-300 transform bg-primary group hover:bg-primary-700 hover:-translate-y-2 hover:shadow-xl">
                        <i class="mr-2 fas fa-search rtl:mr-0 rtl:ml-2"></i>
                        <span class="relative z-10">Search Tutors</span>
                        <div
                            class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                        </div>
                    </button>
                </div>

            </div>

        </div>
    </section>

    <!-- Popular Tutors Section -->
    <section class="overflow-hidden relative px-0 py-16 bg-white">
        <h2 class="mb-12 text-center h-section">Popular Tutors</h2>
        <div class="bg-[#1B449C08] overflow-hidden relative px-4">
            <!-- Background Shape 1 - Bottom Left -->
            <div class="absolute -top-2 left-0 pointer-events-none animate-float rotate-x-[180deg]" id="shape1">
                <img src="{{ asset('front/assets/imgs/hero/shape2.png') }}" alt="">
            </div>

            <!-- Background Shape 2 - Top Right -->
            <div class="absolute bottom-[-200px] right-[95px] w-auto pointer-events-none animate-float-reverse rotate-[90deg]"
                id="shape2">
                <img src="{{ asset('front/assets/imgs/hero/shape1.png') }}" alt="" class="h-full">
            </div>
            <div class="container relative z-10 py-16 mx-auto">
                <!-- Section Header -->
                <div class="flex justify-end items-center pr-3 mb-2">
                    <a href="#"
                        class="text-lg font-medium underline transition-all duration-300 text-primary-700 hover:text-primary hover:scale-105">
                        Load More
                    </a>
                </div>

                <!-- Slider Container -->
                <div class="relative">
                    <!-- Custom Navigation Buttons -->
                    <button
                        class="flex absolute left-[-20px] top-1/2 z-20 justify-center items-center w-12 h-12 rounded-full shadow-lg transition-all duration-300 transform -translate-y-1/2 swiper-button-prev-custom bg-white border border-primary text-primary hover:bg-primary hover:text-white hover:cursor-pointer hover:scale-110">
                        <i class="text-lg fas fa-chevron-left"></i>
                    </button>
                    <button
                        class="flex absolute right-[-20px] top-1/2 z-20 justify-center items-center w-12 h-12 rounded-full shadow-lg transition-all duration-300 transform -translate-y-1/2 swiper-button-next-custom bg-white border border-primary text-primary hover:bg-primary hover:text-white hover:cursor-pointer hover:scale-110">
                        <i class="text-lg fas fa-chevron-right"></i>
                    </button>

                    <!-- Swiper -->
                    <!-- Swiper -->
                    <div class="swiper tutors-swiper">
                        <div class="swiper-wrapper">
                            <!-- Tutor Cards -->
                            @forelse($tutors as $tutor)
                                @php
                                    // الصورة
                                    $avatar = $tutor->avatar
                                        ? (filter_var($tutor->avatar, FILTER_VALIDATE_URL)
                                            ? $tutor->avatar
                                            : asset($tutor->avatar))
                                        : asset('front/assets/imgs/tutors/1.jpg');

                                    // الدولة
                                    $country = $tutor->abouts?->country?->name ?? '—';

                                    // التخصص (أول وصف)
                                    $spec = optional($tutor->descriptions->first()?->specialization)->name ?? '—';

                                    // تقييم "ستاتيك" مؤقتًا
                                    $avg = 4.0;
                                    $maxStars = 5;
                                    $fullStars = (int) floor($avg);
                                @endphp

                                <div class="swiper-slide">
                                    <div
                                        class="px-7 py-4 mx-2 bg-white rounded-xl shadow-lg transition-all duration-300 transform md:p-4 hover:shadow-xl hover:-translate-y-2 hover:scale-105">
                                        <div class="flex justify-center mb-6">
                                            <img src="{{ $avatar }}" alt="{{ $tutor->name }}"
                                                class="object-cover rounded-full w-31 h-31">
                                        </div>

                                        <div class="text-left rtl:text-left">
                                            <h3 class="mb-2 text-xl font-bold text-[#1B449C]">{{ $tutor->name }}</h3>
                                            <p class="mb-4 font-medium text-black">{{ $spec }}</p>

                                            <div class="flex items-center mb-4 text-gray-500">
                                                <i class="text-[#1B449C] mr-2 rtl:ml-2 rtl:mr-0">
                                                    <svg width="14" height="22" viewBox="0 0 14 22"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11.6667 6.38C11.6667 5.50976 11.393 4.65907 10.8802 3.93549C10.3674 3.21192 9.63858 2.64796 8.78586 2.31493C7.93313 1.9819 6.99482 1.89477 6.08958 2.06454C5.18433 2.23432 4.35281 2.65338 3.70017 3.26873C3.04752 3.88408 2.60307 4.66809 2.423 5.5216C2.24294 6.37512 2.33535 7.25981 2.68856 8.06381C3.04177 8.8678 3.63991 9.55499 4.40734 10.0385C5.17477 10.5219 6.07702 10.78 7 10.78C8.23724 10.7787 9.42341 10.3147 10.2983 9.48981C11.1731 8.66493 11.6653 7.54655 11.6667 6.38ZM3.26667 6.38C3.26667 5.68381 3.48562 5.00325 3.89585 4.42439C4.30607 3.84553 4.88914 3.39437 5.57131 3.12794C6.25349 2.86152 7.00414 2.79182 7.72834 2.92764C8.45253 3.06346 9.11775 3.3987 9.63986 3.89098C10.162 4.38327 10.5175 5.01047 10.6616 5.69328C10.8057 6.3761 10.7317 7.08385 10.4492 7.72705C10.1666 8.37024 9.68807 8.91999 9.07413 9.30677C8.46019 9.69356 7.73838 9.9 7 9.9C6.01021 9.89893 5.06128 9.52773 4.36139 8.86783C3.6615 8.20794 3.2678 7.31323 3.26667 6.38ZM8.86895 19.4734C11.3704 16.5803 14 9.2752 14 6.6C14 4.84957 13.2625 3.17084 11.9497 1.9331C10.637 0.695355 8.85651 0 7 0C5.14348 0 3.36301 0.695355 2.05025 1.9331C0.737498 3.17084 0 4.84957 0 6.6C0 9.2752 2.62957 16.5806 5.13105 19.4734C4.01954 19.6165 2.8 19.9412 2.8 20.68C2.8 21.9364 6.29865 22 7 22C7.70135 22 11.2 21.9364 11.2 20.68C11.2 19.9408 9.98046 19.6165 8.86895 19.4734ZM7 0.88C8.60842 0.881712 10.1504 1.4849 11.2878 2.55724C12.4251 3.62958 13.0649 5.08349 13.0667 6.6C13.0667 10.3009 8.70077 19.8 7 19.8C5.29923 19.8 0.933333 10.3009 0.933333 6.6C0.935149 5.08349 1.5749 3.62958 2.71222 2.55724C3.84955 1.4849 5.39158 0.881712 7 0.88ZM7 21.12C5.93405 21.1682 4.8684 21.0192 3.86321 20.6813C4.53795 20.4336 5.25266 20.2965 5.97641 20.2761C6.26171 20.5124 6.62119 20.6543 7 20.68C7.37881 20.6543 7.73829 20.5124 8.02359 20.2761C8.74734 20.2966 9.46203 20.4336 10.1368 20.6813C9.1316 21.0192 8.06595 21.1682 7 21.12Z"
                                                            fill="#1B449C" />
                                                    </svg>
                                                </i>
                                                <span class="text-sm">{{ $country }}</span>
                                            </div>

                                            <div class="flex flex-col items-left rtl:items-right">
                                                <div class="flex items-center mr-3 rtl:ml-3 rtl:mr-0">
                                                    @for ($i = 1; $i <= $maxStars; $i++)
                                                        @if ($i <= $fullStars)
                                                            <i class="mr-1 fas fa-star text-[#FFC700]"></i>
                                                        @else
                                                            <i class="mr-1 fas fa-star text-gray-300"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="mt-2 text-sm text-gray-500">
                                                    {{ number_format($avg, 1) }} / 5
                                                </span>
                                            </div>

                                            <div class="flex gap-2 justify-between my-3">
                                                <button
                                                    class="text-[12px] px-3 py-3 w-full font-medium text-white rounded-lg transition-colors duration-300 bg-[#1B449C] hover:bg-[#1B449C]/90">
                                                    Trial Lesson
                                                </button>
                                                <button
                                                    class="text-[12px] px-3 py-3 w-full font-medium rounded-lg border-1 transition-all duration-300 border-[#1B449C] text-[#1B449C] hover:bg-[#1B449C] hover:text-white">
                                                    Message
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <div class="p-8 bg-white rounded-xl text-center text-gray-500 shadow">
                                        لا يوجد مدرّسون لعرضهم حالياً.
                                    </div>
                                </div>

                            @endforelse
                        </div>
                    </div>

                    <!-- Pagination -->
                    <!-- <div class="mt-20 swiper-pagination !bottom-[-30px]"></div> -->
                    <div class="mt-20 swiper-pagination !bottom-[-30px]"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Become a Tutor Section -->
    <section class="px-4 py-16 bg-white rounded-lg shadow-xl">
        <div class="mx-auto lg:container">

            <!-- Main Container with Shadow and Border -->
            <div class="overflow-hidden p-2 bg-white rounded-lg shadow-md md:p-4">

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 lg:h-[400px]">

                    <!-- Image Section -->
                    <div class="overflow-hidden relative order-1 rounded-lg lg:order-1 rtl:order-2">
                        <!-- Main Image -->
                        <img src="{{ asset('front/assets/imgs/become_tutor/imgs.png') }}"
                            alt="Professional tutor working"
                            class="h-full object-cover min-h-[200px] md:min-h-[400px] lg:min-h-auto">

                        <!-- Gradient Overlay for depth -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-white/20 rtl:bg-gradient-to-l">
                        </div>
                    </div>

                    <!-- Text Section with Gradient Overlay -->
                    <div class="flex relative order-2 items-center mt-6 lg:p-5 lg:order-2 rtl:order-1 lg:mt-0">

                        <!-- Subtle gradient overlay extending from image -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r to-transparent pointer-events-none from-white/5 rtl:bg-gradient-to-l">
                        </div>

                        <!-- Content -->
                        <div class="relative z-10 w-full">

                            <!-- Main Title -->
                            <h2 class="mb-6 text-3xl font-bold leading-tight text-gray-900 md:text-xl lg:text-2xl">
                                Become a Tutor
                            </h2>

                            <!-- Description -->
                            <p class="mb-8 text-lg leading-relaxed text-gray-900 md:text-base">
                                Raise income by sharing your expertise with students. Register to begin online tutoring
                                with JINN EDU
                            </p>

                            <!-- Features List -->
                            <div class="flex justify-between items-center mb-8">

                                <!-- Feature 1 -->
                                <div class="flex items-start md:items-center">
                                    <i class="mr-2 text-sm text-[#EECD29] rtl:mr-0 rtl:ml-4">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.504 18.1134C18.6259 18.2399 18.6943 18.4109 18.6943 18.589C18.6943 18.7671 18.6259 18.9381 18.504 19.0645C18.4452 19.1302 18.3738 19.1827 18.2943 19.2186C18.2148 19.2544 18.1289 19.2729 18.0421 19.2729C17.9553 19.2729 17.8694 19.2544 17.7899 19.2186C17.7104 19.1827 17.639 19.1302 17.5801 19.0645L12.3463 13.6957C10.9615 14.8767 9.18804 15.4632 7.3942 15.3334C5.60035 15.2036 3.92399 14.3675 2.71326 12.9988C1.50254 11.63 0.850488 9.83373 0.89254 7.98303C0.934591 6.13233 1.66751 4.36942 2.93908 3.06041C4.21065 1.75141 5.92315 0.99692 7.72093 0.953631C9.51871 0.910342 11.2636 1.58158 12.5932 2.82795C13.9229 4.07432 14.7351 5.80002 14.8611 7.64668C14.9872 9.49333 14.4174 11.319 13.2702 12.7446L18.504 18.1134ZM3.93891 4.08016C2.88443 5.16103 2.28965 6.62846 2.28514 8.16033C2.28392 8.91867 2.42954 9.66966 2.71349 10.3694C2.99744 11.0692 3.41403 11.7036 3.93891 12.2357C4.461 12.7755 5.08154 13.2038 5.76489 13.4959C6.44824 13.7881 7.18093 13.9384 7.92087 13.9382C9.40687 13.9282 10.8293 13.3164 11.8805 12.2352C12.9318 11.1539 13.5272 9.69006 13.5381 8.16033C13.5383 7.3986 13.3923 6.64435 13.1085 5.94088C12.8247 5.23742 12.4087 4.59861 11.8844 4.06114C11.3675 3.52081 10.7511 3.09196 10.0714 2.79965C9.39167 2.50734 8.66215 2.35744 7.92549 2.35869C7.18348 2.36036 6.44914 2.51334 5.76488 2.80881C5.08063 3.10429 4.46002 3.53639 3.93891 4.08016Z"
                                                fill="#EECD29" />
                                        </svg>
                                    </i>
                                    <span class="font-bold text-gray-900 text-md lg:font-medium">Find new
                                        students</span>
                                </div>

                                <!-- Feature 2 -->
                                <div class="flex items-start md:items-center">
                                    <i class="mr-2 text-sm text-[#930DA7C2] rtl:mr-0 rtl:ml-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6.75 3V5.25M17.25 3V5.25M3 18.75V7.5C3 6.90326 3.23705 6.33097 3.65901 5.90901C4.08097 5.48705 4.65326 5.25 5.25 5.25H18.75C19.3467 5.25 19.919 5.48705 20.341 5.90901C20.7629 6.33097 21 6.90326 21 7.5V18.75M3 18.75C3 19.3467 3.23705 19.919 3.65901 20.341C4.08097 20.7629 4.65326 21 5.25 21H18.75C19.3467 21 19.919 20.7629 20.341 20.341C20.7629 19.919 21 19.3467 21 18.75M3 18.75V11.25C3 10.6533 3.23705 10.081 3.65901 9.65901C4.08097 9.23705 4.65326 9 5.25 9H18.75C19.3467 9 19.919 9.23705 20.341 9.65901C20.7629 10.081 21 10.6533 21 11.25V18.75"
                                                stroke="#930DA7" stroke-opacity="0.76" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </i>
                                    <span class="font-bold text-gray-900 text-md lg:font-medium">Grow your
                                        business</span>
                                </div>

                                <!-- Feature 3 -->
                                <div class="flex items-start md:items-center">
                                    <i class="mr-2 text-sm text-[#1B449CC2] rtl:mr-0 rtl:ml-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.25 18.75C7.58561 18.7457 12.898 19.4522 18.047 20.851C18.774 21.049 19.5 20.509 19.5 19.755V18.75M3.75 4.5V5.25C3.75 5.44891 3.67098 5.63968 3.53033 5.78033C3.38968 5.92098 3.19891 6 3 6H2.25M2.25 6V5.625C2.25 5.004 2.754 4.5 3.375 4.5H20.25M2.25 6V15M20.25 4.5V5.25C20.25 5.664 20.586 6 21 6H21.75M20.25 4.5H20.625C21.246 4.5 21.75 5.004 21.75 5.625V15.375C21.75 15.996 21.246 16.5 20.625 16.5H20.25M2.25 15V15.375C2.25 15.6734 2.36853 15.9595 2.5795 16.1705C2.79048 16.3815 3.07663 16.5 3.375 16.5H3.75M2.25 15H3C3.19891 15 3.38968 15.079 3.53033 15.2197C3.67098 15.3603 3.75 15.5511 3.75 15.75V16.5M20.25 16.5V15.75C20.25 15.5511 20.329 15.3603 20.4697 15.2197C20.6103 15.079 20.8011 15 21 15H21.75M20.25 16.5H3.75M15 10.5C15 11.2956 14.6839 12.0587 14.1213 12.6213C13.5587 13.1839 12.7956 13.5 12 13.5C11.2044 13.5 10.4413 13.1839 9.87868 12.6213C9.31607 12.0587 9 11.2956 9 10.5C9 9.70435 9.31607 8.94129 9.87868 8.37868C10.4413 7.81607 11.2044 7.5 12 7.5C12.7956 7.5 13.5587 7.81607 14.1213 8.37868C14.6839 8.94129 15 9.70435 15 10.5ZM18 10.5H18.008V10.508H18V10.5ZM6 10.5H6.008V10.508H6V10.5Z"
                                                stroke="#1B449C" stroke-opacity="0.76" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </i>
                                    <span class="font-bold text-gray-900 text-md lg:font-medium">Get paid
                                        securely</span>
                                </div>

                            </div>

                            <!-- Call to Action Button -->
                            <button
                                class="overflow-hidden relative px-16 py-3.5 w-full text-2xl font-bold text-white rounded-lg transition-all duration-300 transform lg:w-auto bg-primary group hover:bg-primary-700 hover:ml-2 hover:rtl:mr-2 hover:shadow-xl">
                                <span class="relative z-10">Get Started</span>
                                <div
                                    class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                                </div>
                            </button>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Three Steps Section -->
    <section class="pt-6 pb-24 w-full bg-white md:px-4">

        <div class="mx-auto lg:container">
            <!-- First Image Container -->
            <div class="w-full">
                <img src="{{ asset('front/assets/imgs/steps/img1.png') }}" alt="Student learning with laptop"
                    class="object-cover w-full h-auto">
            </div>

            <!-- Blue Message Container -->
            <div class="py-8 w-full text-center" style="background-color: #1B449C;">
                <h2 class="font-bold text-white text-md lg:text-[25px]">
                    3 Simple Steps To Start Learning With JINNEDU
                </h2>
            </div>

            <!-- Second Image Container -->
            <div class="w-full">
                <div class="px-4 mx-auto max-w-full lg:max-w-[90%]">
                    <img src="{{ asset('front/assets/imgs/steps/img2.png') }}" alt="Learning steps infographic"
                        class="object-cover w-full h-auto">
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
