<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <div class="text-sm font-light text-gray-600 leading-relaxed">
                    <span>
                        <a href="index.html" class="text-primary-600 hover:text-primary-700">Home</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">Classes</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        Online Private classes
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">Online Private classes</h2>
            <div class="">
                <!-- Section Title -->
                <div class="flex items-center mb-8">
                    <i class="mr-3 text-xl text-primary fas fa-search rtl:mr-0 rtl:ml-3"></i>
                    <h2 class="text-[25px] font-extrabold text-gray-900">Find a Tutor</h2>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-3 md:grid-cols-3 lg:grid-cols-5">

                    {{-- What to Learn? --}}
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">
                            What to Learn?
                        </label>
                        <div class="relative">
                            <select
                                id="filterSubject"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Subject</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Price Range</label>
                        <div class="relative">
                            <select
                                id="filterPriceRange"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Any price</option>
                                <option value="0-10">Under 10 USD</option>
                                <option value="10-25">10 USD - 25 USD</option>
                                <option value="25-50">25 USD - 50 USD</option>
                                <option value="50-100">50 USD - 100 USD</option>
                                <option value="100-9999">100 USD+</option>
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Native Language -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Native Language</label>
                        <div class="relative">
                            <select
                                id="filterNativeLanguage"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Native Language</option>
                                @foreach($languages as $language)
                                <option value="{{ $language->id }}">
                                    {{ $language->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Availability Time -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Availability Time</label>
                        <div class="relative">
                            <select
                                id="filterAvailability"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Any Time</option>
                                <option value="morning">Morning (6AM - 12PM)</option>
                                <option value="afternoon">Afternoon (12PM - 6PM)</option>
                                <option value="evening">Evening (6PM - 10PM)</option>
                                <option value="night">Night (10PM - 6AM)</option>
                                <option value="weekend">Weekends Only</option>
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>


                    <!-- Specializations -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Specializations</label>
                        <div class="relative">
                            <select
                                id="filterSpecialization"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Specializations</option>
                                @foreach($specializations as $spec)
                                <option value="{{ $spec->id }}">
                                    {{ $spec->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Country</label>
                        <div class="relative">
                            <select
                                id="filterCountry"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">
                                    {{-- عندك بالـ DB أعمدة name / en_name / ar_name --}}
                                    {{ $country->en_name ?? $country->name ?? $country->ar_name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    <!-- Also Speaks -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Also Speaks</label>
                        <div class="relative">
                            <select
                                id="filterAlsoSpeaks"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Also Speaks</option>
                                @foreach($languages as $language)
                                <option value="{{ $language->id }}">
                                    {{ $language->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>
                    <!-- Sort By -->
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Sort By</label>
                        <div class="relative">
                            <select
                                id="filterSortBy"
                                class="text-[13px] px-4 py-3 w-full text-black bg-white rounded-lg border border-gray-300 transition-all duration-300 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                                <option value="">Sort By</option>
                                <option value="price_low_high">Price: Low to High</option>
                                <option value="price_high_low">Price: High to Low</option>
                                <option value="rating_high_low">Rating: High to Low</option>
                                <option value="most_popular">Most Popular</option>
                            </select>
                            <i class="absolute right-4 top-1/2 text-sm text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-chevron-down rtl:right-auto rtl:left-4"></i>
                        </div>
                    </div>

                    {{-- Full name --}}
                    <div class="flex flex-col">
                        <label class="mb-2 text-[15px] tracking-wide text-primary uppercase">Full name</label>
                        <div class="relative">
                            <input type="text"
                                id="filterFullName"
                                placeholder="Search by tutor name"
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
                    $fullName = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
                    if ($fullName === '') {
                    $fullName = $tutor->name ?? 'Tutor';
                    }

                    // IDs متعددة (JSON أو نص مفصول بفواصل)
                    $specializationIds = [];
                    if (!empty($tp?->specializations)) {
                    if (is_array($tp->specializations)) {
                    $specializationIds = $tp->specializations;
                    } else {
                    $specializationIds = array_filter(array_map('trim', explode(',', $tp->specializations)));
                    }
                    }

                    $otherLanguageIds = [];
                    if (!empty($tp?->other_languages)) {
                    if (is_array($tp->other_languages)) {
                    $otherLanguageIds = $tp->other_languages;
                    } else {
                    $otherLanguageIds = array_filter(array_map('trim', explode(',', $tp->other_languages)));
                    }
                    }

                    // فترات التوفر من user_availabilities (لو عندك حقل period)
                    $availabilityPeriods = [];
                    if ($tutor->relationLoaded('availabilities')) {
                    $availabilityPeriods = $tutor->availabilities
                    ->pluck('period')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
                    }

                    // أسماء لطيفة للعرض
                    $subjectName = $tp?->subject?->name ?? '-';
                    $nativeLangName = $tp?->nativeLanguageModel?->name ?? null;
                    $countryName = $tp?->countryModel?->en_name
                    ?? $tp?->countryModel?->name
                    ?? $tp?->countryModel?->ar_name
                    ?? null;

                    $hourlyRate = $tp->hourly_rate ?? null;
                    $rating = $tp->avg_rating ?? 0;
                    $reviewsCount = $tp->reviews_count ?? 0;
                    $studentsCount = $tp->students_count ?? 0;
                    @endphp

                    <div
                        class="tutor-card bg-white rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl"
                        data-full-name="{{ strtolower($fullName) }}"
                        data-subject-id="{{ $tp->teaching_subject ?? '' }}"
                        data-price="{{ $hourlyRate ?? 0 }}"
                        data-native-language-id="{{ $tp->native_language ?? '' }}"
                        data-country-id="{{ $tp->tutor_country ?? '' }}"
                        data-specialization-ids="{{ implode(',', $specializationIds) }}"
                        data-also-speaks-ids="{{ implode(',', $otherLanguageIds) }}"
                        data-availability-periods="{{ implode(',', $availabilityPeriods) }}"
                        data-rating="{{ $rating }}"
                        data-students="{{ $studentsCount }}">
                        <div class="flex gap-4">
                            <!-- Tutor Image -->
                            <div class="flex-shrink-0">
                                {{-- لو عندك صورة في البروفايل عدّلي السطر الجاي --}}
                                <img src="{{ asset('front/assets/imgs/tutors/1.jpg') }}"
                                    alt="{{ $fullName }}"
                                    class="w-53 h-full object-cover">
                            </div>

                            <!-- Tutor Info -->
                            <div class="flex-1 p-5 ">
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-primary mb-1">
                                            {{ $fullName }}
                                        </h3>
                                        @if($countryName)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $countryName }}
                                        </p>
                                        @endif
                                    </div>

                                    <!-- Price & Rating -->
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex flex-col items-start justify-end">
                                            <span class="text-lg font-bold text-primary">
                                                @if($hourlyRate !== null)
                                                {{ $hourlyRate }}$
                                                @else
                                                -
                                                @endif
                                            </span>
                                            <span class="text-sm text-black">Per hour</span>
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
                                                {{ $reviewsCount }} Reviews
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
                                        <span>{{ $studentsCount }} Students</span>
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
                                            Speaks
                                            <span class="text-primary font-semibold">
                                                {{ $nativeLangName ?? 'N/A' }} (Native)
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex gap-3 w-2/3">
                                    <button
                                        class="cursor-pointer flex-1 px-4 py-2.5 text-sm font-semibold text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                        View Details
                                    </button>
                                    <button
                                        class="cursor-pointer flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-700 transition-all duration-300 join-now-btn">
                                        Join Now
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
                            <img src="./assets/imgs/tutors/1.jpg" alt="Tutor Jinn"
                                class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 class="font-bold text-primary">Tutor Jinn</h3>
                                <p class="text-sm text-gray-600">English language</p>
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
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-xs font-semibold text-gray-700">M</div>
                            <div class="text-center text-xs font-semibold text-gray-700">T</div>
                            <div class="text-center text-xs font-semibold text-gray-700">W</div>
                            <div class="text-center text-xs font-semibold text-gray-700">Th</div>
                            <div class="text-center text-xs font-semibold text-gray-700">F</div>
                            <div class="text-center text-xs font-semibold text-gray-700">S</div>
                            <div class="text-center text-xs font-semibold text-gray-700">Su</div>
                        </div>

                        <!-- Schedule Grid -->
                        <div class="space-y-1 mb-4">
                            <!-- Morning Row -->
                            <div class="grid grid-cols-7 gap-1">
                                <div class="text-xs text-gray-500 flex items-center pr-1">Morning<br>8AM -<br>12AM</div>
                                <div class="col-span-6 grid grid-cols-6 gap-1">
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                </div>
                            </div>

                            <!-- Afternoon Row -->
                            <div class="grid grid-cols-7 gap-1">
                                <div class="text-xs text-gray-500 flex items-center pr-1">Afternoon<br>8AM -<br>12AM
                                </div>
                                <div class="col-span-6 grid grid-cols-6 gap-1">
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                </div>
                            </div>

                            <!-- Evening Row -->
                            <div class="grid grid-cols-7 gap-1">
                                <div class="text-xs text-gray-500 flex items-center pr-1">Evening<br>8AM -<br>12AM</div>
                                <div class="col-span-6 grid grid-cols-6 gap-1">
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                </div>
                            </div>

                            <!-- Night Row -->
                            <div class="grid grid-cols-7 gap-1">
                                <div class="text-xs text-gray-500 flex items-center pr-1">Night<br>8AM -<br>12AM</div>
                                <div class="col-span-6 grid grid-cols-6 gap-1">
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                    <div class="h-12 bg-blue-200 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <!-- View Full Schedule Button -->
                        <button
                            class="w-full py-3 text-center font-semibold text-white bg-primary hover:bg-primary-700 rounded-lg transition-all duration-300"
                            id="viewFullScheduleBtn">
                            View Full Schedule
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