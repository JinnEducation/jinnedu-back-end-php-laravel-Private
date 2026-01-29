<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <div class="text-sm font-light text-gray-600 leading-relaxed">
                    <span>
                        <a href="{{ route('home') }}"
                            class="text-primary-600 hover:text-primary-700">{{ label_text('global', 'Home', __('auth.Home')) }}</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i
                            class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global', 'Classes', __('site.Classes')) }}</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i
                            class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        {{ label_text('global', 'Online Private classes', __('site.Online Private classes')) }}
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">
                {{ label_text('global', 'Online Private classes', __('site.Online Private classes')) }}
            </h2>
            <div class="">
                <!-- Section Title -->
                <div class="flex items-center mb-8">
                    <i class="mr-3 text-xl text-primary fas fa-search rtl:mr-0 rtl:ml-3"></i>
                    <h2 class="text-[25px] font-extrabold text-gray-900">
                        {{ label_text('global', 'Find a Tutor', __('site.Find a Tutor')) }}
                    </h2>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-3 md:grid-cols-3 lg:grid-cols-5">

                    {{-- What to Learn? --}}
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">
                            {{ label_text('global', 'What to Learn?', __('site.What to Learn?')) }}
                        </label>
                        <div class="relative">
                            <select id="filterSubject"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Subject', __('site.Subject')) }}</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->name }}" {{ ($filters['filterSubject'] ?? '') == $subject->name ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Price Range', __('site.Price Range')) }}</label>
                        <div class="relative">
                            <select id="filterPriceRange"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Any price', __('site.Any price')) }}</option>
                                <option value="0-10" {{ ($filters['filterPriceRange'] ?? '') == '0-10' ? 'selected' : '' }}>{{ label_text('global', 'Under 10 USD', __('site.Under 10 USD')) }}
                                </option>
                                <option value="10-25" {{ ($filters['filterPriceRange'] ?? '') == '10-25' ? 'selected' : '' }}>
                                    {{ label_text('global', '10 USD - 25 USD', __('site.10 USD - 25 USD')) }}
                                </option>
                                <option value="25-50" {{ ($filters['filterPriceRange'] ?? '') == "25-50" ? 'selected' : '' }}>
                                    {{ label_text('global', '25 USD - 50 USD', __('site.25 USD - 50 USD')) }}
                                </option>
                                <option value="50-100" {{ ($filters['filterPriceRange'] ?? '') == "50-100" ? 'selected' : '' }}>
                                    {{ label_text('global', '50 USD - 100 USD', __('site.50 USD - 100 USD')) }}
                                </option>
                                <option value="100-9999" {{ ($filters['filterPriceRange'] ?? '') == "100-9999" ? 'selected' : '' }}>{{ label_text('global', '100 USD+', __('site.100 USD+')) }}
                                </option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Native Language -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">
                            {{ label_text('global', 'Native Language', __('site.Native Language')) }}</label>
                        <div class="relative">
                            <select id="filterNativeLanguage"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">
                                    {{ label_text('global', 'Native Language', __('site.Native Language')) }}
                                </option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->name }}" {{ ($filters['filterNativeLanguage'] ?? '') == $language->name ? 'selected' : '' }}>
                                        {{ $language->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Availability Time -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Availability Time', __('site.Availability Time')) }}</label>
                        <div class="relative">
                            <select id="filterAvailability"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Any Time', __('site.Any Time')) }}</option>
                                <option value="morning"  {{ ($filters['filterAvailability'] ?? '') == "morning" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Morning (6AM - 12PM)', __('site.Morning (6AM - 12PM)')) }}
                                </option>
                                <option value="afternoon" {{ ($filters['filterAvailability'] ?? '') == "afternoon" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Afternoon (12PM - 6PM)', __('site.Afternoon (12PM - 6PM)')) }}
                                </option>
                                <option value="evening" {{ ($filters['filterAvailability'] ?? '') == "evening" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Evening (6PM - 10PM)', __('site.Evening (6PM - 10PM)')) }}
                                </option>
                                <option value="night" {{ ($filters['filterAvailability'] ?? '') == "night" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Night (10PM - 6AM)', __('site.Night (10PM - 6AM)')) }}
                                </option>
                                <option value="weekend" {{ ($filters['filterAvailability'] ?? '') == "weekend" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Weekends Only', __('site.Weekends Only')) }}
                                </option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Specializations -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Specializations', __('site.Specializations')) }}</label>
                        <div class="relative">
                            <select id="filterSpecialization"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">
                                    {{ label_text('global', 'Specializations', __('site.Specializations')) }}
                                </option>
                                @foreach ($specializations as $spec)
                                    <option value="{{ $spec->name }}"  {{ ($filters['filterSpecialization'] ?? '') == $spec->name ? 'selected' : '' }}>
                                        {{ $spec->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Country', __('site.Country')) }}</label>
                        <div class="relative">
                            <select id="filterCountry"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Country', __('site.Country')) }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" {{ ($filters['filterCountry'] ?? '') == $country->name ? 'selected' : '' }}>
                                        {{-- عندك بالـ DB أعمدة name / en_name / ar_name --}}
                                        {{ $country->en_name ?? ($country->name ?? $country->ar_name) }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Also Speaks -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Also Speaks', __('site.Also Speaks')) }}</label>
                        <div class="relative">
                            <select id="filterAlsoSpeaks"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Also Speaks', __('site.Also Speaks')) }}
                                </option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->name }}" {{ ($filters['filterAlsoSpeaks'] ?? '') == $language->name ? 'selected' : '' }}>
                                        {{ $language->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>
                    <!-- Sort By -->
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Sort By', __('site.Sort By')) }}</label>
                        <div class="relative">
                            <select id="filterSortBy"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">{{ label_text('global', 'Sort By', __('site.Sort By')) }}</option>
                                <option value="price_low_high" {{ ($filters['filterAvailability'] ?? '') == "price_low_high" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Price: Low to High', __('site.Price: Low to High')) }}
                                </option>
                                <option value="price_high_low" {{ ($filters['filterAvailability'] ?? '') == "price_high_low" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Price: High to Low', __('site.Price: High to Low')) }}
                                </option>
                                <option value="rating_high_low" {{ ($filters['filterAvailability'] ?? '') == "rating_high_low" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Rating: High to Low', __('site.Rating: High to Low')) }}
                                </option>
                                <option value="most_popular" {{ ($filters['filterAvailability'] ?? '') == "most_popular" ? 'selected' : '' }}>
                                    {{ label_text('global', 'Most Popular', __('site.Most Popular')) }}
                                </option>
                            </select>
                            <i
                                class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    {{-- Full name --}}
                    <div class="flex flex-col">
                        <label
                            class="mb-2 text-[15px] tracking-wide text-primary uppercase">{{ label_text('global', 'Full name', __('site.Full name')) }}</label>
                        <div class="relative">
                            <input type="text" id="filterFullName" value="{{ $filters['filterFullName'] ?? '' }}"
                                placeholder="{{ label_text('global', 'Search by tutor name', __('site.Search by tutor name')) }}"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Tutors List Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Tutors List -->
                <div class="lg:col-span-8 space-y-4" id="tutorsListContainer">

                    @forelse($tutors as $tutor)
                        @php
                            $profile = $tutor->profile;
                            $tp = $tutor->tutorProfile;

                            // اسم المعلم
                            $fullName = trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''));
                            if ($fullName === '') {
                                $fullName = $tutor?->name ?? 'Tutor';
                            }

                            // فترات التوفر من availability_json الجديد
                            $availabilityPeriods = [];
                            $availabilities = $tutor->getFilteredAvailabilities();

                            if ($availabilities && $availabilities->isNotEmpty()) {
                                // دالة لتحويل الوقت إلى فترات
                                $getPeriodsFromTimeRange = function ($hourFrom, $hourTo) {
                                    $periods = [];

                                    // تحويل الوقت إلى دقائق لتسهيل المقارنة
                                    $timeToMinutes = function ($time) {
                                        [$hours, $minutes] = explode(':', $time);
                                        return (int) $hours * 60 + (int) $minutes;
                                    };

                                    $fromMinutes = $timeToMinutes($hourFrom);
                                    $toMinutes = $timeToMinutes($hourTo);

                                    // تعريف الفترات بالدقائق
                                    $morningStart = 6 * 60; // 06:00
                                    $morningEnd = 12 * 60; // 12:00
                                    $afternoonStart = 12 * 60; // 12:00
                                    $afternoonEnd = 18 * 60; // 18:00
                                    $eveningStart = 18 * 60; // 18:00
                                    $eveningEnd = 22 * 60; // 22:00
                                    $nightStart = 22 * 60; // 22:00
                                    $nightEndNext = 6 * 60; // 06:00 (اليوم التالي)

                                    // معالجة الفترة الليلية التي تمتد لليوم التالي
                                    $spansMidnight = $fromMinutes > $toMinutes;

                                    if ($spansMidnight) {
                                        // فترة تمتد من ليلة اليوم إلى صباح اليوم التالي
                                        // Night: من 22:00 إلى 06:00
                                        $periods[] = 'night';
                                        // Morning: إذا كانت الفترة تمتد بعد 06:00
                                        if ($toMinutes > $morningStart) {
                                            $periods[] = 'morning';
                                        }
                                    } else {
                                        // فترة في نفس اليوم

                                        // Morning: 06:00 - 12:00
                                        if ($fromMinutes < $morningEnd && $toMinutes > $morningStart) {
                                            $periods[] = 'morning';
                                        }

                                        // Afternoon: 12:00 - 18:00
                                        if ($fromMinutes < $afternoonEnd && $toMinutes > $afternoonStart) {
                                            $periods[] = 'afternoon';
                                        }

                                        // Evening: 18:00 - 22:00
                                        if ($fromMinutes < $eveningEnd && $toMinutes > $eveningStart) {
                                            $periods[] = 'evening';
                                        }

                                        // Night: 22:00 - 24:00 أو 00:00 - 06:00
                                        if ($fromMinutes >= $nightStart || $toMinutes <= $nightEndNext) {
                                            $periods[] = 'night';
                                        }
                                    }

                                    return $periods;
                                };

                                foreach ($availabilities as $availability) {
                                    if (isset($availability->hour_from) && isset($availability->hour_to)) {
                                        $periods = $getPeriodsFromTimeRange(
                                            $availability->hour_from,
                                            $availability->hour_to
                                        );
                                        $availabilityPeriods = array_merge($availabilityPeriods, $periods);
                                    }
                                }

                                // Get unique values and re-index
                                $availabilityPeriods = array_values(
                                    array_unique(array_filter($availabilityPeriods))
                                );
                            }

                            // أسماء لطيفة للعرض
                            $subjectName = $tp?->teaching_subject ?? '-';
                            $nativeLangName = $tp?->native_language ?? '-';
                            $countryName = $tp?->tutor_country;

                            $hourlyRate = $tp->hourly_rate ?? null;

                            $rating = $tp->avg_rating ?? 0;
                            $reviewsCount = $tp->reviews_count ?? 0;
                            $studentsCount = $tp->students_count ?? 0;
                        @endphp

                        <div class="tutor-card bg-white rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl"
                            data-tutor-id="{{ $tutor->id }}" data-tutor-name="{{ $fullName }}"
                            data-tutor-subject="{{ $subjectName }}"
                            data-tutor-avatar="{{ $tutor->profile?->avatar_path ? asset('storage/' . $tutor->profile?->avatar_path) : ($tutor->avatar ? asset('storage/' . $tutor->avatar) : asset('front/assets/imgs/tutors/1.jpg')) }}"
                            data-tutor-slug="{{ $tutor->slug ?? $tutor->id }}"
                            data-availability="{{ json_encode($availabilities->map(function ($av) {
            return ['day_id' => $av->day->id ?? 0, 'day_name' => $av->day->name ?? '', 'hour_from' => $av->hour_from ?? '', 'hour_to' => $av->hour_to ?? '']; })->toArray()) }}"
                            data-full-name="{{ strtolower($fullName) }}" data-subject-id="{{ $tp->teaching_subject ?? '' }}"
                            data-price="{{ $hourlyRate ?? 0 }}" data-native-language-id="{{ $tp->native_language ?? '' }}"
                            data-country="{{ $profile->country ?? '' }}"
                            data-specialization="{{ $tp?->specializations }}"
                            data-also-speaks="{{ $tp?->other_languages ?? '' }}"
                            data-availability-periods="{{ implode(',', $availabilityPeriods) }}" data-rating="{{ $rating }}"
                            data-students="{{ $studentsCount }}">
                            <div class="flex gap-4">
                                <!-- Tutor Image -->
                                <div class="flex-shrink-0">
                                    {{-- لو عندك صورة في البروفايل عدّلي السطر الجاي --}}
                                    <img src="{{ $tutor->profile?->avatar_path ? asset('storage/' . $tutor->profile?->avatar_path) : ($tutor->avatar ? asset('storage/' . $tutor->avatar) : asset('front/assets/imgs/tutors/1.jpg')) }}"
                                        alt="{{ $fullName }}" class="w-53 h-full object-cover transition-all duration-300"
                                        loading="lazy">
                                </div>

                                <!-- Tutor Info -->
                                <div class="flex-1 p-5 ">
                                    <div class="flex justify-between items-center mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-primary mb-1">
                                                {{ $fullName }}
                                            </h3>
                                            @if ($countryName)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $countryName }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Price & Rating -->
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex flex-col items-start justify-end">
                                                <span class="text-lg font-bold text-primary">

                                                    {{ $hourlyRate }}$

                                                </span>
                                                <span class="text-sm text-black">
                                                    {{ label_text('global', 'Per hour', __('site.Per hour')) }} </span>
                                            </div>
                                            <div class="flex flex-col items-start justify-end">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                    </svg>
                                                    <span class="text-lg text-gray-700">
                                                        {{ number_format($rating, 1) }}
                                                    </span>
                                                </div>
                                                <span class="text-sm text-black">
                                                    {{ $reviewsCount }}
                                                    {{ label_text('global', 'Review', __('site.Review')) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info Rows -->
                                    <div class="space-y-2 mb-4">
                                        {{-- المادة التي يدرّسها --}}
                                        <div class="flex items-center gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                            </svg>
                                            <span>
                                                {{ $subjectName }}
                                            </span>
                                        </div>

                                        {{-- عدد الطلاب --}}
                                        <div class="flex items-center gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            <span>{{ $studentsCount }}
                                                {{ label_text('global', 'Students', __('site.Students')) }}</span>
                                        </div>

                                        {{-- اللغة الأم --}}
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
                                                    {{ label_text('global', 'Native', __('site.Native')) }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                                <!-- Buttons -->
                                                <div class="flex gap-3 w-2/3">
                                                    <a href="{{ route('site.tutor_jinn', $tutor->id) }}"
                                                        class="cursor-pointer px-4 py-2.5 text-sm font-semibold text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                                       {{ label_text('global', 'View Details', __('site.View Details')) }} 
                                                    </a>
                                                    <button onclick="window.location.href='{{ route('redirect.dashboard', ['redirect_to'=>'/chats/private-chat?user_id=' . $tutor->id]) }}'"
                                                        type="button"
                                                        class="cursor-pointer flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-700 transition-all duration-300 w-full">
                                                      {{ label_text('global', 'Message Now', __('site.Message Now')) }}   
                                                    </button>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @empty
                                    <p class="text-gray-500">No tutors found.</p>
                                    @endforelse

                </div>


                <!-- Right Column: Schedule Preview -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-lg shadow-md p-5 sticky top-32">
                        <!-- Header -->
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 mb-4">
                            <img id="scheduleTutorAvatar" src="./assets/imgs/tutors/1.jpg" alt="Tutor"
                                class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 id="scheduleTutorName" class="font-bold text-primary">
                                    {{ label_text('global', 'Tutor Jinn', __('site.Tutor Jinn')) }}
                                </h3>
                                <p id="scheduleTutorSubject" class="text-sm text-gray-600">
                                    {{ label_text('global', 'English language', __('site.English language')) }}
                                </p>
                            </div>
                            <button class="p-2 text-primary hover:bg-gray-100 rounded-lg transition-all">
                                <svg width="53" height="50" viewBox="0 0 53 50" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.96875 49.6875H48.0312C48.4884 49.6875 48.8594 49.3165 48.8594 48.8594C48.8594 48.4023 48.4884 48.0312 48.0312 48.0312H4.96875C4.51163 48.0312 4.14062 48.4023 4.14062 48.8594C4.14062 49.3165 4.51163 49.6875 4.96875 49.6875Z"
                                        fill="#1B449C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.828125 44.7188H52.1719C52.629 44.7188 53 44.3477 53 43.8906C53 43.4335 52.629 43.0625 52.1719 43.0625H0.828125C0.371 43.0625 0 43.4335 0 43.8906C0 44.3477 0.371 44.7188 0.828125 44.7188Z"
                                        fill="#1B449C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.828125 39.75H52.1719C52.629 39.75 53 39.379 53 38.9219C53 38.4648 52.629 38.0938 52.1719 38.0938H0.828125C0.371 38.0938 0 38.4648 0 38.9219C0 39.379 0.371 39.75 0.828125 39.75Z"
                                        fill="#1B449C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M53 5.79688C53 2.59534 50.4047 0 47.2031 0C37.3874 0 15.6126 0 5.79688 0C2.59534 0 0 2.59534 0 5.79688V28.9844C0 32.1859 2.59534 34.7812 5.79688 34.7812H47.2031C50.4047 34.7812 53 32.1859 53 28.9844V5.79688Z"
                                        fill="#1B449C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M19.4436 6.72607C19.1868 6.58611 18.8754 6.59191 18.6245 6.74097C18.3728 6.89003 18.2188 7.16083 18.2188 7.45316V27.3282C18.2188 27.6205 18.3728 27.8913 18.6245 28.0403C18.8754 28.1894 19.1868 28.1952 19.4436 28.0553L37.6623 18.1178C37.9281 17.9728 38.0938 17.6938 38.0938 17.3907C38.0938 17.0876 37.9281 16.8085 37.6623 16.6636L19.4436 6.72607Z"
                                        fill="white" />
                                </svg>

                            </button>
                        </div>

                        <!-- Days Header -->
                        <div class="grid grid-cols-8 gap-1 mb-2">
                            <div class="text-center text-xs font-semibold text-gray-700">#</div>
                            <div class="text-center text-xs font-semibold text-gray-700">M</div>
                            <div class="text-center text-xs font-semibold text-gray-700">T</div>
                            <div class="text-center text-xs font-semibold text-gray-700">W</div>
                            <div class="text-center text-xs font-semibold text-gray-700">Th</div>
                            <div class="text-center text-xs font-semibold text-gray-700">F</div>
                            <div class="text-center text-xs font-semibold text-gray-700">S</div>
                            <div class="text-center text-xs font-semibold text-gray-700">Su</div>
                        </div>

                        <!-- Schedule Grid -->
                        <div id="scheduleGrid" class="space-y-1 mb-4">
                            <!-- Morning Row -->
                            <div class="grid grid-cols-8 gap-1" data-period="morning">
                                <div class="text-xs text-gray-500 flex items-center justify-center">Morning<br>6AM
                                    -<br>12PM</div>
                                <div class="h-12 bg-gray-100 rounded" data-day="monday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="tuesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="wednesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="thursday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="friday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="saturday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="sunday"></div>
                            </div>

                            <!-- Afternoon Row -->
                            <div class="grid grid-cols-8 gap-1" data-period="afternoon">
                                <div class="text-xs text-gray-500 flex items-center justify-center">Afternoon<br>12PM
                                    -<br>6PM</div>
                                <div class="h-12 bg-gray-100 rounded" data-day="monday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="tuesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="wednesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="thursday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="friday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="saturday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="sunday"></div>
                            </div>

                            <!-- Evening Row -->
                            <div class="grid grid-cols-8 gap-1" data-period="evening">
                                <div class="text-xs text-gray-500 flex items-center justify-center">Evening<br>6PM
                                    -<br>10PM</div>
                                <div class="h-12 bg-gray-100 rounded" data-day="monday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="tuesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="wednesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="thursday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="friday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="saturday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="sunday"></div>
                            </div>

                            <!-- Night Row -->
                            <div class="grid grid-cols-8 gap-1" data-period="night">
                                <div class="text-xs text-gray-500 flex items-center justify-center">Night<br>10PM
                                    -<br>6AM</div>
                                <div class="h-12 bg-gray-100 rounded" data-day="monday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="tuesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="wednesday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="thursday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="friday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="saturday"></div>
                                <div class="h-12 bg-gray-100 rounded" data-day="sunday"></div>
                            </div>
                        </div>

                        <!-- View Full Schedule Button -->
                        <button
                            class="w-full py-3 text-center font-semibold text-white bg-primary hover:bg-primary-700 rounded-lg transition-all duration-300"
                            id="viewFullScheduleBtn">
                            {{ label_text('global', 'View Full Schedule', __('site.View Full Schedule')) }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>


    @push('scripts')
        <script src="{{ asset('front/assets/js/tutors_list.js') }}"></script>
        <script src="{{ asset('front/assets/js/find_tutor.js') }}"></script>
    @endpush

</x-front-layout>