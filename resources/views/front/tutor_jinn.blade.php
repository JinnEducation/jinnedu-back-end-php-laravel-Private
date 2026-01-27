<x-front-layout>

    @php
        $profile = $tutor->profile ?? null;
        $tp = $tutor->tutorProfile;

        // الاسم الكامل
        $fullName = $profile?->full_name ?? ($tutor->name ?? 'Tutor');

        // الصورة
        $avatar = $profile?->avatar_path
            ? asset('storage/' . $profile->avatar_path)
            : ($tutor->avatar
                ? asset('storage/' . $tutor->avatar)
                : asset('front/assets/imgs/user-avatar.jpg'));

        // بيانات أساسية
        $headline = $tp?->headline ?? '';
        $nativeLanguage = $tp?->native_language ?? 'N/A';
        $teachingSubject = $tp?->teaching_subject ?? 'N/A';
        $tutorCountry = $tp?->tutor_country ?? $profile?->country;

        // نص الـ About
        $aboutMain = $tp?->experience_bio ?? $tp?->motivation ?? '';
        $aboutExtra = $tp?->methodology ?? '';

        // الفيديو
        $videoPath = $tp?->video_path ? asset('storage/' . $tp->video_path) : null;

        // إحصائيات (لو ما عندك أعمدة، هتطلع 0 عادي)
        $rating = $tp->avg_rating ?? 0;
        $lessonsCount = $tp->lessons_count ?? 0;
    @endphp

    <!-- Main Content - Tutor Profile -->
    <section class="py-8 md:py-16 mt-[120px]">
        <div class="container px-4 mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Tutor Card -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex gap-6 items-start">
                            <!-- Tutor Image -->
                            <img src="{{ $avatar }}" alt="{{ $fullName }}"
                                class="w-36 h-36 rounded-lg object-cover flex-shrink-0">

                            <!-- Tutor Info -->
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-primary mb-2">{{ $fullName }}</h2>

                                @if($headline)
                                    <p class="text-gray-600 mb-4">{{ $headline }}</p>
                                @endif

                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-comment text-primary"></i>
                                        <span>
                                            {{ label_text('global', 'Speaks', __('site.Speaks')) }}
                                            {{-- {{ $nativeLanguage }} --}}
                                            {{ label_text('languages', $nativeLanguage, __('languages.' . $nativeLanguage)) }}
                                            {{ label_text('global', 'Language', __('site.Language')) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-book text-primary"></i>
                                        <span>
                                            {{ label_text('global', 'Teaches', __('site.Teaches')) }}
                                            {{ $teachingSubject }}
                                            {{ label_text('global', 'Subject', __('site.Subject')) }}
                                        </span>
                                    </div>

                                    @if($tutorCountry)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fas fa-globe text-primary"></i>
                                            <span>
                                                {{ label_text('global', 'From', __('site.From')) }}
                                                {{ $tutorCountry }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $availability = json_encode(($availabilities ?? collect())->map(function ($av) {
                            return [
                                'day_id' => $av->day->id ?? 0,
                                'day_name' => $av->day->name ?? '',
                                'hour_from' => $av->hour_from ?? '',
                                'hour_to' => $av->hour_to ?? ''
                            ];
                        })->toArray());
                    @endphp

                    <!-- Tabs Section -->
                    <div class="bg-white rounded-lg p-6" data-availability='{{ $availability }}'>
                        <!-- Tab Buttons -->
                        <div class="flex gap-3 mb-6">
                            <button data-tab="about"
                                class="tab-button active px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 bg-primary text-white hover:bg-primary hover:text-white">
                                {{ label_text('global', 'About', __('site.About')) }}
                            </button>
                            <button data-tab="schedule"
                                class="tab-button px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 text-black hover:bg-primary hover:text-white">
                                {{ label_text('global', 'Schedule', __('site.Schedule')) }}
                            </button>
                            <button data-tab="reviews"
                                class="tab-button px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 text-black hover:bg-primary hover:text-white">
                                {{ label_text('global', 'Reviews', __('site.Reviews')) }} ({{ $reviewsCount }})
                            </button>
                        </div>

                        <!-- Tab Contents -->
                        <div>
                            <!-- About Tab Content -->
                            <div id="about-tab" class="tab-content">
                                <h3 class="text-2xl font-bold text-primary mb-4">{{ $fullName }}</h3>
                                <div class="text-gray-700 leading-relaxed">
                                    @if($aboutMain)
                                        <p class="mb-4">
                                            {!! nl2br(e($aboutMain)) !!}
                                        </p>
                                    @else
                                        <p class="mb-4">
                                            {{ label_text('global', 'No bio has been added yet.', __('site.No bio has been added yet.')) }}
                                        </p>
                                    @endif

                                    @if($aboutExtra)
                                        <div class="about-text-extra hidden">
                                            <p class="mb-4">
                                                {!! nl2br(e($aboutExtra)) !!}
                                            </p>
                                        </div>
                                        <button id="showMoreBtn" class="text-primary font-bold hover:underline mt-2">
                                            {{ label_text('global', 'Show More', __('site.Show More')) }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Schedule Tab Content -->
                            <div id="schedule-tab" class="tab-content hidden">
                                <!-- Title and Date Navigation -->
                                <div class="mb-6">
                                    <h3 class="text-2xl font-bold text-primary mb-3">
                                        {{ label_text('global', 'Tutor Schedule', __('site.Tutor Schedule')) }} - {{ $fullName }}
                                    </h3>
                                    <div class="flex items-center gap-3">
                                        <span id="weekDate" class="text-base text-gray-700 font-medium">
                                            {{ label_text('global', 'This Week', __('site.This Week')) }}
                                        </span>
                                        <button id="prevWeek"
                                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left'}} text-sm"></i>
                                        </button>
                                        <button id="nextWeek"
                                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Schedule Table -->
                                <div class="overflow-x-auto mb-8">
                                    <div id="scheduleGrid" class="flex gap-3 min-w-max">
                                        <!-- Days will be generated dynamically by JavaScript -->
                                    </div>
                                </div>

                                <!-- View Full Schedule Button -->
                                <div class="text-center">
                                    <button id="viewFullScheduleBtn"
                                        class="px-10 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-700 transition-all">
                                        {{ label_text('global', 'View Full Schedule', __('site.View Full Schedule')) }}
                                    </button>
                                </div>
                            </div>

                            <!-- Reviews Tab Content -->
                            <div id="reviews-tab" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-primary mb-6">
                                    {{ label_text('global', 'Reviews', __('site.Reviews')) }} ({{ $reviewsCount }})
                                </h3>

                                {{-- Placeholder ثابت حالياً، لاحقاً يمكن ربطه بجدول التقييمات --}}
                                <div class="space-y-6">
                                    @foreach($reviews as $review)
                                        @php
                                            $reviewer = $review->user;
                                            $reviewerProfile = $reviewer->profile;
                                            $reviewerName = $reviewerProfile?->full_name ?? $reviewer?->name ?? 'Reviewer';
                                        @endphp
                                        <div class="pb-6">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $reviewerProfile?->avatar_path ? asset('storage/' . $reviewerProfile?->avatar_path) : ($reviewer?->avatar ? asset('storage/' . $reviewer?->avatar) : asset('front/assets/imgs/user-avatar.jpg')) }}"
                                                    alt="{{ label_text('global', 'Reviewer', __('site.Reviewer')) }}" class="w-18 h-18 rounded-md object-cover">
                                                <div class="flex-1">
                                                    <div class="flex items-start flex-col mb-2">
                                                        <h4 class="font-bold text-gray-800">{{ $reviewerName }}</h4>
                                                        <div class="flex items-center gap-1">
                                                            <i class="fas fa-star text-yellow-400"></i>
                                                            <span class="font-semibold">
                                                                {{ number_format($review->stars, 1) }} / 5
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-gray-600 text-sm leading-relaxed">
                                                    {{ $review->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tutor Suggestions Section -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <h3 class="text-2xl font-bold text-primary mb-6">
                            {{ label_text('global', 'Tutor Suggestions', __('site.Tutor Suggestions')) }}
                        </h3>

                        <div class="space-y-3">
                            @forelse($tutorsSuggestions as $suggestion)
                                @php
                                    $profileSuggestion = $suggestion->profile;
                                    $tpSuggestion = $suggestion->tutorProfile;

                                    // اسم المعلم
                                    $fullName = trim(($profileSuggestion?->first_name ?? '') . ' ' . ($profileSuggestion?->last_name ?? ''));
                                    if ($fullName === '') {
                                        $fullName = $suggestion?->name ?? 'Tutor';
                                    }

                                    // أسماء لطيفة للعرض
                                    $subjectName = $tpSuggestion?->teaching_subject ?? '-';
                                    $nativeLangName = $tpSuggestion?->native_language ?? '-';
                                    $countryName = $tpSuggestion?->tutor_country;

                                    $hourlyRate = $tpSuggestion->hourly_rate ?? null;

                                    $rating = $tpSuggestion->avg_rating ?? 0;
                                    $reviewsCount = $tpSuggestion->reviews_count ?? 0;
                                    $studentsCount = $tpSuggestion->students_count ?? 0;
                                @endphp
                                <div
                                    class="tutor-card bg-white rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl">
                                    <div class="flex gap-4">
                                        <!-- Tutor Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $suggestion->profile?->avatar_path ? asset('storage/' . $suggestion->profile?->avatar_path) : ($suggestion->avatar ? asset('storage/' . $suggestion->avatar) : asset('front/assets/imgs/tutors/1.jpg')) }}"
                                                alt="{{ $fullName }}" class="w-53 h-full object-cover">
                                        </div>

                                        <!-- Tutor Info -->
                                        <div class="flex-1 p-5 ">
                                            <div class="flex justify-between items-center mb-3">
                                                <div>
                                                    <h3 class="text-xl font-bold text-primary mb-1">{{ $fullName }}</h3>
                                                </div>
                                                <!-- Price & Rating -->
                                                <div class="flex items-center justify-between gap-3">
                                                    <div class="flex flex-col items-start justify-end">
                                                        <span
                                                            class="text-lg font-bold text-primary">{{ $hourlyRate }}$</span>
                                                        <span class="text-sm text-black">
                                                            {{ label_text('global', 'Per hour', __('site.Per hour')) }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-col items-start justify-end">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-yellow-400 fill-current"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                            </svg>
                                                            <span
                                                                class="text-lg text-gray-700">{{ number_format($rating, 1) }}</span>
                                                        </div>
                                                        <span class="text-sm text-black">
                                                            {{ $reviewsCount }} {{ label_text('global', 'Reviews', __('site.Reviews')) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Rows -->
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                                    <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                                    </svg>
                                                    <span>{{ $subjectName }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                                    <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                    </svg>
                                                    <span>{{ $studentsCount }} {{ label_text('global', 'Students', __('site.Students')) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                                    <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.723 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>
                                                        {{ label_text('global', 'Speaks', __('site.Speaks')) }}
                                                        <span class="text-primary font-semibold">
                                                            {{ $nativeLangName ?? 'N/A' }}
                                                            {{ label_text('global', '(Native)', __('site.(Native)')) }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="flex gap-3 w-2/3">
                                                <a href="{{ route('site.tutor_jinn', $suggestion->id) }}"
                                                    class="cursor-pointer px-4 py-2.5 text-sm font-semibold text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                                    {{ label_text('global', 'View Details', __('site.View Details')) }}
                                                </a>
                                                <form
                                                    action="{{ route('site.private_lesson_order', ['id' => $suggestion->id]) }}"
                                                    method="POST">
                                                    @csrf

                                                    <button type="submit"
                                                        class="cursor-pointer flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-700 transition-all duration-300 join-now-btn w-full">
                                                        {{ label_text('global', 'Join Now', __('site.Join Now')) }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">{{ label_text('global', 'No tutors found.', __('site.No tutors found.')) }}</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-32 space-y-4">
                        <!-- Price Card -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-3">
                            <!-- Price at Top -->
                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold text-primary mb-1">
                                    {{ $tutor?->tutorProfile?->hourly_rate }} <span
                                        class="text-2xl text-black">USD</span>
                                </div>
                            </div>

                            <!-- Video Preview -->
                            <div class="relative rounded-lg overflow-hidden bg-black aspect-video">
                                @if($videoPath)
                                    <video class="w-full h-full" controls poster="{{ $avatar }}">
                                        <source src="{{ $videoPath }}" type="video/mp4">
                                        {{ label_text('global', 'Your browser does not support the video tag.', __('site.Your browser does not support the video tag.')) }}
                                    </video>
                                @else
                                    <img src="{{ $avatar }}" alt="{{ $fullName }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>

                        <div>
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-3 gap-4 mb-6 text-start">
                                <div>
                                    <div class="flex items-center justify-start gap-1 mb-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="font-bold text-lg text-primary">
                                            {{ number_format($rating, 1) }}/5
                                        </span>
                                    </div>
                                    <div class="text-xs text-black">{{ $reviewsCount }} {{ label_text('global', 'reviews', __('site.reviews')) }}</div>
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-primary mb-1">{{ $lessonsCount }}</div>
                                    <div class="text-xs text-black">{{ label_text('global', 'lessons', __('site.lessons')) }}</div>
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-primary mb-1">
                                        {{ $hourlyRate ?? 0 }} USD
                                    </div>
                                    <div class="text-xs text-black">{{ label_text('global', '50-min lesson', __('site.50-min lesson')) }}</div>
                                </div>
                            </div>

                            @guest
                                <!-- Guest Action Buttons -->
                                <div class="space-y-3">
                                    <button type="button" data-open="#loginModal"
                                        class="px-6 py-3 mb-3 w-full text-base font-medium text-white rounded-md bg-primary-600 transition-colors hover:bg-primary-700">
                                        {{ label_text('global', 'Login to Book', __('site.Login to Book')) }}
                                    </button>
                                </div>
                            @endguest
                            @auth
                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                    @if(!$orderTrialExists)
                                    <form action="{{ route('site.trial_lesson_order', ['id' => $tutor->id]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-3 text-white bg-primary rounded-lg font-semibold hover:bg-primary-700 transition-all duration-300 shadow-sm hover:shadow-md">
                                            {{ label_text('global', 'Book trial lesson', __('site.Book trial lesson')) }}
                                        </button>
                                    </form>
                                    @endif
                                    @if($orderTrialExists && !$orderTrialFinash)
                                    <a href="{{ route('redirect.dashboard',['redirect_to'=> '/conferences/student-index']) }}"
                                        class="w-full block text-center py-3 text-white bg-primary rounded-lg font-semibold hover:bg-primary-700 transition-all duration-300 shadow-sm hover:shadow-md">
                                        {{ label_text('global', 'Go to dashboard Trial Lesson', __('site.Go to dashboard Trial Lesson')) }}
                                    </a>
                                    @endif
                                    @if($orderTrialFinash && $checkAllowOrder)
                                    <form action="{{ route('site.private_lesson_order', ['id' => $tutor->id]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-3 text-white bg-primary rounded-lg font-semibold hover:bg-primary-700 transition-all duration-300 shadow-sm hover:shadow-md">
                                            {{ label_text('global', 'Book Lesson', __('site.Book Lesson')) }}
                                        </button>
                                    </form>
                                    @endif
                                    @if($orderTrialExists && $orderTrialFinash && !$checkAllowOrder)
                                    <a href="{{ route('redirect.dashboard',['redirect_to'=> '/conferences/student-index']) }}"
                                        class="w-full block text-center py-3 text-white bg-primary rounded-lg font-semibold hover:bg-primary-700 transition-all duration-300 shadow-sm hover:shadow-md">
                                        {{ label_text('global', 'Go to dashboard', __('site.Go to dashboard')) }}
                                    </a>
                                    @endif
                                    <button
                                        class="cursor-pointer w-full px-4 py-3 text-sm font-semibold text-primary border border-primary rounded-md hover:bg-primary hover:text-white transition-all duration-300">
                                        {{ label_text('global', 'Message', __('site.Message')) }} {{ $profile?->full_name }}
                                    </button>
                                    <button id="favTutorBtn"
    type="button"
    data-ref="{{ $tutor->id }}"
    data-type="1"
    class="cursor-pointer w-full px-4 py-3 text-sm font-semibold text-primary border border-primary rounded-md hover:bg-primary hover:text-white transition-all duration-300">
    {{ label_text('global', 'Save to my list', __('site.Save to my list')) }}
</button>

                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Full Schedule Modal -->
    <div id="fullScheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-primary">
                        {{ label_text('global', 'Full Schedule', __('site.Full Schedule')) }} - {{ $fullName }}
                    </h3>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <!-- Title and Date Navigation -->
                <div class="mb-6">
                    <div class="flex items-center gap-3">
                        <span id="weekDateModal" class="text-base text-gray-700 font-medium">
                            {{ label_text('global', 'This Week', __('site.This Week')) }}
                        </span>
                        <button id="prevWeekModal"
                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left'}} text-sm"></i>
                        </button>
                        <button id="nextWeekModal"
                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div class="overflow-x-auto mb-8">
                    <div id="scheduleGridModal" class="flex gap-3 min-w-max">
                        <!-- Days will be generated dynamically by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('front/assets/js/tutor_profile.js') }}"></script>
        <script src="{{ asset('front/assets/js/user_favorite.js') }}"></script>
    @endpush

</x-front-layout>
