<x-front-layout>

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
                            <img src="./assets/imgs/user-avatar.jpg" alt="Tutor Jinn"
                                class="w-36 h-36 rounded-lg object-cover flex-shrink-0">

                            <!-- Tutor Info -->
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-primary mb-2">Tutor Jinn</h2>
                                <p class="text-gray-600 mb-4">headline 1</p>

                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-comment text-primary"></i>
                                        <span>Speaks english Language</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-book text-primary"></i>
                                        <span>Teaches english-language Subject</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Section -->
                    <div class="bg-white rounded-lg p-6">
                        <!-- Tab Buttons -->
                        <div class="flex gap-3 mb-6">
                            <button data-tab="about"
                                class="tab-button active px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 bg-primary text-white hover:bg-primary hover:text-white">
                                About
                            </button>
                            <button data-tab="schedule"
                                class="tab-button px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 text-black hover:bg-primary hover:text-white">
                                Schedule
                            </button>
                            <button data-tab="reviews"
                                class="tab-button px-8 py-3 text-base font-semibold rounded-lg transition-all duration-300 text-black hover:bg-primary hover:text-white">
                                Reviews (2)
                            </button>
                        </div>

                        <!-- Tab Contents -->
                        <div>
                            <!-- About Tab Content -->
                            <div id="about-tab" class="tab-content">
                                <h3 class="text-2xl font-bold text-primary mb-4">Tutor Jinn</h3>
                                <div class="text-gray-700 leading-relaxed">
                                    <p class="mb-4">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex
                                        ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                                        esse cillum dolore eu
                                        fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                                        culpa qui officia deserunt
                                        mollit anim id est Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                        do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                        reprehenderit in voluptate velit
                                        esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                        non proident, sunt in culpa qui officia deserunt
                                        mollit anim id est laborum.
                                    </p>
                                    <div class="about-text-extra hidden">
                                        <p class="mb-4">
                                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                            doloremque laudantium,
                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                            architecto beatae vitae dicta sunt explicabo.
                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,
                                            sed quia consequuntur magni dolores eos qui ratione
                                            voluptatem sequi nesciunt.
                                        </p>
                                    </div>
                                    <button id="showMoreBtn" class="text-primary font-bold hover:underline mt-2">
                                        Show More
                                    </button>
                                </div>
                            </div>

                            <!-- Schedule Tab Content -->
                            <div id="schedule-tab" class="tab-content hidden">
                                <!-- Title and Date Navigation -->
                                <div class="mb-6">
                                    <h3 class="text-2xl font-bold text-primary mb-3">Tutor Schedule Wedne</h3>
                                    <div class="flex items-center gap-3">
                                        <span id="weekDate" class="text-base text-gray-700 font-medium">Wednesday,
                                            10/15/2025</span>
                                        <button id="prevWeek"
                                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </button>
                                        <button id="nextWeek"
                                            class="w-8 h-8 flex items-center justify-center bg-primary text-white rounded-full hover:bg-primary-700 transition-colors">
                                            <i class="fas fa-chevron-right text-sm"></i>
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
                                        View Full Schedule
                                    </button>
                                </div>
                            </div>

                            <!-- Reviews Tab Content -->
                            <div id="reviews-tab" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-primary mb-6">Tutor Schedule Wedne</h3>

                                <div class="space-y-6">
                                    <!-- Review Item 1 -->
                                    <div class="pb-6">
                                        <div class="flex items-center  gap-4">
                                            <img src="./assets/imgs/user-avatar.jpg" alt="Reviewer"
                                                class="w-18 h-18 rounded-md object-cover">
                                            <div class="flex-1">
                                                <div class="flex items-start flex-col  mb-2">
                                                    <h4 class="font-bold text-gray-800">joud-shaheen</h4>
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                        <span class="font-semibold">2 / 5</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-gray-600 text-sm leading-relaxed">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                eiusmod tempor
                                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud
                                                exercitation ullamco laboris nisi ut ali
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pb-6">
                                        <div class="flex items-center  gap-4">
                                            <img src="./assets/imgs/user-avatar.jpg" alt="Reviewer"
                                                class="w-18 h-18 rounded-md object-cover">
                                            <div class="flex-1">
                                                <div class="flex items-start flex-col  mb-2">
                                                    <h4 class="font-bold text-gray-800">joud-shaheen</h4>
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                        <span class="font-semibold">2 / 5</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-gray-600 text-sm leading-relaxed">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                eiusmod tempor
                                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud
                                                exercitation ullamco laboris nisi ut ali
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tutor Suggestions Section -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <h3 class="text-2xl font-bold text-primary mb-6">Tutor Suggestions</h3>

                        <div class="space-y-3">
                            <div
                                class="tutor-card bg-white rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl">
                                <div class="flex gap-4">
                                    <!-- Tutor Image -->
                                    <div class="flex-shrink-0">
                                        <img src="./assets/imgs/tutors/1.jpg" alt="Tutor Jinn"
                                            class="w-53 h-full object-cover">
                                    </div>

                                    <!-- Tutor Info -->
                                    <div class="flex-1 p-5 ">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <h3 class="text-xl font-bold text-primary mb-1">Tutor Jinn</h3>
                                            </div>
                                            <!-- Price & Rating -->
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex flex-col items-start justify-end">
                                                    <span class="text-lg font-bold text-primary">50$</span>
                                                    <span class="text-sm text-black">Per hour</span>
                                                </div>
                                                <div class="flex flex-col items-start justify-end">
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-yellow-400 fill-current"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                        </svg>
                                                        <span class="text-lg text-gray-700">4</span>
                                                    </div>
                                                    <span class="text-sm text-black">2 Reviews</span>
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
                                                <span>English language</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                </svg>
                                                <span>8 Students</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.723 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>Speaks <span
                                                        class="text-primary font-semibold">(Native)</span></span>
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
                            <div
                                class="tutor-card bg-white rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl">
                                <div class="flex gap-4">
                                    <!-- Tutor Image -->
                                    <div class="flex-shrink-0">
                                        <img src="./assets/imgs/tutors/2.jpg" alt="Tutor Jinn"
                                            class="w-53 h-full object-cover">
                                    </div>

                                    <!-- Tutor Info -->
                                    <div class="flex-1 p-5 ">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <h3 class="text-xl font-bold text-primary mb-1">Tutor Jinn</h3>
                                            </div>
                                            <!-- Price & Rating -->
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex flex-col items-start justify-end">
                                                    <span class="text-lg font-bold text-primary">50$</span>
                                                    <span class="text-sm text-black">Per hour</span>
                                                </div>
                                                <div class="flex flex-col items-start justify-end">
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-yellow-400 fill-current"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                        </svg>
                                                        <span class="text-lg text-gray-700">4</span>
                                                    </div>
                                                    <span class="text-sm text-black">2 Reviews</span>
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
                                                <span>English language</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                </svg>
                                                <span>8 Students</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.723 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>Speaks <span
                                                        class="text-primary font-semibold">(Native)</span></span>
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
                                <div class="text-4xl font-bold text-primary mb-1">0 <span
                                        class="text-2xl text-black">USD</span>
                                </div>
                            </div>

                            <!-- Video Preview -->
                            <div class="relative rounded-lg overflow-hidden bg-black aspect-video">
                                <video class="w-full h-full" controls poster="./assets/imgs/user-avatar.jpg">
                                    <source src="#" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>

                        <div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-3 gap-4 mb-6 text-start">
                                <div>
                                    <div class="flex items-center justify-start gap-1 mb-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="font-bold text-lg text-primary">4/5</span>
                                    </div>
                                    <div class="text-xs text-black">96 reviews</div>
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-primary mb-1">11773</div>
                                    <div class="text-xs text-black">lessons</div>
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-primary mb-1">$1,904</div>
                                    <div class="text-xs text-black">50-min lesson</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button
                                    class="w-full py-3 text-white bg-primary rounded-lg font-semibold hover:bg-primary-700 transition-all duration-300 shadow-sm hover:shadow-md">
                                    Book trial lesson
                                </button>
                                <button
                                    class="cursor-pointer w-full px-4 py-3 text-sm font-semibold text-primary border border-primary rounded-md hover:bg-primary hover:text-white transition-all duration-300">
                                    Message Cheryl
                                </button>
                                <button
                                    class="cursor-pointer w-full px-4 py-3 text-sm font-semibold text-primary border border-primary rounded-md hover:bg-primary hover:text-white transition-all duration-300">
                                    Save to my list
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Full Schedule Modal -->
    <div id="fullScheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-primary">Full Schedule</h3>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <p class="text-gray-600">Full schedule content will be displayed here...</p>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="{{ asset('front/assets/js/tutor_profile.js') }}"></script>
    @endpush

</x-front-layout>
