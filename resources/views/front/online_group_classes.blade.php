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
                        <a href="index.html" class="text-primary-600 hover:text-primary-700">{{ label_text('global', 'Home', __('site.Home')) }}</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">{{ label_text('global', 'Classes', __('site.Classes')) }}</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}} text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        {{ label_text('global', 'Online Group classes', __('site.Online Group classes')) }}
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">{{ label_text('global', 'Online Group classes', __('site.Online Group classes')) }}</h2>

            <!-- Filter Bar Section -->
            <div class="flex flex-wrap items-end gap-3 md:gap-4 lg:gap-6">
                <!-- Price (USD) Filter -->
                <div class="flex flex-col gap-2 min-w-[230px] flex-1 md:flex-none md:min-w-[180px]">
                    <label class="text-sm font-semibold text-primary">{{ label_text('global', 'Price (USD)', __('site.Price (USD)')) }}</label>
                    <div class="flex gap-2 items-center">
                        <!-- From Input with Floating Label -->
                        <div class="relative flex-1">
                            <input type="number" step="any" min="0" id="price-from" placeholder=" "
                                class="w-full px-3 pt-4 pb-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 peer placeholder-transparent">
                            <label for="price-from"
                                class="absolute left-3 rtl:left-auto rtl:right-3 top-1/2 -translate-y-1/2 text-lg text-black transition-all duration-200 pointer-events-none
                                    peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary
                                    peer-[&:not(:placeholder-shown)]:top-2 peer-[&:not(:placeholder-shown)]:text-sm peer-[&:not(:placeholder-shown)]:text-primary">{{ label_text('global', 'From', __('site.From')) }}</label>
                        </div>
                        <!-- To Input with Floating Label -->
                        <div class="relative flex-1">
                            <input type="number" step="any" min="0" id="price-to" placeholder=" "
                                class="w-full px-3 pt-4 pb-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 peer placeholder-transparent">
                            <label for="price-to"
                                class="absolute left-3 rtl:left-auto rtl:right-3 top-1/2 -translate-y-1/2 text-lg text-black transition-all duration-200 pointer-events-none
                                    peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary
                                    peer-[&:not(:placeholder-shown)]:top-2 peer-[&:not(:placeholder-shown)]:text-sm peer-[&:not(:placeholder-shown)]:text-primary">{{ label_text('global', 'To', __('site.To')) }}</label>
                        </div>
                    </div>
                </div>

                <!-- Level Filter -->
                <div class="flex flex-col gap-2 min-w-[110px] flex-1 md:flex-none md:min-w-[130px]">
                    <label class="text-sm font-semibold text-primary">{{ label_text('global', 'Level', __('site.Level')) }}</label>
                    <div class="relative">
                        <select id="level-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="all" selected>{{ label_text('global', 'All', __('site.All')) }}</option>
                            <option value="beginner">{{ label_text('global', 'Beginner', __('site.Beginner')) }}</option>
                            <option value="intermediate">{{ label_text('global', 'Intermediate', __('site.Intermediate')) }}</option>
                            <option value="advanced">{{ label_text('global', 'Advanced', __('site.Advanced')) }}</option>
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
                    <label class="text-sm font-semibold text-primary">{{ label_text('global', 'Topic', __('site.Topic')) }}</label>
                    <div class="relative">
                        <select id="topic-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="" selected>{{ label_text('global', 'Topic', __('site.Topic')) }}</option>
                            <option value="technology">{{ label_text('global', 'Technology', __('site.Technology')) }}</option>
                            <option value="business">{{ label_text('global', 'Business', __('site.Business')) }}</option>
                            <option value="design">{{ label_text('global', 'Design', __('site.Design')) }}</option>
                            <option value="marketing">{{ label_text('global', 'Marketing', __('site.Marketing')) }}</option>
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
                    <label class="text-sm font-semibold text-primary">{{ label_text('global', 'Time', __('site.Time')) }}</label>
                    <div class="relative">
                        <select id="time-filter"
                            class="w-full h-[42px] px-3 pr-8 text-sm text-black bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            <option value="" selected>{{ label_text('global', 'Start time placeholder', __('site.Start time placeholder')) }}</option>
                            <option value="morning">{{ label_text('global', 'Morning (8:00 - 12:00)', __('site.Morning (8:00 - 12:00)')) }}</option>
                            <option value="afternoon">{{ label_text('global', 'Afternoon (12:00 - 17:00)', __('site.Afternoon (12:00 - 17:00)')) }}</option>
                            <option value="evening">{{ label_text('global', 'Evening (17:00 - 21:00)', __('site.Evening (17:00 - 21:00)')) }}</option>
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
                    <label class="text-sm font-semibold text-primary">{{ label_text('global', 'Rate', __('site.Rate')) }}</label>
                    <div class="relative flex items-center h-[42px] px-3 bg-white border border-gray-300 rounded-md">
                        <svg class="w-5 h-5 text-yellow-400 fill-current flex-shrink-0 me-2" viewBox="0 0 20 20">
                            <path
                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                        </svg>
                        <input type="number" step="0.1" max="5" id="rate-value" value="" placeholder="4.0"
                            class="w-12 text-sm text-gray-600 bg-transparent border-none outline-none focus:outline-none p-0">
                        <span class="text-sm text-gray-400 ml-1">/ 5</span>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                <div class="flex items-end w-full md:w-auto lg:ml-auto">
                    <button id="clear-filters-btn"
                        class="w-full h-[42px] px-8 text-sm font-semibold text-white bg-primary rounded-xl transition-all duration-200 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:w-auto">
                       {{ label_text('global', 'Clear Filters', __('site.Clear Filters')) }}
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto">
            @if ($classes->count() > 0)
            <div>
                <!-- Courses Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 mb-12 md:gap-10"
                    id="coursesGridGroupClasses">

                    @foreach ($classes as $class)
                        <div
                            class="block overflow-hidden bg-white rounded-lg shadow-md transition-all duration-300 course-card hover:shadow-xl">
                            <div class="overflow-hidden relative h-54">
                                <img src="{{ asset('storage/'.$class->imageInfo?->path) }}" alt="Course"
                                    class="object-cover w-full h-full">
                            </div>
                            <div class="p-5">
                                <h3 class="mb-3 text-lg font-bold text-primary">{{ $class->langsAll?->first()->title }}</h3>
                                <p class="mb-4 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($class->langsAll?->first()->headline), 120) }}</p>
                                
                                <div class="flex flex-wrap justify-between gap-4 items-center mb-4">
                                    <div class="flex gap-10 items-center">
                                        <div class="flex gap-2 items-center">
                                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                            <span class="text-lg font-semibold text-gray-800">{{ $class->rating }} / 5</span>
                                        </div>
                                        <span class="text-lg text-gray-400">({{ $class->reviews()->count() }})</span>
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

                                        <span class="text-lg font-medium text-gray-800">{{ $class->views }}</span>
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
                                        <span class="text-md font-medium text-gray-700">{{ Carbon\Carbon::parse($class->dates?->first()->class_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <span class="text-md font-medium text-gray-800">{{ Carbon\Carbon::parse($class->dates?->first()->class_date)->format('h:i A') }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <div class="flex gap-2 items-center">
                                        <img src="{{ $class->tutor?->avatar }}" alt="{{ $class->tutor?->full_name }}"
                                            class="w-10 h-10 rounded-full">
                                        <span class="text-base font-semibold text-gray-800">{{ $class->tutor?->full_name }}</span>
                                    </div>
                                    <span class="text-lg font-bold text-primary">{{ $class->price }} $</span>
                                </div>
                                <a href="{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $class->id]) }}"
                                    class="block text-center mt-4 w-full py-2.5 text-base font-semibold text-primary rounded-lg transition-all duration-300 border border-primary bg-white hover:bg-primary hover:text-white hover:border-0 cursor-pointer">
                                    {{ label_text('global', 'Preview this courses', __('site.Preview this courses')) }}
                                </a>
                            </div>
                        </div>
                    @endforeach



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
                                <span class="text-sm font-medium text-gray-700">{{ label_text('global', 'PER PAGE', __('site.PER PAGE')) }}</span>
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
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left'}}"></i>
                        </button>

                        <div id="pagesNumbers" class="flex gap-1"></div>

                        <button
                            class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
                            data-page="next">
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}}"></i>
                        </button>
                    </div>

                    <!-- فراغ على اليمين للتوازن -->
                    <div class="hidden w-32 md:block"></div>
                </div>
            </div>
            @endif
            @if ($classes->count() == 0)
            <div class="flex flex-col gap-8 justify-center items-center h-full text-center pt-8">
                <div>
                    <svg width="97" height="97" viewBox="0 0 97 97" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_115_1008)">
                            <path d="M20.1992 48.114L22.8051 49.2569L23.9511 46.6584L21.3454 45.5156L20.1992 48.114Z"
                                fill="#1B449C" />
                            <path d="M22.4902 42.9167L25.0962 44.0595L26.2424 41.4611L23.6364 40.3184L22.4902 42.9167Z"
                                fill="#1B449C" />
                            <path
                                d="M18.4109 27.6839L21.7513 31.0147L11.1872 54.9627L0 66.1177V73.5478L10.6675 84.1847H18.1191L19.4214 82.8862L25.7397 89.186L27.7528 87.1788L21.4345 80.8789L25.4604 76.8646L37.6025 88.9714L33.5764 92.9857L29.8067 89.2266L27.7935 91.2338L33.5762 96.9998L41.6282 88.9712L36.5302 83.888L40.2008 80.2281V71.4906L37.9444 69.2406L53.3223 62.4955L56.6571 65.8206L62.6961 59.7988L24.4493 21.6621L18.4109 27.6839ZM16.94 81.3459H11.8467L2.84691 72.3719V67.2932L12.3827 57.785L26.4757 71.8374L16.94 81.3459ZM37.3543 72.6663V79.0524L34.5176 81.8809L27.4735 74.8571L29.3061 73.0299L35.1485 70.4673L37.3543 72.6663ZM56.6575 61.8061L34.4983 39.7105L32.4852 41.718L51.1628 60.3418L28.8065 70.1479L14.0773 55.4611L23.9111 33.1685L30.336 39.5751L32.3491 37.5678L22.4367 27.6841L24.4497 25.6768L58.6706 59.7992L56.6575 61.8061Z"
                                fill="#1B449C" />
                            <path d="M10.4082 63.7692L12.4211 65.7762L14.4343 63.769L12.4213 61.7617L10.4082 63.7692Z"
                                fill="#1B449C" />
                            <path
                                d="M44.186 17.1964H38.4492V20.081H44.1925C49.7719 19.861 49.7994 11.691 44.1773 11.5128L42.7254 11.5114C40.888 11.4785 40.8863 8.73973 42.7269 8.70772H48.4182V5.86914H42.7269C37.1282 6.01691 37.1266 14.1977 42.7229 14.35L44.1747 14.3513C46.0141 14.3805 46.0201 17.1558 44.186 17.1964Z"
                                fill="#7D95C7" />
                            <path
                                d="M51.2598 11.5463V14.4038C51.2598 17.5341 53.814 20.081 56.9534 20.081C60.0928 20.081 62.647 17.5341 62.647 14.4038V11.5463C62.647 8.41596 60.0928 5.86914 56.9534 5.86914C53.814 5.86914 51.2598 8.41596 51.2598 11.5463ZM59.8001 11.5463V14.4038C59.8001 15.9691 58.5232 17.2424 56.9534 17.2424C55.3836 17.2424 54.1067 15.9691 54.1067 14.4038V11.5463C54.1067 9.98103 55.3836 8.70772 56.9534 8.70772C58.5232 8.70772 59.8001 9.98103 59.8001 11.5463Z"
                                fill="#7D95C7" />
                            <path
                                d="M65.4941 14.4038C65.4941 17.5341 68.0483 20.081 71.1878 20.081C74.3272 20.081 76.8814 17.5341 76.8814 14.4038V11.5463C76.8814 8.41596 74.3272 5.86914 71.1878 5.86914C68.0483 5.86914 65.4941 8.41596 65.4941 11.5463V14.4038ZM68.3409 11.5463C68.3409 9.98103 69.6178 8.70772 71.1876 8.70772C72.7574 8.70772 74.0343 9.98103 74.0343 11.5463V14.4038C74.0343 15.9691 72.7574 17.2424 71.1876 17.2424C69.6178 17.2424 68.3409 15.9691 68.3409 14.4038V11.5463Z"
                                fill="#7D95C7" />
                            <path
                                d="M88.2463 20.081H91.1214V5.86914H88.2747V14.8373L82.431 5.86914H79.7148V20.081H82.5616V11.2701L88.2463 20.081Z"
                                fill="#7D95C7" />
                            <path d="M63.5117 0H66.3584V2.83858H63.5117V0Z" fill="#7D95C7" />
                            <path
                                d="M69.2043 0V2.83858H94.153V22.902H69.6514L64.8827 33.5946L60.1138 22.902H35.6125V2.83858H60.6639V0H32.7656V25.7408H58.2641L64.8827 40.5803L71.501 25.7408H96.9997V0L69.2043 0Z"
                                fill="#7D95C7" />
                            <path d="M79.6875 30.7141L86.3049 35.6865L88.0181 33.4197L81.4007 28.4473L79.6875 30.7141Z"
                                fill="#7D95C7" />
                            <path d="M71.1777 38.931L76.1645 45.5296L78.4381 43.8213L73.4516 37.2227L71.1777 38.931Z"
                                fill="#7D95C7" />
                            <path d="M75.7617 35.0045L81.6207 40.8467L83.6339 38.8394L77.7748 32.9971L75.7617 35.0045Z"
                                fill="#7D95C7" />
                        </g>
                        <defs>
                            <clipPath id="clip0_115_1008">
                                <rect width="97" height="97" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div>
                    <p>
                        {{ __('Content will be available soon.') }}
                    </p>
                    <a href="{{ route('home') }}" class="flex gap-2 justify-center items-center mt-7 text-[#0553FC]">
                        <span class="underline">{{ __('Return to Homepage') }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('front/assets/js/online_group_classes.js') }}"></script>
    @endpush
</x-front-layout>
