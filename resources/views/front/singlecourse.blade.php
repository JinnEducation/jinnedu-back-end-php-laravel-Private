<x-front-layout>
    @php
        $fmt = function (int $seconds) {
            $seconds = max(0, $seconds);
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            $s = $seconds % 60;
            return $h > 0 ? sprintf('%02d:%02d:%02d', $h, $m, $s) : sprintf('%02d:%02d', $m, $s);
        };

        $totalLabel = function (int $seconds) {
            $seconds = max(0, $seconds);
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            if ($h <= 0) {
                return $m . ' min';
            }
            if ($m <= 0) {
                return $h . ' hours';
            }
            return $h . ' hours ' . $m . ' min';
        };

    @endphp

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-6">
                <div class="text-sm font-light text-black leading-relaxed">
                    <span>
                        <a href="{{ route('home') }}" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global', 'site.Home', __('site.Home')) }}
                        </a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global', 'site.Course', __('site.Course')) }}
                        </a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-black break-words">
                        {{ $courseLang->title ?? '-' }}
                    </span>
                </div>
            </nav>

            <!-- Course Title & Description -->
            <div>
                <h1 class="text-3xl font-bold text-black mb-3">{{ $courseLang->title ?? '-' }}</h1>
                @if (!empty($courseLang?->excerpt))
                    <p class="text-2xl text-black leading-relaxed">
                        {{ $courseLang->excerpt }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Course Detail Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto px-4">
            <!-- Two Column Layout -->
            <div class="mt-6 flex flex-col lg:flex-row gap-10">
                <!-- Left Column: Main Content -->
                <div class="w-full lg:w-2/3 space-y-10">
                    <!-- Tabs -->
                    <div class="flex gap-2 pb-6 mt-6 border-b-2 border-[#CAC6C6]">
                        <button
                            class="course-tab px-4 py-2 text-sm font-bold text-white bg-primary border border-primary rounded-lg transition"
                            data-target="#about-course">{{ label_text('global', 'site.About the course', __('site.About the course')) }}</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-outputs">{{ label_text('global', 'site.Outputs', __('site.Outputs')) }}</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-content">{{ label_text('global', 'site.Units', __('site.Units')) }}</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-reviews">{{ label_text('global', 'site.Evaluation', __('site.Evaluation')) }}</button>
                    </div>

                    <!-- About the course -->
                    <div id="about-course">
                        <h2 class="text-lg font-bold text-black mt-2 mb-3">{{ label_text('global', 'site.About the course', __('site.About the course')) }}</h2>
                        @if (!empty($courseLang?->description))
                            <div class="text-[15px] text-black leading-7 space-y-4">
                                {!! nl2br(e($courseLang->description)) !!}
                            </div>
                        @endif
                    </div>

                    <!-- What you will learn -->
                    @if (!empty($outcomes) && count($outcomes))
                        <div class="mt-6">
                            <h2 class="text-lg font-bold text-black mb-3">
                                {{ label_text('global', 'site.What you will learn', __('site.What you will learn')) }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($outcomes as $outcome)
                                    <div class="flex items-start gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                        <p class="text-[15px] text-black leading-6">
                                            {{ $outcome }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Course content (Accordions) -->
                    <div id="course-content" class="mt-6 border border-gray-300 p-4">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <h2 class="text-[20px] font-semibold text-black">{{ label_text('global', 'site.Course content', __('site.Course content')) }}</h2>
                            <span class="text-[14px] text-gray-500">
                                {{ $totalLabel((int) $totalSeconds) }} • {{ $course->category?->name ?? '—' }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            @foreach ($content as $secIndex => $sec)
                                <div class="accordion-item overflow-hidden">
                                    <button type="button"
                                        class="accordion-header flex justify-between items-center w-full px-4 py-3 cursor-pointer bg-[#F7F7F7] border border-[#CAC6C6] rounded-lg hover:bg-gray-100 text-lg"
                                        data-acc-target="acc-{{ $secIndex }}">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $sec['title'] }}</span>
                                        </div>

                                        <svg class="icon w-4 h-4 text-gray-500 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>

                                    <div id="acc-{{ $secIndex }}"
                                        class="accordion-body hidden px-2 py-3 text-lg text-black leading-6">
                                        <div class="space-y-2">
                                            @foreach ($sec['items'] as $itemIndex => $item)
                                                <div
                                                    class="flex justify-between items-center pb-4 border-b border-[#CAC6C6]">
                                                    <span class="flex items-center gap-2">
                                                        <span
                                                            class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                                        <span>{{ $item['title'] }}</span>
                                                    </span>
                                                    <span class="text-[13px] text-gray-500">
                                                        {{ $fmt((int) $item['duration_seconds']) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <!-- Requirements -->
                    @if (!empty($requirements) && count($requirements))
                        <div id="requirements" class="mt-10">
                            <h2 class="text-lg font-bold text-black mb-3">
                                {{ label_text('global', 'site.Requirements', __('site.Requirements')) }}
                            </h2>

                            <div class="space-y-2">
                                @foreach ($requirements as $req)
                                    <div class="flex items-start gap-2">
                                        <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                        <p class="text-[15px] text-black leading-6">
                                            {{ $req }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                </div>

                <!-- Right Column: Sidebar -->
                <div class="w-full lg:w-1/3 space-y-4 mt-8 lg:mt-0">
                    <div class="rounded-md bg-white shadow-lg">
                        <!-- Video Card -->
                        <div class="relative rounded-t-lg w-full h-[216px] bg-gray-200 overflow-hidden">
                            <img src="{{ asset('front/assets/imgs/blogs/2.jpg') }}" alt="Course video"
                                class="w-full h-full object-cover">

                            <span
                                class="absolute top-1 right-1 rtl:left-1 rtl:right-auto text-sm bg-primary text-white p-1.5 rounded-lg z-8">
                                {{ label_text('global', 'site.Certified Achievement Certificate', __('site.Certified Achievement Certificate')) }}
                            </span>
                            @if (!empty($course->promo_video_url))
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <button type="button" onclick="openPromoVideo()"
                                        class="bg-primary px-3 py-0.5 rounded-lg hover:scale-105 transition">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <!-- left: 50%;
                            transform: translate(-50%, -50%); -->
                            <button
                                class="absolute left-1/2 -translate-1/2 z-10  bottom-0 text-white font-bold text-sm">
                                {{ label_text('global', 'site.View the course', __('site.View the course')) }}
                            </button>
                            <span class="absolute bg-black opacity-15 w-full h-full top-0 z-1"></span>
                        </div>
                        <div class="p-5">
                            @php
                                $isFree = $course->is_free;
                                $hasDiscount = $course->has_active_discount;
                                $price = (float) $course->price;
                                $final = (float) $course->final_price;
                            @endphp

                            <p class="text-[13px] text-black font-bold mb-1">
                                {{ $isFree ? label_text('global', 'site.This course is free', __('site.This course is free')) : label_text('global', 'site.This paid course is included in the plans', __('site.This paid course is included in the plans')) }}
                            </p>

                            <div class="flex items-center gap-2">
                                @if ($isFree)
                                    <span class="text-[19px] font-bold text-primary">{{ label_text('global', 'site.Free', __('site.Free')) }}</span>
                                @else
                                    @if ($hasDiscount)
                                        <span
                                            class="text-[17px] line-through text-gray-400">${{ number_format($price, 2) }}</span>
                                    @endif
                                    <span
                                        class="text-[19px] font-bold text-primary">${{ number_format($final, 2) }}</span>
                                @endif
                            </div>

                            @if ($hasDiscount)
                                <div class="mb-3 mt-2">
                                    <span class="text-[17px] text-primary bg-[#1B449C1A] rounded-full py-0.5 px-2">
                                        {{ label_text('global', 'site.Limited discount', __('site.Limited discount')) }}
                                    </span>
                                </div>
                            @endif

                            {{-- <div class="space-y-1 text-[13px] text-black font-light mb-4">
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>36 hours of on-demand video</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Project files + graph templates</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Practical tasks + solutions</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Certificate of completion</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Lifetime access + updates</span>
                                </div>
                            </div> --}}
                            <div class="space-y-1 text-[13px] text-black font-light mb-4">
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5"></span>
                                    <span>{{ $totalLabel((int) $totalSeconds) }} of on-demand video</span>
                                </div>

                                @if ($course->has_certificate)
                                    <div class="flex items-start gap-2">
                                        <span class="w-1 h-1 rounded-full bg-black mt-1.5"></span>
                                        <span>{{ label_text('global', 'site.Certificate of completion', __('site.Certificate of completion')) }}</span>
                                    </div>
                                @endif

                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5"></span>
                                    <span>{{ label_text('global', 'site.Lifetime access + updates', __('site.Lifetime access + updates')) }}</span>
                                </div>
                            </div>
                            @guest
                                <button
                                    class="mt-4 px-12 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition">
                                    {{ label_text('global', 'Login to Book', __('site.Login to Book')) }}
                                </button>
                            @endguest

                            @auth
                                <form action="{{ route('site.bookCourse', ['id' => $course->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="mt-4 px-12 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition">
                                        {{ label_text('global', 'Book Now', __('site.Book Now')) }}
                                    </button>
                                </form>                                
                            @endauth
                        </div>
                    </div>

                    @if ($course->has_certificate)
                        <!-- Certificate Card -->
                        <div class="border border-gray-200 rounded-xl p-3 bg-white shadow-lg">
                            <p class="text-[13px] font-bold text-black mb-2">{{ label_text('global', 'site.The certificate is shareable', __('site.The certificate is shareable')) }}</p>
                            <div class="flex items-center gap-2 mb-2">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5 14.5C6.38071 14.5 7.5 13.3807 7.5 12C7.5 10.6193 6.38071 9.5 5 9.5C3.61929 9.5 2.5 10.6193 2.5 12C2.5 13.3807 3.61929 14.5 5 14.5Z"
                                            stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M19 7.5C20.3807 7.5 21.5 6.38071 21.5 5C21.5 3.61929 20.3807 2.5 19 2.5C17.6193 2.5 16.5 3.61929 16.5 5C16.5 6.38071 17.6193 7.5 19 7.5Z"
                                            stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M19 21.5C20.3807 21.5 21.5 20.3807 21.5 19C21.5 17.6193 20.3807 16.5 19 16.5C17.6193 16.5 16.5 17.6193 16.5 19C16.5 20.3807 17.6193 21.5 19 21.5Z"
                                            stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M7.29999 11L16.7 6M7.29999 13L16.7 18" stroke="#1B449C"
                                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </span>
                                <p class="text-[13px] font-light text-black">{{ label_text('global', 'site.Share on your social media or LinkedIn', __('site.Share on your social media or LinkedIn')) }}</p>
                            </div>

                            <img src="{{ asset('front/assets/imgs/cer1.jpg') }}" alt="Certificate"
                                class="w-full object-contain rounded-b-lg">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="pb-8 md:pb-16 pt-6 bg-[#1B449C08]">
        <div class="container mx-auto px-4">
            <!-- Reviews Section -->
            <h2 class="text-[15px] font-bold text-black mb-3">
                {{ number_format($avgRating, 1) }} ({{ $reviewsCount }} {{ label_text('global', 'site.Reviewed on', __('site.Reviewed on')) }})
            </h2>

            <div id="course-reviews" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <!-- Rating Summary -->
                    <div class="space-y-1 mb-6">
                        @for ($star = 5; $star >= 1; $star--)
                            @php
                                $count = $ratingsDist[$star] ?? 0;
                                $percent = $reviewsCount > 0 ? round(($count / $reviewsCount) * 100, 1) : 0;
                            @endphp

                            <div class="flex items-center gap-2 text-[14px] text-black">
                                <span class="flex items-center gap-2 w-24">
                                    <span>
                                        <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                                fill="#FFC700" />
                                        </svg>
                                    </span>
                                    <span class="font-bold text-[15px]">{{ $star }} stars</span>
                                </span>

                                <div class="flex-1 h-1.5 rounded bg-gray-200">
                                    <div class="bg-primary h-1.5 rounded" style="width: {{ $percent }}%"></div>
                                </div>

                                <span class="w-11 text-[#00000099]">{{ $percent }}%</span>
                            </div>
                        @endfor
                    </div>

                </div>

                <!-- Review Cards -->
                <div class="space-y-4 md:col-span-2">
                    @forelse($course->reviews as $review)
                        <div class="border border-[#CAC6C6] rounded-xl p-4 flex flex-col gap-1">

                            <div class="flex items-center gap-2">
                                <img src="{{ asset('assets/imgs/user-avatar.jpg') }}" alt="Reviewer"
                                    class="w-16.5 h-16.5 rounded-full object-cover flex-shrink-0">

                                <div class="flex-1">
                                    <p class="text-[15px] font-bold text-black">
                                        {{ $review->user->name ?? 'Anonymous' }}
                                    </p>

                                    <div class="flex items-center gap-2 my-1 text-[15px]">
                                        <span class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg width="20" height="20" viewBox="0 0 24 24"
                                                    fill="{{ $i <= $review->rating ? '#FFC700' : '#E5E7EB' }}">
                                                    <path
                                                        d="M11.9988 18.2742L7.84885 20.7742L8.94885 14.9742L3.77385 11.7992L9.19885 9.62422L11.9988 4.57422L14.7989 9.62422L20.2239 11.7992L15.0488 14.9742L16.1488 20.7742L11.9988 18.2742Z" />
                                                </svg>
                                            @endfor
                                        </span>

                                        <span class="font-bold text-black">{{ $review->rating }}</span>

                                        <p class="text-[#00000099]">
                                            {{ label_text('global', 'site.Reviewed on', __('site.Reviewed on')) }} {{ $review->created_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!empty($review->comment))
                                <p class="text-[15px] text-gray-700 leading-6">
                                    {{ $review->comment }}
                                </p>
                            @endif

                        </div>
                    @empty
                        <p class="text-sm text-gray-500">
                            {{ label_text('global', 'site.No reviews yet for this course.', __('site.No reviews yet for this course.')) }}
                        </p>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-16">
        <div class="container mx-auto">
            <!-- Reviews Section -->
            <h2 class="text-lg font-bold text-black mb-3">{{ label_text('global', 'site.Courses You Might Be Interested In', __('site.Courses You Might Be Interested In')) }}</h2>
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($related as $item)
                    @php
                        $lang = $item->langs->first();
                        $isFree = $item->is_free;
                        $hasDiscount = $item->has_active_discount;
                        $price = (float) $item->price;
                        $final = (float) $item->final_price;
                    @endphp

                    <div
                        class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group hover:shadow-lg hover:scale-105">

                        <!-- Image (Placeholder مؤقت) -->
                        <div class="overflow-hidden relative h-48 rounded-sm">
                            <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?w=400&h=250&fit=crop"
                                alt="Course image" class="object-cover w-full h-full">
                        </div>

                        <div class="pt-4">
                            <!-- Title -->
                            <h3 class="mb-2 text-lg font-bold text-black text-[18px]">
                                {{ $lang->title ?? '—' }}
                            </h3>

                            <!-- Excerpt -->
                            @if (!empty($lang?->excerpt))
                                <p class="mb-4 text-[13px] text-black line-clamp-2">
                                    {{ $lang->excerpt }}
                                </p>
                            @endif

                            <div class="pt-4 border-t border-[#E5E7EB]">
                                <!-- Default view -->
                                <div
                                    class="flex justify-between items-center h-[45px] transition-all duration-300 group-hover:hidden group-hover:opacity-0">

                                    <div class="flex gap-2 items-center">
                                        <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                        <span class="text-sm text-black">
                                            {{-- مدة تقريبية مؤقتة --}}
                                            {{ label_text('global', 'site.Course duration', __('site.Course duration')) }}
                                        </span>
                                    </div>

                                    <span class="text-lg font-bold text-[#1B449C]">
                                        @if ($isFree)
                                        {{ label_text('global', 'site.Free', __('site.Free')) }}
                                        @else
                                            @if ($hasDiscount)
                                                ${{ number_format($final, 2) }}
                                            @else
                                                ${{ number_format($price, 2) }}
                                            @endif
                                        @endif
                                    </span>
                                </div>

                                <!-- Hover view -->
                                <div
                                    class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                    <a href="{{ route('front.course.single', [app()->getLocale(), $item->id]) }}"
                                        class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 bg-[#1B449C]">
                                        {{ label_text('global', 'site.Preview this course', __('site.Preview this course')) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-sm text-gray-500">
                        {{ label_text('global', 'site.No related courses found.', __('site.No related courses found.')) }}
                    </p>
                @endforelse
            </div>

            <!-- Load More Button -->
            {{-- <div class="text-center">
                <button id="loadMoreBtn"
                    class="overflow-hidden relative px-9 py-4 text-[15px] text-white rounded-lg transition-all duration-300 transform bg-primary group hover:bg-primary-700 hover:-translate-y-2 hover:shadow-xl">
                    <span class="relative z-10">Load More</span>
                    <div
                        class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                    </div>
                </button>
            </div> --}}
        </div>
    </section>

    @if (!empty($course->promo_video_url))
        <div id="promoVideoModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
            <div class="bg-black w-full max-w-2xl relative rounded-lg overflow-hidden" style="max-height: 70vh">

                <!-- close -->
                <button onclick="closePromoVideo()" class="absolute top-2 right-2 text-white text-xl z-10">
                    ✕
                </button>

                <!-- video -->
                <video id="promoVideoPlayer" class="w-full h-auto max-h-[70vh]" controls playsinline
                    preload="metadata">
                    <source src="{{ asset($course->promo_video_url) }}" type="video/mp4">
                    {{ label_text('global', 'site.Your browser does not support the video tag.', __('site.Your browser does not support the video tag.')) }}
                </video>

            </div>
        </div>
    @endif



    @push('scripts')
        <script src="{{ asset('front/assets/js/course_detail.js') }}"></script>
        <script>
            function openPromoVideo() {
                const modal = document.getElementById('promoVideoModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closePromoVideo() {
                const modal = document.getElementById('promoVideoModal');
                const video = document.getElementById('promoVideoPlayer');

                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        </script>
    @endpush

</x-front-layout>
