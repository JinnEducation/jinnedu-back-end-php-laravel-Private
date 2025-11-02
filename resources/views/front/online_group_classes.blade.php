<x-front-layout>
    @push('styles')
        <style>
            .course-card.filtered-out {
                display: none !important;
            }
        </style>
    @endpush
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
                        Online Group classes
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">Online Group classes</h2>

            <!-- Filter Bar Section -->
            <div class="flex flex-wrap items-end gap-3 md:gap-4 lg:gap-6">
                <!-- Price (USD) Filter -->
                <div class="flex flex-col gap-2 min-w-[230px] flex-1 md:flex-none md:min-w-[180px]">
                    <label class="text-sm font-semibold text-primary">Price (USD)</label>
                    <div class="flex gap-2 items-center">
                        <!-- From Input with Floating Label -->
                        <div class="relative flex-1">
                            <input type="number" step="any" min="0" id="price-from" placeholder=" "
                                class="w-full px-3 pt-4 pb-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 peer placeholder-transparent">
                            <label for="price-from"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-lg text-black transition-all duration-200 pointer-events-none
                                    peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary
                                    peer-[&:not(:placeholder-shown)]:top-2 peer-[&:not(:placeholder-shown)]:text-sm peer-[&:not(:placeholder-shown)]:text-primary">From</label>
                        </div>
                        <!-- To Input with Floating Label -->
                        <div class="relative flex-1">
                            <input type="number" step="any" min="0" id="price-to" placeholder=" "
                                class="w-full px-3 pt-4 pb-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 peer placeholder-transparent">
                            <label for="price-to"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-lg text-black transition-all duration-200 pointer-events-none
                                    peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary
                                    peer-[&:not(:placeholder-shown)]:top-2 peer-[&:not(:placeholder-shown)]:text-sm peer-[&:not(:placeholder-shown)]:text-primary">To</label>
                        </div>
                    </div>
                </div>

                <!-- Level Filter -->
                <div class="flex flex-col gap-2 min-w-[110px] flex-1 md:flex-none md:min-w-[130px]">
                    <label class="text-sm font-semibold text-primary">Level</label>
                    <div class="relative">
                        <select id="level-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="all" selected>All</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Topic Filter -->
                <div class="flex flex-col gap-2 min-w-[110px] flex-1 md:flex-none md:min-w-[130px]">
                    <label class="text-sm font-semibold text-primary">Topic</label>
                    <div class="relative">
                        <select id="topic-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="" selected>Topic</option>
                            <option value="technology">Technology</option>
                            <option value="business">Business</option>
                            <option value="design">Design</option>
                            <option value="marketing">Marketing</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Time Filter -->
                <div class="flex flex-col gap-2 min-w-[150px] flex-1 md:flex-none md:min-w-[200px]">
                    <label class="text-sm font-semibold text-primary">Time</label>
                    <div class="relative">
                        <select id="time-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="" selected>Start time – End time</option>
                            <option value="morning">Morning (8:00 - 12:00)</option>
                            <option value="afternoon">Afternoon (12:00 - 17:00)</option>
                            <option value="evening">Evening (17:00 - 21:00)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Rate Filter -->
                <div class="flex flex-col gap-2 min-w-[110px] flex-1 md:flex-none md:min-w-[130px]">
                    <label class="text-sm font-semibold text-primary">Rate</label>
                    <div class="relative flex items-center h-[42px] px-3 bg-white border border-gray-300 rounded-md">
                        <svg class="w-5 h-5 text-yellow-400 fill-current flex-shrink-0 mr-2" viewBox="0 0 20 20">
                            <path
                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                        </svg>
                        <input type="number" step="0.1" max="5" id="rate-value" value="4.0"
                            class="w-12 text-sm text-gray-600 bg-transparent border-none outline-none focus:outline-none p-0">
                        <span class="text-sm text-gray-400 ml-1">/ 5</span>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                <div class="flex items-end w-full md:w-auto lg:ml-auto">
                    <button id="clear-filters-btn"
                        class="w-full h-[42px] px-8 text-sm font-semibold text-white bg-primary rounded-xl transition-all duration-200 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:w-auto">
                        Clear Filters
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto">
            <div>
                <!-- Courses Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 mb-12 md:gap-10"
                    id="coursesGridGroupClasses">

                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/1f25ce8f61417696102946d7b6061a827cf37c11.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/9c7c74a52c40cd53c6489ebc23d046fe219f81a7.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/1f25ce8f61417696102946d7b6061a827cf37c11.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/9c7c74a52c40cd53c6489ebc23d046fe219f81a7.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/1f25ce8f61417696102946d7b6061a827cf37c11.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/9c7c74a52c40cd53c6489ebc23d046fe219f81a7.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/1f25ce8f61417696102946d7b6061a827cf37c11.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/9c7c74a52c40cd53c6489ebc23d046fe219f81a7.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/1f25ce8f61417696102946d7b6061a827cf37c11.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>
                    <div
                        class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                        <div class="overflow-hidden relative h-54">
                            <img src="./assets/imgs/9c7c74a52c40cd53c6489ebc23d046fe219f81a7.jpg" alt="Course"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-bold text-primary">Lorem ipsum dolor sit</h3>
                            <p class="mb-4 text-sm text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>
                            <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                <div class="flex gap-10 items-center">
                                    <div class="flex gap-2 items-center">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-lg font-semibold text-gray-800">0 / 5</span>
                                    </div>
                                    <span class="text-lg text-gray-400">(0)</span>
                                </div>
                                <div class="flex gap-1.5 items-center">
                                    <svg class="text-primary" width="22" height="15" viewBox="0 0 22 15"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 3.32292C0 1.55465 1.34316 0 3.1439 0H13.7727C15.5735 0 16.9167 1.55465 16.9167 3.32292V11.1771C16.9167 12.9454 15.5735 14.5 13.7727 14.5H3.1439C1.34316 14.5 0 12.9454 0 11.1771V3.32292ZM3.1439 1.8125C2.473 1.8125 1.8125 2.4218 1.8125 3.32292V11.1771C1.8125 12.0783 2.473 12.6875 3.1439 12.6875H13.7727C14.4437 12.6875 15.1042 12.0783 15.1042 11.1771V3.32292C15.1042 2.4218 14.4437 1.8125 13.7727 1.8125H3.1439Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.2826 1.62337C21.5707 1.78308 21.7495 2.08658 21.7495 2.41602V12.0827C21.7495 12.4046 21.5787 12.7024 21.3008 12.865C21.0228 13.0275 20.6796 13.0304 20.399 12.8726L15.5657 10.1538C15.1294 9.90841 14.9747 9.35584 15.2201 8.91963C15.4655 8.48342 16.0181 8.32863 16.4543 8.57405L19.937 10.5331V4.05112L16.4903 6.20535C16.0658 6.47058 15.5067 6.34165 15.2415 5.91716C14.9762 5.49267 15.1052 4.93358 15.5296 4.66835L20.363 1.64752C20.6423 1.47291 20.9944 1.46367 21.2826 1.62337Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 9.96875C3.625 9.46826 4.03075 9.0625 4.53125 9.0625H12.3854C12.8859 9.0625 13.2917 9.46826 13.2917 9.96875C13.2917 10.4692 12.8859 10.875 12.3854 10.875H4.53125C4.03075 10.875 3.625 10.4692 3.625 9.96875Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.625 4.53125C3.625 4.03075 4.03075 3.625 4.53125 3.625H4.54333C5.04384 3.625 5.44958 4.03075 5.44958 4.53125C5.44958 5.03174 5.04384 5.4375 4.54333 5.4375H4.53125C4.03075 5.4375 3.625 5.03174 3.625 4.53125Z"
                                            fill="#1B449C" />
                                    </svg>

                                    <span class="text-lg font-medium text-gray-800">3</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <svg class="text-primary" width="22" height="23" viewBox="0 0 22 23"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.75 13.75C7.30228 13.75 7.75 13.3023 7.75 12.75C7.75 12.1977 7.30228 11.75 6.75 11.75C6.19772 11.75 5.75 12.1977 5.75 12.75C5.75 13.3023 6.19772 13.75 6.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M6.75 17.75C7.30228 17.75 7.75 17.3023 7.75 16.75C7.75 16.1977 7.30228 15.75 6.75 15.75C6.19772 15.75 5.75 16.1977 5.75 16.75C5.75 17.3023 6.19772 17.75 6.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 13.75C11.3023 13.75 11.75 13.3023 11.75 12.75C11.75 12.1977 11.3023 11.75 10.75 11.75C10.1977 11.75 9.75 12.1977 9.75 12.75C9.75 13.3023 10.1977 13.75 10.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M10.75 17.75C11.3023 17.75 11.75 17.3023 11.75 16.75C11.75 16.1977 11.3023 15.75 10.75 15.75C10.1977 15.75 9.75 16.1977 9.75 16.75C9.75 17.3023 10.1977 17.75 10.75 17.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 13.75C15.3023 13.75 15.75 13.3023 15.75 12.75C15.75 12.1977 15.3023 11.75 14.75 11.75C14.1977 11.75 13.75 12.1977 13.75 12.75C13.75 13.3023 14.1977 13.75 14.75 13.75Z"
                                            fill="#1B449C" />
                                        <path
                                            d="M14.75 17.75C15.3023 17.75 15.75 17.3023 15.75 16.75C15.75 16.1977 15.3023 15.75 14.75 15.75C14.1977 15.75 13.75 16.1977 13.75 16.75C13.75 17.3023 14.1977 17.75 14.75 17.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M21.5 5.75V18.75C21.5 19.745 21.105 20.698 20.402 21.402C19.698 22.105 18.745 22.5 17.75 22.5H3.75C2.755 22.5 1.802 22.105 1.098 21.402C0.395 20.698 0 19.745 0 18.75V5.75C0 4.755 0.395 3.802 1.098 3.098C1.802 2.395 2.755 2 3.75 2H17.75C18.745 2 19.698 2.395 20.402 3.098C21.105 3.802 21.5 4.755 21.5 5.75ZM20 5.75C20 5.153 19.763 4.581 19.341 4.159C18.919 3.737 18.347 3.5 17.75 3.5H3.75C3.153 3.5 2.581 3.737 2.159 4.159C1.737 4.581 1.5 5.153 1.5 5.75V18.75C1.5 19.347 1.737 19.919 2.159 20.341C2.581 20.763 3.153 21 3.75 21H17.75C18.347 21 18.919 20.763 19.341 20.341C19.763 19.919 20 19.347 20 18.75V5.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.75 7.5C21.164 7.5 21.5 7.836 21.5 8.25C21.5 8.664 21.164 9 20.75 9H0.75C0.336 9 0 8.664 0 8.25C0 7.836 0.336 7.5 0.75 7.5H20.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14 0.75C14 0.336 14.336 0 14.75 0C15.164 0 15.5 0.336 15.5 0.75V4.75C15.5 5.164 15.164 5.5 14.75 5.5C14.336 5.5 14 5.164 14 4.75V0.75Z"
                                            fill="#1B449C" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 0.75C6 0.336 6.336 0 6.75 0C7.164 0 7.5 0.336 7.5 0.75V4.75C7.5 5.164 7.164 5.5 6.75 5.5C6.336 5.5 6 5.164 6 4.75V0.75Z"
                                            fill="#1B449C" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-700">07/01/2025</span>
                                </div>
                                <span class="text-md font-medium text-gray-800">12:30 PM</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <div class="flex gap-2 items-center">
                                    <img src="./assets/imgs/a1.jpg" alt="Eman" class="w-10 h-10 rounded-full">
                                    <span class="text-base font-semibold text-gray-800">Eman</span>
                                </div>
                                <span class="text-lg font-bold text-primary">50$</span>
                            </div>
                            <a href="./online_group_classe.html"
                                class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                Preview this courses
                            </a>
                        </div>
                    </div>


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
                                <span class="text-sm font-medium text-gray-700">PER PAGE</span>
                            </div>
                            <!-- السهم من اليمين -->
                            <div class="flex absolute inset-y-0 right-0 items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination في المنتصف -->
                    <div class="flex gap-1 justify-center items-center" id="paginationBlogs">
                        <button
                            class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
                            data-page="prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <div id="pagesNumbers" class="flex gap-1"></div>

                        <button
                            class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
                            data-page="next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- فراغ على اليمين للتوازن -->
                    <div class="hidden w-32 md:block"></div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="{{ asset('front/assets/js/online_group_classes.js') }}"></script>
    @endpush
</x-front-layout>
