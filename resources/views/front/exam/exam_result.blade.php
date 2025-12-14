<x-front-layout>


    <!-- Content - Exam Results -->
    <section class="pt-8 pb-32 mt-[120px]" data-exam-id="{{ $attempt->exam_id }}">
        <div class="container px-4 mx-auto lg:px-20">

            <!-- Top Section: Result Card + Rapid Diagnosis -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Left: Main Result Card -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h1 class="text-2xl font-bold text-black mb-4">
                        @if($attempt->result == 'passed')
                            Congratulations! You passed the exam.
                        @else
                            Unfortunately, you did not pass the entrance exam.
                        @endif
                    </h1>

                    <!-- Student Info & Timer Row -->
                    <div class="flex flex-wrap justify-between items-start mb-6 gap-4 w-3/4">
                        <div class="text-md text-black space-y-1">
                            <div>Student: {{ $attempt->student?->profile?->first_name }}
                                {{ $attempt->student?->profile?->last_name }} Attempt: {{ $attempt->attempt_no }}</div>
                            <div>Your score <span class="font-bold">{{ $attempt->success_rate }}%</span> – Passing
                                threshold: <span class="font-bold">{{ $attempt->exam->pass_percentage }}%</span></div>
                        </div>
                        <div class="text-lg text-black bg-[#1B449C17] px-2 py-0.5 rounded-lg">
                            ELAPSED TIME: <span
                                class="font-semibold">{{ gmdate('i:s', $attempt->finished_at->diffInSeconds($attempt->started_at)) }}</span>
                        </div>
                    </div>

                    <!-- Score Circle & Status -->
                    <div class="flex flex-wrap items-center gap-6 mb-6">
                        <div
                            class="flex items-center justify-center w-19.5 h-19.5 {{ $attempt->result == 'passed' ? 'bg-green-50 text-green-600' : 'bg-[#D70B0F0F] text-[#D70B0F]' }} rounded-full shadow-lg flex-shrink-0">
                            <div class="text-center">
                                <div class="text-xl font-bold leading-none">{{ $attempt->success_rate }}</div>
                                <div class="text-sm">%</div>
                            </div>
                        </div>
                        <div>
                            <div
                                class="text-[19px] font-bold {{ $attempt->result == 'passed' ? 'text-green-600' : 'text-[#D70B0F]' }} mb-1">
                                @if($attempt->result == 'passed')
                                    Eligible For The Current Level
                                @else
                                    Not Eligible For The Current Level
                                @endif
                            </div>
                            <div class="text-sm text-black">Correct answers: <span
                                    class="font-semibold">{{ $attempt->answers->where('is_correct', true)->count() }}/{{ $attempt->answers->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- progress bar -->
                    <div class="w-1/2 relative h-1">
                        <span class="w-full h-1 bg-gray-300 rounded-full block absolute top-0 left-0"></span>
                        <span
                            class="h-1 {{ $attempt->result == 'passed' ? 'bg-green-500' : 'bg-red-500' }} rounded-full block absolute top-0 left-0 transition-all duration-300"
                            style="width: {{ $attempt->success_rate }}%"></span>
                    </div>
                </div>

                <!-- Right: Rapid Diagnosis Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 px-6 py-10">
                    <h2 class="text-xl font-bold text-black mb-4">Rapid diagnosis</h2>
                    <ul class="space-y-4 text-md text-black list-disc list-inside ps-2">
                        <li>Sections that need strengthening: <span class="font-bold">Basics, Data Structures</span>
                        </li>
                        <li>We recommend introductory courses before proceeding to this level.</li>
                    </ul>
                </div>
            </div>

            <!-- Suggested Courses Section -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-black mb-6">Suggested courses (lower level)</h2>

                <!-- Course Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($suggestions as $suggestion)
                        <div
                            class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105">
                            <div class="overflow-hidden relative h-46.5 rounded-sm">
                                <img src="{{ asset('storage/' . $suggestion->imageInfo?->path) }}" alt="JavaScript Essentials"
                                    class="object-cover w-full h-full">
                            </div>
                            <div class="py-2">
                                <h3 class="text-md font-bold text-black mb-2">{{ $suggestion->langsAll?->first()->title }}
                                </h3>
                                <p class="text-sm text-black font-light mb-4">Level: {{ $suggestion->level?->name }} •
                                    Hours: {{ round(($suggestion->total_classes_length ?? 0 / 60), 2) }}</p>
                                <div class="flex gap-3">
                                    <a href="{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $suggestion->id]) }}"
                                        class="block px-8 py-2 text-lg text-black bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">Details</a>
                                    <a href="{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $suggestion->id]) }}"
                                        class="block px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Registration</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- <!-- Course Card 2-->
                    <div
                        class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105">
                        <div class="overflow-hidden relative h-46.5 rounded-sm">
                            <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop"
                                alt="JavaScript Essentials" class="object-cover w-full h-full">
                        </div>
                        <div class="py-2">
                            <h3 class="text-md font-bold text-black mb-2">JavaScript Essentials</h3>
                            <p class="text-sm text-black font-light mb-4">Level: Beginner • Hours: 10</p>
                            <div class="flex gap-3">
                                <button
                                    class="px-8 py-2 text-lg text-black bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">Details</button>
                                <button
                                    class="px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Registration</button>
                            </div>
                        </div>
                    </div>
                    <!-- Course Card 2-->
                    <div
                        class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105">
                        <div class="overflow-hidden relative h-46.5 rounded-sm">
                            <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop"
                                alt="JavaScript Essentials" class="object-cover w-full h-full">
                        </div>
                        <div class="py-2">
                            <h3 class="text-md font-bold text-black mb-2">JavaScript Essentials</h3>
                            <p class="text-sm text-black font-light mb-4">Level: Beginner • Hours: 10</p>
                            <div class="flex gap-3">
                                <button
                                    class="px-8 py-2 text-lg text-black bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">Details</button>
                                <button
                                    class="px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Registration</button>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- Action Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                    <!-- Retest Card -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-black mb-3">Retest</h3>
                        <p class="text-md text-black mb-4">Review the incorrect questions and then try again.</p>
                        <a href="{{ route('site.take_exam', ['locale' => app()->getLocale(), 'group_class_id' => $attempt->exam?->groupClass?->id]) }}"
                            class="px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Replay
                            Now</a>
                    </div>

                    <!-- Review Wrong Questions Card -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-black mb-3">Review wrong questions</h3>
                        <p class="text-md text-black mb-4">A quick look at the mistakes and omissions.</p>
                        <a href="{{ route('site.take_exam', ['locale' => app()->getLocale(), 'group_class_id' => $attempt->exam?->groupClass?->id]) }}"
                            class="px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Start
                            Reviewing</a>
                    </div>

                    <!-- Book Now Card -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-black mb-3">Book now</h3>
                        <p class="text-md text-black mb-4">Book 15 minutes to determine the most suitable route.</p>
                        <button
                        class="px-4 py-2 text-lg text-white bg-primary rounded-lg hover:bg-primary-700 hover:scale-105 transition-all duration-200">Replay
                        Now</button>

                    </div>
                </div>
            </div>

            <!-- Registration Disclaimer Section -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-black mb-4">Registration is at my own risk</h2>
                <p class="text-md text-black mb-6">You can still enroll in this course even if you haven't passed the
                    entrance exam. Please check the box before proceeding.</p>

                <div class="flex items-start gap-3 mb-6">
                    <input type="checkbox" id="risk-acknowledgement"
                        class="w-5 h-5 mt-1 text-primary border-2 border-gray-300 rounded-full focus:ring-primary focus:ring-2 cursor-pointer appearance-none checked:bg-primary checked:border-primary relative checked:after:content-['✓'] checked:after:absolute checked:after:text-white checked:after:text-xs checked:after:left-1/2 checked:after:top-1/2 checked:after:-translate-x-1/2 checked:after:-translate-y-1/2">
                    <label for="risk-acknowledgement" class="text-md text-black cursor-pointer">I confirm that I wish
                        to register despite not passing the test.</label>
                </div>


                <form
                    action="{{ route('site.group_class_order', ['locale' => app()->getLocale(), 'id' => $attempt->exam->groupClass->id]) }}"
                    method="post">
                    @csrf
                    <button id="registration-risk-btn" disabled type="submit"
                        class="px-6 py-3 text-base font-semibold text-white bg-primary rounded-lg opacity-50 cursor-not-allowed transition-all duration-200">
                        Registration Is At My Own Risk
                    </button>
                </form>
            </div>

        </div>
    </section>


    @push('scripts')
        <script src="{{ asset('front/assets/js/exam_result.js') }}"></script>
    @endpush
</x-front-layout>