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
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">Online Group classes</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        {{ $group_class->name }}
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">{{ $group_class->name }}</h2>
        </div>
    </section>

    <!-- Course Detail Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Left Column: Main Content -->
                <div class="lg:col-span-2">
                    <!-- Course Title & Description -->
                    <div class="mb-6">
                        <h1 class="mb-3 text-lg font-bold text-gray-900 leading-tight">{{ $group_class->langsAll->first()->title }}
                        </h1>
                        <p class="mb-4 text-md text-gray-600">{!! $group_class->langsAll->first()->about !!}</p>
                        <p class="mb-4 text-md text-gray-600">{!! $group_class->langsAll->first()->headline !!}</p>

                        <!-- Rating -->
                        <div class="flex gap-2 items-center mb-4">
                            <div class="flex gap-1 items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $group_class->rating)
                                        <i class="text-yellow-400 fas fa-star text-sm"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-star text-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-md font-medium text-gray-900 me-6">{{ $group_class->rating }} / 5</span>
                            <span class="text-md text-primary">{{ $group_class->reviews->count() }} reviews</span>
                        </div>
                    </div>

                    <!-- Course Image -->
                    @if ($group_class->imageInfo?->path)
                        <div class="overflow-hidden mb-6 bg-white rounded-md border border-gray-400 shadow-md">
                            <img src="{{ asset('storage/'.$group_class->imageInfo->path) }}" alt="Course preview"
                                class="object-cover w-full h-96.5">
                        </div>
                    @endif

                    <!-- Tags/Badges -->
                    <div class="flex flex-wrap gap-2 mb-8">
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">Level
                            : {{ $group_class->level->name }}</span>
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">duration :  Not available
                            week</span>
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">Classes : {{ $group_class->classes }}</span>
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">teacher : {{ $group_class->tutor?->name }}</span>
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">Language : Not available</span>
                    </div>

                    <!-- What you'll learn -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        {{-- <h2 class="mb-4 text-lg font-bold text-gray-900">What you'll learn</h2>
                        <ul class="space-y-2">
                            <li class="flex gap-2 items-start text-sm text-black">
                                <span class="mt-1.5 w-1.5 h-1.5 bg-black rounded-full flex-shrink-0"></span>
                                <span>Plan an essay and draft key points</span>
                            </li>
                            <li class="flex gap-2 items-start text-sm text-black">
                                <span class="mt-1.5 w-1.5 h-1.5 bg-black rounded-full flex-shrink-0"></span>
                                <span>Write clearly with effective style</span>
                            </li>
                            <li class="flex gap-2 items-start text-sm text-black">
                                <span class="mt-1.5 w-1.5 h-1.5 bg-black rounded-full flex-shrink-0"></span>
                                <span>Revise technique, personalize feedback.</span>
                            </li>
                        </ul> --}}
                        {!! $group_class->langsAll->first()->information !!}
                    </div>

                    <!-- Instructor Section -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        <div class="flex gap-4 items-center mb-4">
                            <img src="{{ asset('storage/'.$group_class->tutor?->avatar) }}" alt="{{ $group_class->tutor?->name }}"
                                class="object-cover w-21 h-21 rounded-full">
                            <div>
                                <h3 class="text-sm font-semibold text-black">{{ $group_class->tutor?->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $group_class->tutor?->descriptions?->first()->specialization }}</p>
                            </div>
                        </div>

                        @if ($group_class->imageInfo?->path)
                        <div class="overflow-hidden rounded-md border border-gray-200">
                            <img src="{{ asset('storage/'.$group_class->imageInfo?->path) }}" alt="{{ $group_class->name }}"
                                class="object-cover w-full h-56 md:h-93">
                        </div>
                        @endif

                        <div class="flex gap-3 mt-4">
                            <button
                                class="cursor-pointer px-6 py-2 text-sm text-black bg-white rounded-md border border-gray-400 transition-all duration-300 hover:bg-primary hover:text-white hover:border-primary">
                                View profile
                            </button>
                            <button
                                class="cursor-pointer px-6 py-2 text-sm text-white rounded-md bg-primary transition-all duration-300 hover:bg-primary-800">
                                Message tutor
                            </button>
                        </div>

                    </div>

                    <!-- FAQ Section -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">FAQ</h2>
                        <div class="space-y-3">
                            <!-- FAQ Item 1 -->
                            <div class="faq-item bg-gray-50 rounded-md border border-gray-200 overflow-hidden">
                                <button
                                    class="faq-question flex justify-between items-center p-4 w-full text-left transition-colors hover:bg-gray-100">
                                    <span class="text-sm font-medium text-gray-900">Are sessions recorded ?</span>
                                    <i class="text-sm transition-transform fas fa-chevron-down text-primary-600"></i>
                                </button>
                                <div class="faq-answer px-4 pb-4 text-sm text-gray-600 leading-relaxed"
                                    style="display: none;">
                                    Sessions are live; a short recap may be shared afterwards when possible.
                                </div>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div class="faq-item bg-gray-50 rounded-md border border-gray-200 overflow-hidden">
                                <button
                                    class="faq-question flex justify-between items-center p-4 w-full text-left transition-colors hover:bg-gray-100">
                                    <span class="text-sm font-medium text-gray-900">Can I cancel ?</span>
                                    <i class="text-sm transition-transform fas fa-chevron-down text-primary-600"></i>
                                </button>
                                <div class="faq-answer px-4 pb-4 text-sm text-gray-600 leading-relaxed"
                                    style="display: none;">
                                    Free cancellation up to 12 hours before the session.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- You might also like -->
                    <div class="mb-8 w-4/5">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">You might also like</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach($suggestions as $suggestion)
                            <div class="bg-white rounded-md border border-gray-200 shadow-sm overflow-hidden">
                                <div class="p-2.5 w-full">
                                    <img src="{{ asset('storage/'.$suggestion->imageInfo?->path) }}"
                                    alt="Essay Basics" class="object-cover w-full h-43 rounded-sm">
                                </div>
                                <div class="pb-4 px-2.5">
                                    <div class="flex justify-between items-center my-2">
                                        <h3 class="text-lg text-black">{{ $suggestion->langsAll?->first()->title }}</h3>
                                        <p class="text-lg text-black font-light">{{ $suggestion->price }} USD</p>
                                    </div>
                                    <a href="{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $suggestion->id]) }}"
                                        class="block text-center cursor-pointer w-full px-6 py-2 text-sm text-primary bg-white rounded-md border border-gray-400 transition-all duration-300 hover:bg-primary hover:text-white hover:border-primary">
                                        View details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Booking Card -->
                <div class="lg:col-span-1">
                    @if($exams->count() > 0)
                    <a href="{{ route('site.take_exam', ['locale' => app()->getLocale(), 'group_class_id' => $group_class->id]) }}"
                        class="block text-center px-6 py-3 mb-6 text-base font-medium text-white rounded-md bg-primary-600 transition-colors hover:bg-primary-700">
                        Take Exam
                    </a>
                    @endif
                    <div class="bg-white rounded-md border border-gray-200 shadow-sm p-6 sticky top-31">
                        <div class="flex justify-between items-center mb-3">
                            <h2 class="text-lg font-bold text-black">Booking</h2>
                            <div class="relative group">
                                <button id="fav-btn" class="cursor-pointer transition-all duration-300 text-gray-300 flex items-center">
                                    <i class="fa-regular fa-heart not-faved"></i>
                                    <i class="fa-solid fa-heart text-red-600 faved !hidden"></i>
                                </button>
                                <div class="absolute left-1/2 -translate-x-1/2 mt-2 bg-gray-900 text-white text-xs px-3 py-2 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none whitespace-nowrap">
                                    Add to favorites
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('site.group_class_order', ['locale' => app()->getLocale(), 'id' => $group_class->id]) }}" method="post">
                            @csrf

                            <!-- Price -->
                            <div class="flex justify-between items-center rounded-md p-3 mb-4 border border-gray-300 bg-[#1B449C03]">
                                <span class="text-[15px] font-semibold text-black">Price</span>
                                <span class="text-[15px] font-semibold text-black">{{ $group_class->price }} USD</span>
                            </div>

                            <!-- Date/Time Selection -->
                            <div class="mb-4 space-y-3">
                                <input type="hidden" name="group_class_id" value="{{ $group_class->id }}">
                                @foreach($group_class->dates as $date)
                                    <div class="flex justify-between items-center p-3 rounded-md border border-gray-200 cursor-pointer transition-all hover:border-primary-600 hover:bg-primary-50">
                                        <span class="text-sm text-black">{{ Carbon\Carbon::parse($date->class_date)->format('l') }} , {{ Carbon\Carbon::parse($date->class_date)->format('M . d , Y') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($date->class_date)->format('h:i A') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @guest
                            <button type="button" data-open="#loginModal"
                                class="px-6 py-3 mb-3 w-full text-base font-medium text-white rounded-md bg-primary-600 transition-colors hover:bg-primary-700">
                                Login to Book
                            </button>
                            @endguest

                            @auth
                            <!-- Book Now Button -->
                            <button type="submit"
                                class="px-6 py-3 mb-3 w-full text-base font-medium text-white rounded-md bg-primary-600 transition-colors hover:bg-primary-700">
                                Book Now
                            </button>                                    
                            @endauth
                            
                        </form>
                        <p class="text-xs text-center text-gray-500">
                            Secure payment — Free cancellation up to 12h
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="{{ asset('front/assets/js/course_detail.js') }}"></script>
    @endpush

</x-front-layout>


