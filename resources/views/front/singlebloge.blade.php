<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <!-- Home -->
                    <li>
                        <a href="{{ route('home') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">Home</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    <li>
                        <a href="{{ route('site.blog') }}"
                            class="transition-colors text-primary-600 hover:text-primary-700">Blog</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    <!-- Current Page -->
                    <li>
                        <span class="text-gray-900">{{ $blog->langs?->first()?->title }}</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">{{ $blog->langs?->first()?->title }}</h2>

            <div
                class="flex flex-wrap lg:flex-nowrap relative gap-10 whitespace-nowrap py-4 mt-3 border-t border-[#E5E7EB]">
                <div class="flex gap-1 items-center">
                    <i class="text-lg fas fa-clock text-primary"></i>
                    <span class="text-sm text-gray-400">{{ $blog->langs?->first()?->title }}</span>
                </div>
                <div class="flex gap-1 items-center">
                    <i class="text-lg fas fa-clock text-primary"></i>
                    <span class="text-sm text-gray-400">Category : {{ $blog->category->langs?->first()?->title }}</span>
                </div>
                <div class="flex gap-1 items-center">
                    <i class="text-lg fas fa-user text-primary"></i>
                    <span class="text-sm text-gray-400">Posted by : {{ $blog->users->name ?? '' }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-12 md:mb-12 md:gap-20 md:grid-cols-3" id="coursesGridBlogs">
                <div class="flex flex-col gap-4 items-start text-justify md:col-span-2 md:text-start">
                    <p>
                        {!! $blog->langs?->first()?->description !!}
                    </p>
                </div>
                <div class="md:px-6 md:col-span-1">
                    <h3 class="mb-4 text-2xl font-bold">Explore Related Courses</h3>
                    <div class="grid grid-cols-1 gap-7 mb-12 md:gap-5" id="coursesGridBlog">
                        @foreach ($blogs as $blog)
                            <div
                                class="block overflow-hidden bg-white rounded-md shadow-md transition-all duration-300 course-blogs-card hover:shadow-lg hover:scale-102">
                                <div class="overflow-hidden relative h-67 group">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->langs?->first()?->title }}"
                                        class="object-cover w-full h-full">
                                    <div class="absolute right-2 top-4">
                                        <span class="px-4 py-2 text-base text-white rounded-xl bg-primary">Free
                                            Learn</span>
                                    </div>
                                    <div
                                        class="absolute top-0 left-0 w-full h-full opacity-0 transition-all duration-300 bg-black/50 group-hover:opacity-100">
                                        <div class="flex justify-center items-center h-full">
                                            <a href="{{ route('site.showBlog', $blog->langs?->first()?->slug) }}"
                                                class="px-8 py-4 text-lg text-white rounded-lg bg-primary">Load
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h3 class="mb-2 font-semibold text-black text-md">{{ $blog->langs?->first()?->title }}</h3>
                                    <p class="my-6 text-[13px] text-gray-700">
                                        {{ \Illuminate\Support\Str::limit($blog->langs?->first()?->description, 120) }}
                                    </p>
                                    <div class="py-2 mt-3 border-t border-[#E5E7EB]">
                                        <div class="flex justify-between items-center transition-all duration-300">
                                            <div class="flex gap-1 items-center">
                                                <i>
                                                    <svg width="18" height="19" viewBox="0 0 18 19"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M18 13.143V14.5C18 15.3139 17.619 16.0099 17.0899 16.557C16.567 17.099 15.857 17.541 15.0581 17.891C13.456 18.591 11.314 19 9 19C6.686 19 4.544 18.592 2.942 17.891C2.14299 17.541 1.43301 17.099 0.91 16.557C0.425008 16.057 0.064992 15.429 0.00799999 14.701L0 14.5V13.143C0.465008 13.407 0.962 13.641 1.494 13.8379C3.524 14.589 6.17901 15.008 9 15.008C11.821 15.008 14.476 14.589 16.5059 13.8379C16.905 13.6899 17.284 13.5219 17.645 13.335L18 13.143ZM0 7.64299C0.465008 7.90699 0.962 8.14099 1.494 8.338C3.524 9.08899 6.17901 9.508 9 9.508C11.821 9.508 14.476 9.08899 16.5059 8.338C17.0221 8.14749 17.5218 7.91506 18 7.64299V10.748C17.336 11.2605 16.5982 11.6698 15.812 11.962C14.057 12.612 11.648 13.009 9 13.009C6.35299 13.009 3.944 12.612 2.188 11.962C1.4017 11.6698 0.664032 11.2605 0 10.748V7.64299ZM9 0C11.314 0 13.456 0.408 15.0581 1.10901C15.857 1.45901 16.567 1.90099 17.0899 2.44299C17.575 2.94299 17.935 3.57099 17.992 4.29899L18 4.5V5.248C17.336 5.76058 16.5984 6.16986 15.812 6.462C14.057 7.112 11.648 7.50899 9 7.50899C6.35299 7.50899 3.944 7.112 2.188 6.462C1.51184 6.2112 0.871408 5.87299 0.282992 5.456L0 5.248V4.5C0 3.686 0.381008 2.99 0.91 2.44299C1.43301 1.90099 2.14299 1.45901 2.942 1.10901C4.544 0.409008 6.686 0 9 0Z"
                                                            fill="#1B449C" />
                                                    </svg>
                                                </i>
                                                <span class="text-sm text-gray-400">level :
                                                    {{ $blog->lessons }}</span>
                                            </div>

                                            <div class="flex gap-1 items-center">
                                                <i class="text-lg fas fa-clock text-primary"></i>
                                                <span
                                                    class="text-sm font-bold text-gray-400">{{ $blog->date }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="flex gap-1 justify-center items-center" id="paginationBlogs">
                            <button
                                class="flex justify-center items-center w-8 h-8 rounded-full transition-all duration-200 cursor-pointer text-primary hover:text-white hover:bg-primary"
                                data-page="prev">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <div id="pagesNumbers" class="flex gap-1"></div>

                            <button
                                class="flex justify-center items-center w-8 h-8 rounded-full transition-all duration-200 cursor-pointer text-primary hover:text-white hover:bg-primary"
                                data-page="next">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-front-layout>
