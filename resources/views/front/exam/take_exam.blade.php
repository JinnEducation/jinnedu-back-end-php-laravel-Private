<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5 md:py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <div class="text-sm font-light text-gray-600 leading-relaxed">
                    <span>
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                            class="text-primary-600 hover:text-primary-700">Home</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="{{ route('site.online_group_classes', ['locale' => app()->getLocale()]) }}"
                            class="text-primary-600 hover:text-primary-700">Classes</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="{{ route('site.online_group_classes', ['locale' => app()->getLocale()]) }}"
                            class="text-primary-600 hover:text-primary-700">Online Group classes</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="{{ route('site.group_class_details', ['locale' => app()->getLocale(), 'id' => $group_class_id]) }}"
                            class="text-primary-600 hover:text-primary-700">{{ $group_class->name }}</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-gray-900 break-words">
                        Take Exam
                    </span>
                </div>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-6 text-3xl font-bold">Take Exam</h2>
        </div>
    </section>

    <!-- Content -->
    <section class="pt-8 pb-32">
        <div class="container px-4 mx-auto lg:px-20">
            <div class="mb-8">
                <h2 class="mb-2 text-lg font-semibold text-primary">
                    {{ $group_class->langsAll()->where('language_id', $lang_id)->first()?->title ?? '-' }}
                </h2>
                <h2 class="mb-2 text-md font-semibold text-black">
                    {{ $exam->langsAll()->where('language_id', $lang_id)->first()?->title ?? 'Group Class Level Exam' }}
                </h2>
                <p class="font-light w-3/4 text-black text-justify md:text-left md:rtl:text-right">
                    {{ $exam->langsAll()->where('language_id', $lang_id)->first()?->instructions ?? 'Hi student, for every question below, please, choose the correct answer and make sure every answer below is your final choice before you move on to the next page, good luck' }}
                </p>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Exam Form -->
            <form id="exam-form" action="{{ route('site.take_exam_store', ['locale' => app()->getLocale(), 'group_class_id' => $group_class_id]) }}" method="post" data-exam-id="{{ $exam->id }}" data-time="{{ $exam->duration_minutes * 60 }}">
                @csrf
                <div class="flex justify-between items-center gap-2 lg:gap-4 mb-4">
                    <!-- progress bar -->
                    <div class="w-3/4 relative h-1" id="progress-container">
                        <span class="w-full h-1 bg-gray-300 rounded-full block absolute top-0 left-0"></span>
                        <span
                            class="h-1 bg-primary rounded-full block absolute top-0 left-0 transition-all duration-300"
                            id="progress-bar" style="width: 0%"></span>
                    </div>
                    <!-- Page -->
                    <span>
                        <span>PAGE</span>
                        <span id="current-page">1</span>
                        <span>OF</span>
                        <span id="total-pages">{{ $exam->questions->count() }}</span>
                    </span>
                    <!-- Timer -->
                    <span>
                        <span>TIME :</span>
                        <span id="timer-display">{{ sprintf('%02d:%02d', floor($exam->duration_minutes), ($exam->duration_minutes % 1) * 60) }}</span>
                        <input type="hidden" name="time_elapsed" id="time_elapsed" value="0">
                    </span>
                </div>

                <!-- Questions -->
                <div id="questions-container">
                    @foreach($exam->questions as $question)
                        <div class="question-container mb-4 w-full border border-gray-200 rounded-lg p-5 bg-white transition-all duration-300"
                            data-question-id="{{ $question->id }}" data-page="{{ $loop->iteration }}">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-md font-blod mb-3">
                                    <span class="font-semibold">Q{{ $question->question_no }}:</span> 
                                    {{ $question->langsAll()->where('language_id', $lang_id)->first()?->title ?? '-' }}
                                </p>
                                <span
                                    class="saved-indicator text-md text-primary bg-[#1B449C17] px-1 py-0.5 rounded-lg opacity-0 hidden transition-opacity duration-300 ease-in-out">✔
                                    Saved</span>
                            </div>

                            <div class="flex gap-4 w-3/4 mb-3">
                                @foreach($question->answers as $answer)
                                    <label
                                        class="answer-option flex items-center w-1/3 border border-gray-300 rounded-sm px-3 py-3 cursor-pointer bg-white hover:border-blue-400 transition-all duration-200">
                                        <input type="radio" name="questions[{{ $question->id }}]" value="{{ $answer->id }}" class="cursor-pointer" />
                                        <span class="answer-text ml-2 text-md">{{ $answer->langsAll()->where('language_id', $lang_id)->first()?->title ?? '-' }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-8">
                    <button type="button" id="back-btn"
                        class="px-6 py-2 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                        ← Back
                    </button>
                    <p class="text-gray-600 text-sm" id="continue-message">Answer All The Questions To Continue</p>
                    <button type="button" id="continue-btn" disabled
                        class="px-6 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md"
                        style="pointer-events: none;">
                        Continue →
                    </button>
                </div>
            </form>

            <!-- Exam Timeout Modal -->
            <div id="exam-timeout-modal" class="fixed inset-0 z-50 items-center justify-center bg-black/50 px-4 hidden">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 md:p-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 text-red-600">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Time is over</h3>
                            <p class="text-sm text-gray-600">
                                Your exam time has finished. You can submit your answers as they are or restart the exam
                                and try again.
                            </p>
                        </div>
                        <div class="flex flex-col md:flex-row gap-3 w-full mt-4">
                            <button id="timeout-restart-btn" type="button"
                                class="w-full px-4 py-2.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                Restart Exam
                            </button>
                            <button id="timeout-submit-btn" type="button"
                                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 transition-colors duration-200">
                                Submit Answers
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('front/assets/js/take_exam.js') }}"></script>
    @endpush
</x-front-layout>