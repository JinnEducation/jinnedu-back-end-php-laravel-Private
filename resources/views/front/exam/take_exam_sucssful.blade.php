<x-front-layout>

    <!-- Content - Exam Results -->
    <section class="pt-8 pb-32 mt-[120px]" data-exam-id="{{ $attempt->exam_id }}">
        <div class="container px-4 mx-auto lg:px-20">

            <!-- Summary Card -->
            <div class="mb-6 bg-white rounded-lg shadow-lg border border-gray-200 p-8 transition-all duration-500 opacity-0 translate-y-4"
                id="summary-card">
                <div class="flex flex-col justify-center items-start">
                    <h1 class="text-3xl font-bold text-black mb-3">{{ $attempt->exam->langsAll()->where('language_id', $lang_id)->first()?->title ?? 'Group Class Level Exam' }} - {{ $attempt->exam->groupClass->langsAll()->where('language_id', $lang_id)->first()?->title ?? '-' }}</h1>

                    <div class="flex justify-between items-center w-full lg:w-1/2 mb-4">
                        <div class="flex flex-wrap items-center gap-3 text-sm text-black">
                            <span>Student: {{ $attempt->student->profile->first_name }}  {{ $attempt->student->profile->last_name }}</span>
                            <span>Attempt: {{ $attempt->attempt_no }}</span>
                        </div>
                        <!-- Right Section: Timer -->
                        <span class="text-md text-black bg-[#1B449C17] px-1 py-0.5 rounded-lg">
                            ELAPSED TIME: <span class="font-bold">{{ gmdate('i:s', $attempt->finished_at->diffInSeconds($attempt->started_at)) }}</span>
                        </span>
                    </div>

                    <!-- Status Badge & Score Info -->
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-3">
                            <!-- Circular Score Badge -->
                            <div
                                class="flex items-center justify-center w-24 h-24 {{ $attempt->result == 'passed' ? 'bg-primary text-white' : 'bg-[#D70B0F0F] text-[#D70B0F]' }} rounded-full shadow-lg">
                                <div class="text-center">
                                    <div class="text-xl font-bold leading-none">{{ $attempt->success_rate }}</div>
                                    <div class="text-sm">/100</div>
                                </div>
                            </div>

                            <!-- Status & Details -->
                            <div>
                                <div class="text-lg">
                                    <span class="{{ $attempt->result == 'passed' ? 'text-primary' : 'text-[#D70B0F]' }} font-bold">{{ $attempt->result == 'passed' ? 'Successful' : 'Not Passed' }}</span>
                                    <div class="text-black">Passing Score: <span class="font-semibold">{{ $attempt->exam->pass_percentage }}%</span></div>
                                    <div class="text-black">Correct Answers: <span class="font-semibold">{{ $attempt->answers->where('is_correct', true)->count() }} / {{ $attempt->answers->count() }}</span></div>
                                </div>
                            </div>
                        </div>
                        <!-- progress bar -->
                        <div class="w-full relative h-1">
                            <span class="w-full h-1 bg-gray-300 rounded-full block absolute top-0 left-0"></span>
                            <span class="h-1 bg-primary rounded-full block absolute top-0 left-0 transition-all duration-300" style="width: {{ $attempt->success_rate }}%"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Questions Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 transition-all duration-500 opacity-0 translate-y-4"
                id="review-card">

                <!-- Card Header -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2 pb-4">
                    <h2 class="text-lg font-bold text-black">Reviewing the questions</h2>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 md:gap-40">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            Answers
                        </button>
                        <button id="download-pdf-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            Download PDF
                        </button>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="space-y-4 px-2">
                    @foreach($attempt->answers as $attemptAnswer)
                        @php
                            $question = $attemptAnswer->question;
                            $selectedAnswer = $attemptAnswer->answer;
                            $correctAnswer = $question->answers->where('is_correct', true)->first();
                            $questionLang = $question->langsAll()->where('language_id', $lang_id)->first();
                            $selectedAnswerLang = $selectedAnswer ? $selectedAnswer->langsAll()->where('language_id', $lang_id)->first() : null;
                            $correctAnswerLang = $correctAnswer ? $correctAnswer->langsAll()->where('language_id', $lang_id)->first() : null;
                        @endphp

                        <div class="flex flex-col md:flex-row gap-4 py-4 {{ $loop->last ? '' : 'border-b-2' }} border-gray-300 transition-all duration-500 hover:shadow-md opacity-0 translate-x-8">
                            <div class="flex items-center gap-3 flex-1 w-2/3">
                                <span class="w-21 inline-block px-3 py-1 text-md text-center {{ $attemptAnswer->is_correct ? 'text-green-700' : ($selectedAnswer ? 'text-red-700' : 'text-black') }} bg-[#EAEEF6] rounded whitespace-nowrap">
                                    {{ $attemptAnswer->is_correct ? 'Correct' : ($selectedAnswer ? 'Wrong' : 'Left') }}
                                </span>
                                <div class="flex-1">
                                    <span class="text-primary text-md">Question {{ $question->question_no }}:</span>
                                    <span class="text-black text-md">{{ $questionLang?->title ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="md:text-start pl-14 rtl:pr-14 text-lg w-1/3 text-black">
                                <div class="mb-2">Your answer: <span class="font-bold">{{ $selectedAnswerLang?->title ?? '—' }}</span></div>
                                @if(!$attemptAnswer->is_correct && $correctAnswerLang)
                                    <div>The correct answer: <span class="font-bold">{{ $correctAnswerLang->title }}</span></div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div>
                <!-- Bottom Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-start items-center gap-4 mt-6">
                    <button id="share-result-btn"
                        class="w-full sm:w-auto px-6 py-3 text-lg font-medium text-primary bg-white border-2 border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-0.5">
                        Share The Result
                    </button>
                    @if(Auth::check() && Auth::user()->id == $attempt->student_id)
                    <button id="retake-test-btn"
                        class="w-full sm:w-auto px-6 py-3 text-lg font-medium text-primary bg-white border-2 border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-0.5">
                        Retake The Test
                    </button>
                    @endif
                    <button id="class-group-registration-btn"
                        class="w-full sm:w-auto px-6 py-3 text-lg font-bold text-white bg-primary border-2 border-primary rounded-lg hover:bg-primary-700 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-0.5">
                        Class Group Registration
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- Toast Notification -->
    <div id="share-toast"
        class="fixed top-24 right-6 rtl:left-6 rtl:right-auto bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg opacity-0 transform translate-x-full rtl:-translate-x-full transition-all duration-300 z-50">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>Link copied to clipboard!</span>
        </div>
    </div>

    @push('scripts')
        <script>
            const take_exam_url = "{{ route('site.take_exam', ['locale' => app()->getLocale(), 'group_class_id' => $attempt->exam->groupClass->id]) }}";
            const group_class_url = "{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $attempt->exam->groupClass->id]) }}";
        </script>
        <script src="{{ asset('front/assets/js/exam_successful.js') }}"></script>
    @endpush
</x-front-layout>
