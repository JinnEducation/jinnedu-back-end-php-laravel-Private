<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <div class="container z-10 px-4 mx-auto w-full">

            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <div class="text-sm font-light text-gray-600 leading-relaxed">
                    <span>
                        <a href="index.html" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global','Home', __('site.Home')) }}
                        </a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global','Classes', __('site.Classes')) }}
                        </a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">
                            {{ label_text('global','Online Group classes', __('site.Online Group classes')) }}
                        </a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        {{ $group_class->name }}
                    </span>
                </div>
            </nav>

            <h2 class="mb-6 text-3xl font-bold">{{ $group_class->name }}</h2>
        </div>
    </section>

    <!-- Course Detail Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

                <!-- Left Column -->
                <div class="lg:col-span-2">

                    <!-- Course Info -->
                    <div class="mb-6">
                        <h1 class="mb-3 text-lg font-bold text-gray-900 leading-tight">
                            {{ $group_class->langsAll->first()->title }}
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

                            <span class="text-md font-medium text-gray-900 me-6">
                                {{ $group_class->rating }} / 5
                            </span>

                            <span class="text-md text-primary">
                                {{ $group_class->reviews->count() }}
                                {{ label_text('global','reviews', __('site.reviews')) }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Image -->
                    @if ($group_class->imageInfo?->path)
                        <div class="overflow-hidden mb-6 bg-white rounded-md border border-gray-400 shadow-md">
                            <img src="{{ asset('storage/'.$group_class->imageInfo->path) }}"
                                 alt="{{ label_text('global','Course preview', __('site.Course preview')) }}"
                                 class="object-cover w-full h-96.5">
                        </div>
                    @endif

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-8">
                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">
                            {{ label_text('global','Level', __('site.Level')) }} :
                            {{ $group_class->level->name }}
                        </span>

                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">
                            {{ label_text('global','duration', __('site.duration')) }} :
                            {{ label_text('global','Not available', __('site.Not available')) }}
                        </span>

                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">
                            {{ label_text('global','Classes', __('site.Classes')) }} :
                            {{ $group_class->classes }}
                        </span>

                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">
                            {{ label_text('global','teacher', __('site.teacher')) }} :
                            {{ $group_class->tutor?->name }}
                        </span>

                        <span class="px-4 py-2 text-sm text-black border border-gray-400 rounded-md">
                            {{ label_text('global','Language', __('site.Language')) }} :
                            {{ label_text('global','Not available', __('site.Not available')) }}
                        </span>
                    </div>

                    <!-- Information -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        {!! $group_class->langsAll->first()->information !!}
                    </div>

                    <!-- Instructor -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        <div class="flex gap-4 items-center mb-4">
                            <img src="{{ asset('storage/'.$group_class->tutor?->avatar) }}"
                                 alt="{{ $group_class->tutor?->name }}"
                                 class="object-cover w-21 h-21 rounded-full">

                            <div>
                                <h3 class="text-sm font-semibold text-black">
                                    {{ $group_class->tutor?->name }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $group_class->tutor?->descriptions?->first()->specialization }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-4">
                            <button class="cursor-pointer px-6 py-2 text-sm text-black bg-white rounded-md border border-gray-400 hover:bg-primary hover:text-white">
                                {{ label_text('global','View profile', __('site.View profile')) }}
                            </button>

                            <button class="cursor-pointer px-6 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-800">
                                {{ label_text('global','Message tutor', __('site.Message tutor')) }}
                            </button>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="mb-8 rounded-md border border-gray-300 p-6">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">
                            {{ label_text('global','FAQ', __('site.FAQ')) }}
                        </h2>
                    </div>

                    <!-- Suggestions -->
                    <div class="mb-8 w-4/5">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">
                            {{ label_text('global','You might also like', __('site.You might also like')) }}
                        </h2>
                    </div>

                </div>

                <!-- Booking -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-md border border-gray-200 shadow-sm p-6 sticky top-31">

                        <h2 class="text-lg font-bold text-black mb-3">
                            {{ label_text('global','Booking', __('site.Booking')) }}
                        </h2>

                        <div class="flex justify-between items-center rounded-md p-3 mb-4 border border-gray-300 bg-[#1B449C03]">
                            <span class="font-semibold">
                                {{ label_text('global','Price', __('site.Price')) }}
                            </span>
                            <span class="font-semibold">
                                {{ $group_class->price }} USD
                            </span>
                        </div>

                        @guest
                            <button type="button" class="px-6 py-3 w-full text-white bg-primary-600 rounded-md">
                                {{ label_text('global','Login to Book', __('site.Login to Book')) }}
                            </button>
                        @endguest

                        @auth
                            <button type="submit" class="px-6 py-3 w-full text-white bg-primary-600 rounded-md">
                                {{ label_text('global','Book Now', __('site.Book Now')) }}
                            </button>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-front-layout>
