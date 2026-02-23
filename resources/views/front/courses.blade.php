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
                        <span class="text-gray-900">{{ label_text('global', 'this-courses', __('site.this-courses')) }}</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-7 text-3xl font-bold">
                {{ label_text('global', 'Explore-Our-Courses', __('site.Explore Our Courses')) }}
            </h2>

            @if($category)
                <h3 class="mb-7 text-2xl font-bold">
                    {{ label_text('course_categories', $category->name, __('course_categories.' . $category->name)) }}
                </h3>
            @endif

            <!-- Paid / Free Tabs - سطر كامل -->
            <div class="flex w-full mb-6 md:mb-12 border-b border-gray-200">
                <button type="button"
                    class="flex-1 py-3 font-bold transition-all duration-300 text-md tab-type-btn border-b-2 border-primary text-primary -mb-px"
                    data-type="paid">
                    {{ label_text('global', 'site.Paid', __('site.Paid')) }}
                </button>
                <button type="button"
                    class="flex-1 py-3 font-medium transition-all duration-300 text-md tab-type-btn border-b-2 border-transparent hover:text-primary hover:font-bold -mb-px"
                    data-type="free">
                    {{ label_text('global', 'site.Free', __('site.Free')) }}
                </button>
            </div>

        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="coursesGrid">
                @forelse ($courses as $index => $course)
                    @php
                        // ===== Title (multilang) =====
                        $langRow = $course->langs?->firstWhere('lang', $langShorts) ?? $course->langs?->first();
                        $title = $langRow->title ?? $course->title ?? $course->name ?? 'Course';
                        $desc = $langRow->short_description ?? $langRow->description ?? $course->short_description ?? null;

                        // ===== URL (عدّلي حسب routes عندك) =====
                        // $courseUrl = url('/course/' . $course->id);
                        $courseUrl = route('site.singlecourse', $course->id);

                        // ===== Duration =====
                        $hours = $course->course_duration_hours ?? $course->duration_hours ?? 0;

                        // ===== Pricing + Discount =====
                        $price = (float) ($course->price ?? 0);
                        $finalPrice = $price;
                        $oldPrice = null;

                        if ($course->activeDiscount) {
                            $oldPrice = $price;

                            $dtype = $course->activeDiscount->discount_type ?? null;
                            $dval = (float) ($course->activeDiscount->discount_value ?? 0);

                            if ($dtype === 'percent') {
                                $finalPrice = max(0, $price - ($price * ($dval / 100)));
                            } elseif ($dtype === 'fixed') {
                                $finalPrice = max(0, $price - $dval);
                            }
                        }

                        $isFree = $course->is_free;

                        $initialVisibleCount = 12;
                        // Load-more visibility (أول $initialVisibleCount ظاهر، والباقي مخفي)
                        $hiddenStyle = ($index >= $initialVisibleCount) ? 'display:none;' : '';
                    @endphp

                    <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                        style="{{ $hiddenStyle }}"
                        data-is-free="0">
                        {{-- data-is-free="{{ $isFree ? '1' : '0' }}"> --}}

                        <div class="overflow-hidden relative h-48 rounded-sm">
                            <img src="{{ $course->course_image_full }}" alt="{{ $title }}" class="object-cover w-full h-full">
                        </div>

                        <div class="pt-4">
                            <h3 class="mb-2 text-lg font-bold text-black text-[18px] line-clamp-2">
                                {{ $title }}
                            </h3>

                            @if($desc)
                                <p class="mb-4 text-[13px] text-black line-clamp-2">
                                    {{ strip_tags($desc) }}
                                </p>
                            @else
                                <p class="mb-4 text-[13px] text-black line-clamp-2">
                                    {{ __('site.Learn with confidence.') }}
                                </p>
                            @endif

                            <div class="pt-4 border-t border-[#E5E7EB]">
                                <!-- Default (before hover) -->
                                <div class="flex justify-between items-center h-[45px] transition-all duration-300 group-hover:opacity-0 group-hover:hidden">
                                    <div class="flex gap-2 items-center">
                                        <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                        <span class="text-sm text-black">
                                            @if($hours)
                                                {{ $hours }} {{ __('site.total hours') }}
                                            @else
                                                {{ __('site.Self paced') }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex flex-col items-end">
                                        @if($isFree)
                                            <span class="text-lg font-bold text-[#1B449C]">{{ __('site.Free') }}</span>
                                        @else
                                            @if($oldPrice && $oldPrice > $finalPrice)
                                                <span class="text-sm line-through text-[#87CEEB]">${{ number_format($oldPrice, 2) }}</span>
                                            @endif
                                            <span class="text-[15px] font-bold text-[#1B449C]">${{ number_format($finalPrice, 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Hover CTA -->
                                <div class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                    <a href="{{ $courseUrl }}"
                                        class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                        {{ __('site.Preview this course') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500">
                        {{ __('site.No courses found') }}
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
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pagination في المنتصف -->
                <div class="flex gap-1 justify-center items-center" id="paginationCourses">
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

    @push('scripts')
        <script src="{{ asset('front/assets/js/courses.js') }}"></script>
    @endpush

</x-front-layout>