<x-front-layout>

    <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-5">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-6">
                <div class="text-sm font-light text-black leading-relaxed">
                    <span>
                        <a href="index.html" class="text-primary-600 hover:text-primary-700">Home</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span>
                        <a href="#" class="text-primary-600 hover:text-primary-700">Course</a>
                    </span>
                    <span class="mx-1 text-gray-400">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    <span class="text-black break-words">
                        Complete Agentic AI Bootcamp with LangGraph & LangChain
                    </span>
                </div>
            </nav>

            <!-- Course Title & Description -->
            <div>
                <h1 class="text-3xl font-bold text-black mb-3">Complete Agentic AI Bootcamp
                    with LangGraph & LangChain</h1>
                <p class="text-2xl text-black leading-relaxed">Learn with speed, think with clarity,
                    grow with confidence.</p>
            </div>
        </div>
    </section>

    <!-- Course Detail Section -->
    <section class="pb-8 md:pb-16 pt-6">
        <div class="container mx-auto px-4">
            <!-- Two Column Layout -->
            <div class="mt-6 flex flex-col lg:flex-row gap-10">
                <!-- Left Column: Main Content -->
                <div class="w-full lg:w-2/3 space-y-10">
                    <!-- Tabs -->
                    <div class="flex gap-2 pb-6 mt-6 border-b-2 border-[#CAC6C6]">
                        <button
                            class="course-tab px-4 py-2 text-sm font-bold text-white bg-primary border border-primary rounded-lg transition"
                            data-target="#about-course">About the course</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-outputs">Outputs</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-content">Units</button>
                        <button
                            class="course-tab px-6 py-2 text-sm text-black bg-white border border-[#CAC6C6] rounded-lg transition"
                            data-target="#course-reviews">Evaluation</button>
                    </div>

                    <!-- About the course -->
                    <div id="about-course">
                        <h2 class="text-lg font-bold text-black mt-2 mb-3">About the course</h2>
                        <p class="text-[15px] text-black leading-7">
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                            veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                            mollit anim id est Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                            tempor .
                        </p>
                    </div>

                    <!-- What you will learn -->
                    <div class="mt-6">
                        <h2 class="text-lg font-bold text-black mb-3">What you will learn</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">
                                    <span class="font-medium">Building intelligent agents using</span> LangGraph
                                </p>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">
                                    Memory management, agent state, and event flow
                                </p>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">
                                    Deploying multi-agent applications that communicate and collaborate
                                </p>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">
                                    <span class="font-medium">Implementing practical projects:</span> Research Agent,
                                    Task Automation
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Course content (Accordions) -->
                    <div id="course-content" class="mt-6 border border-gray-300 p-4">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <h2 class="text-[20px] font-semibold text-black">Course content</h2>
                            <span class="text-[14px] text-gray-500">36 hours • Intermediate</span>
                        </div>
                        <div class="space-y-2">
                            <!-- Accordion Item 1 -->
                            <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                                <button
                                    class="accordion-header flex justify-between items-center w-full px-4 py-3 cursor-pointer bg-[#F7F7F7] border border-[#CAC6C6] rounded-lg  hover:bg-gray-100 text-lg">
                                    <div class="flex items-center gap-2">
                                        <span>Introduction and work environment</span>
                                    </div>
                                    <svg class="icon w-4 h-4 text-gray-500 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                                <div class="accordion-body hidden px-2 py-3 text-lg text-black leading-6">
                                    <p>Introduction to the course structure and setting up your development environment.
                                    </p>
                                </div>
                            </div>

                            <!-- Accordion Item 2 -->
                            <div class="accordion-item overflow-hidden">
                                <button
                                    class="accordion-header flex justify-between items-center w-full px-4 py-3 cursor-pointer bg-[#F7F7F7] border border-[#CAC6C6] rounded-lg  hover:bg-gray-100 text-lg">
                                    <div class="flex items-center gap-2">
                                        <span>Basic concepts of agents</span>
                                    </div>
                                    <svg class="icon w-4 h-4 text-gray-500 transition-transform rotate-180" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <div class="accordion-body px-2 py-3 text-lg text-black leading-6">
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center pb-4 border-b border-[#CAC6C6]">
                                            <span class="flex items-center gap-2">
                                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                                <span>Agent State & Memory</span>
                                            </span>
                                            <span class="text-[13px] text-gray-500">21:10</span>
                                        </div>
                                        <div class="flex justify-between items-center pb-4 border-b border-[#CAC6C6]">
                                            <span class="flex items-center gap-2">
                                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                                <span>Event-driven Workflows</span>
                                            </span>
                                            <span class="text-[13px] text-gray-500">17:05</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accordion Item 3 -->
                            <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                                <button
                                    class="accordion-header flex justify-between items-center w-full px-4 py-3 cursor-pointer bg-[#F7F7F7] border border-[#CAC6C6] rounded-lg  hover:bg-gray-100 text-lg">
                                    <div class="flex items-center gap-2">
                                        <span>Practical projects</span>
                                    </div>
                                    <svg class="icon w-4 h-4 text-gray-500 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                                <div class="accordion-body hidden px-2 py-3 text-lg text-black leading-6">
                                    <p>Hands-on projects including Research Agent and Task Automation systems.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div id="requirements" class="mt-10">
                        <h2 class="text-lg font-bold text-black mb-3">Requirements</h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">Basic knowledge of Python programming
                                    (variables, functions, classes).</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">Understanding of APIs and RESTful services
                                    (basic level).</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">Familiarity with Large Language Models
                                    (LLMs) concepts (like OpenAI, Hugging Face models, etc.).</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                <p class="text-[15px] text-black leading-6">Curiosity and willingness to build
                                    real-world AI applications — no prior experience with LangGraph needed!</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Sidebar -->
                <div class="w-full lg:w-1/3 space-y-4 mt-8 lg:mt-0">
                    <div class="rounded-md bg-white shadow-lg">
                        <!-- Video Card -->
                        <div class="relative rounded-t-lg w-full h-[216px] bg-gray-200 overflow-hidden">
                            <img src="./assets/imgs/Rectangle 1904355.png" alt="Course video"
                                class="w-full h-full object-cover">
                            <span
                                class="absolute top-1 right-1 rtl:left-1 rtl:right-auto text-sm bg-primary text-white p-1.5 rounded-lg z-8">Certified
                                Achievement Certificate</span>
                            <div class="absolute inset-0 flex items-center justify-center z-8">
                                <button class="bg-primary px-3 py-0.5 rounded-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <!-- left: 50%;
                            transform: translate(-50%, -50%); -->
                            <button
                                class="absolute left-1/2 -translate-1/2 z-10  bottom-0 text-white font-bold text-sm">View
                                the course</button>
                            <span class="absolute bg-black opacity-15 w-full h-full top-0 z-1"></span>
                        </div>
                        <div class="p-5">
                            <p class="text-[13px] text-black font-bold mb-1">This paid course is included in the plans
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-[17px] line-through text-gray-400">$69</span>
                                <span class="text-[19px] font-bold text-primary">$29</span>
                            </div>
                            <div class="mb-3 mt-2">
                                <span class="text-[17px] text-primary bg-[#1B449C1A] rounded-full py-0.5 px-2">Limited
                                    discount</span>
                            </div>
                            <div class="space-y-1 text-[13px] text-black font-light mb-4">
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>36 hours of on-demand video</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Project files + graph templates</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Practical tasks + solutions</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Certificate of completion</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-1 h-1 rounded-full bg-black mt-1.5 flex-shrink-0"></span>
                                    <span>Lifetime access + updates</span>
                                </div>
                            </div>
                            <button
                                class="mt-4 px-12 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition">Buy
                                now</button>
                        </div>
                    </div>

                    <!-- Certificate Card -->
                    <div class="border border-gray-200 rounded-xl p-3 bg-white shadow-lg">
                        <p class="text-[13px] font-bold text-black mb-2">The certificate is shareable</p>
                        <div class="flex items-center gap-2 mb-2">
                            <span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 14.5C6.38071 14.5 7.5 13.3807 7.5 12C7.5 10.6193 6.38071 9.5 5 9.5C3.61929 9.5 2.5 10.6193 2.5 12C2.5 13.3807 3.61929 14.5 5 14.5Z"
                                        stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M19 7.5C20.3807 7.5 21.5 6.38071 21.5 5C21.5 3.61929 20.3807 2.5 19 2.5C17.6193 2.5 16.5 3.61929 16.5 5C16.5 6.38071 17.6193 7.5 19 7.5Z"
                                        stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M19 21.5C20.3807 21.5 21.5 20.3807 21.5 19C21.5 17.6193 20.3807 16.5 19 16.5C17.6193 16.5 16.5 17.6193 16.5 19C16.5 20.3807 17.6193 21.5 19 21.5Z"
                                        stroke="#1B449C" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.29999 11L16.7 6M7.29999 13L16.7 18" stroke="#1B449C" stroke-width="1.7"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </span>
                            <p class="text-[13px] font-light text-black">Share on your social media or LinkedIn</p>
                        </div>
                        <img src="./assets/imgs/cer1.jpg" alt="Certificate" class="w-full object-contain rounded-b-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-8 md:pb-16 pt-6 bg-[#1B449C08]">
        <div class="container mx-auto px-4">
            <!-- Reviews Section -->
            <h2 class="text-[15px] font-bold text-black mb-3">4.5 (41 reviews)</h2>

            <div id="course-reviews" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <!-- Rating Summary -->
                    <div class="space-y-1 mb-6">
                        <div class="flex items-center gap-2 text-[14px] text-black">
                            <span class="flex items-center gap-2">
                                <span>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                            fill="#FFC700" />
                                    </svg>
                                </span>
                                <span class="font-bold text-[15px]">5 stars</span>
                            </span>
                            <div class="flex-1 h-1.5 rounded bg-gray-200">
                                <div class="w-[82.9%] bg-primary h-1.5 rounded"></div>
                            </div>
                            <span class="w-11 text-[#00000099]">82.9%</span>
                        </div>
                        <div class="flex items-center gap-2 text-[14px] text-black">
                            <span class="flex items-center gap-2">
                                <span>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                            fill="#FFC700" />
                                    </svg>
                                </span>
                                <span class="font-bold text-[15px]">4 stars</span>
                            </span>
                            <div class="flex-1 h-1.5 rounded bg-gray-200">
                                <div class="w-[7.4%] bg-primary h-1.5 rounded"></div>
                            </div>
                            <span class="w-11 text-[#00000099]">7.4%</span>
                        </div>
                        <div class="flex items-center gap-2 text-[14px] text-black">
                            <span class="flex items-center gap-2">
                                <span>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                            fill="#FFC700" />
                                    </svg>
                                </span>
                                <span class="font-bold text-[15px]">3 stars</span>
                            </span>
                            <div class="flex-1 h-1.5 rounded bg-gray-200">
                                <div class="w-[4.9%] bg-primary h-1.5 rounded"></div>
                            </div>
                            <span class="w-11 text-[#00000099]">4.9%</span>
                        </div>
                        <div class="flex items-center gap-2 text-[14px] text-black">
                            <span class="flex items-center gap-2">
                                <span>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                            fill="#FFC700" />
                                    </svg>
                                </span>
                                <span class="font-bold text-[15px]">2 stars</span>
                            </span>
                            <div class="flex-1 h-1.5 rounded bg-gray-200">
                                <div class="w-[0%] bg-primary h-1.5 rounded"></div>
                            </div>
                            <span class="w-11 text-[#00000099]">0%</span>
                        </div>
                        <div class="flex items-center gap-2 text-[14px] text-black">
                            <span class="flex items-center gap-2">
                                <span>
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.56135 13.7L4.41135 16.2C4.22802 16.3167 4.03635 16.3667 3.83635 16.35C3.63635 16.3333 3.46135 16.2667 3.31135 16.15C3.16135 16.0333 3.04468 15.8877 2.96135 15.713C2.87802 15.5377 2.86135 15.3417 2.91135 15.125L4.01135 10.4L0.33635 7.225C0.169683 7.075 0.065683 6.904 0.0243497 6.712C-0.0176503 6.52067 -0.00531702 6.33333 0.0613497 6.15C0.128016 5.96667 0.228016 5.81667 0.36135 5.7C0.494683 5.58333 0.678016 5.50833 0.91135 5.475L5.76135 5.05L7.63635 0.6C7.71968 0.4 7.84902 0.25 8.02435 0.15C8.19902 0.0500001 8.37802 0 8.56135 0C8.74468 0 8.92402 0.0500001 9.09935 0.15C9.27402 0.25 9.40302 0.4 9.48635 0.6L11.3614 5.05L16.2113 5.475C16.4447 5.50833 16.628 5.58333 16.7614 5.7C16.8947 5.81667 16.9947 5.96667 17.0613 6.15C17.128 6.33333 17.1407 6.52067 17.0993 6.712C17.0574 6.904 16.953 7.075 16.7864 7.225L13.1113 10.4L14.2113 15.125C14.2613 15.3417 14.2447 15.5377 14.1614 15.713C14.078 15.8877 13.9613 16.0333 13.8113 16.15C13.6614 16.2667 13.4864 16.3333 13.2864 16.35C13.0864 16.3667 12.8947 16.3167 12.7113 16.2L8.56135 13.7Z"
                                            fill="#FFC700" />
                                    </svg>
                                </span>
                                <span class="font-bold text-[15px]">1 stars</span>
                            </span>
                            <div class="flex-1 h-1.5 rounded bg-gray-200">
                                <div class="w-[0%] bg-primary h-1.5 rounded"></div>
                            </div>
                            <span class="w-11 text-[#00000099]">0%</span>
                        </div>

                    </div>
                </div>

                <!-- Review Cards -->
                <div class="space-y-4 md:col-span-2">
                    <div class="border border-[#CAC6C6] rounded-xl p-4 flex flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <img src="./assets/imgs/user-avatar.jpg" alt="Reviewer"
                                class="w-16.5 h-16.5 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1">
                                <p class="text-[15px] font-bold text-black">KRISHAI Technologies Private Limited</p>
                                <div class="flex items-center gap-2 my-1 text-[15px]">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.9988 18.2742L7.84885 20.7742C7.66552 20.8909 7.47385 20.9409 7.27385 20.9242C7.07385 20.9076 6.89885 20.8409 6.74885 20.7242C6.59885 20.6076 6.48218 20.4619 6.39885 20.2872C6.31552 20.1119 6.29885 19.9159 6.34885 19.6992L7.44885 14.9742L3.77385 11.7992C3.60718 11.6492 3.50318 11.4782 3.46185 11.2862C3.41985 11.0949 3.43218 10.9076 3.49885 10.7242C3.56552 10.5409 3.66552 10.3909 3.79885 10.2742C3.93218 10.1576 4.11552 10.0826 4.34885 10.0492L9.19885 9.62422L11.0738 5.17422C11.1572 4.97422 11.2865 4.82422 11.4619 4.72422C11.6365 4.62422 11.8155 4.57422 11.9988 4.57422C12.1822 4.57422 12.3615 4.62422 12.5368 4.72422C12.7115 4.82422 12.8405 4.97422 12.9239 5.17422L14.7989 9.62422L19.6488 10.0492C19.8822 10.0826 20.0655 10.1576 20.1989 10.2742C20.3322 10.3909 20.4322 10.5409 20.4988 10.7242C20.5655 10.9076 20.5782 11.0949 20.5368 11.2862C20.4949 11.4782 20.3905 11.6492 20.2239 11.7992L16.5488 14.9742L17.6488 19.6992C17.6988 19.9159 17.6822 20.1119 17.5989 20.2872C17.5155 20.4619 17.3988 20.6076 17.2488 20.7242C17.0989 20.8409 16.9239 20.9076 16.7239 20.9242C16.5239 20.9409 16.3322 20.8909 16.1488 20.7742L11.9988 18.2742Z"
                                                fill="#FFC700" />
                                        </svg>
                                    </span>
                                    <span class="font-bold text-black">5</span>
                                    <p class=" text-[#00000099]">Reviewed on November 03, 2025</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-[15px] text-gray-700 leading-6">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et
                        </p>
                    </div>
                    <div class="border border-[#CAC6C6] rounded-xl p-4 flex flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <img src="./assets/imgs/user-avatar.jpg" alt="Reviewer"
                                class="w-16.5 h-16.5 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1">
                                <p class="text-[15px] font-bold text-black">KRISHAI Technologies Private Limited</p>
                                <div class="flex items-center gap-2 my-1 text-[15px]">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.9988 18.2742L7.84885 20.7742C7.66552 20.8909 7.47385 20.9409 7.27385 20.9242C7.07385 20.9076 6.89885 20.8409 6.74885 20.7242C6.59885 20.6076 6.48218 20.4619 6.39885 20.2872C6.31552 20.1119 6.29885 19.9159 6.34885 19.6992L7.44885 14.9742L3.77385 11.7992C3.60718 11.6492 3.50318 11.4782 3.46185 11.2862C3.41985 11.0949 3.43218 10.9076 3.49885 10.7242C3.56552 10.5409 3.66552 10.3909 3.79885 10.2742C3.93218 10.1576 4.11552 10.0826 4.34885 10.0492L9.19885 9.62422L11.0738 5.17422C11.1572 4.97422 11.2865 4.82422 11.4619 4.72422C11.6365 4.62422 11.8155 4.57422 11.9988 4.57422C12.1822 4.57422 12.3615 4.62422 12.5368 4.72422C12.7115 4.82422 12.8405 4.97422 12.9239 5.17422L14.7989 9.62422L19.6488 10.0492C19.8822 10.0826 20.0655 10.1576 20.1989 10.2742C20.3322 10.3909 20.4322 10.5409 20.4988 10.7242C20.5655 10.9076 20.5782 11.0949 20.5368 11.2862C20.4949 11.4782 20.3905 11.6492 20.2239 11.7992L16.5488 14.9742L17.6488 19.6992C17.6988 19.9159 17.6822 20.1119 17.5989 20.2872C17.5155 20.4619 17.3988 20.6076 17.2488 20.7242C17.0989 20.8409 16.9239 20.9076 16.7239 20.9242C16.5239 20.9409 16.3322 20.8909 16.1488 20.7742L11.9988 18.2742Z"
                                                fill="#FFC700" />
                                        </svg>
                                    </span>
                                    <span class="font-bold text-black">5</span>
                                    <p class=" text-[#00000099]">Reviewed on November 03, 2025</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-[15px] text-gray-700 leading-6">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et
                        </p>
                    </div>
                    <div class="border border-[#CAC6C6] rounded-xl p-4 flex flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <img src="./assets/imgs/user-avatar.jpg" alt="Reviewer"
                                class="w-16.5 h-16.5 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1">
                                <p class="text-[15px] font-bold text-black">KRISHAI Technologies Private Limited</p>
                                <div class="flex items-center gap-2 my-1 text-[15px]">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.9988 18.2742L7.84885 20.7742C7.66552 20.8909 7.47385 20.9409 7.27385 20.9242C7.07385 20.9076 6.89885 20.8409 6.74885 20.7242C6.59885 20.6076 6.48218 20.4619 6.39885 20.2872C6.31552 20.1119 6.29885 19.9159 6.34885 19.6992L7.44885 14.9742L3.77385 11.7992C3.60718 11.6492 3.50318 11.4782 3.46185 11.2862C3.41985 11.0949 3.43218 10.9076 3.49885 10.7242C3.56552 10.5409 3.66552 10.3909 3.79885 10.2742C3.93218 10.1576 4.11552 10.0826 4.34885 10.0492L9.19885 9.62422L11.0738 5.17422C11.1572 4.97422 11.2865 4.82422 11.4619 4.72422C11.6365 4.62422 11.8155 4.57422 11.9988 4.57422C12.1822 4.57422 12.3615 4.62422 12.5368 4.72422C12.7115 4.82422 12.8405 4.97422 12.9239 5.17422L14.7989 9.62422L19.6488 10.0492C19.8822 10.0826 20.0655 10.1576 20.1989 10.2742C20.3322 10.3909 20.4322 10.5409 20.4988 10.7242C20.5655 10.9076 20.5782 11.0949 20.5368 11.2862C20.4949 11.4782 20.3905 11.6492 20.2239 11.7992L16.5488 14.9742L17.6488 19.6992C17.6988 19.9159 17.6822 20.1119 17.5989 20.2872C17.5155 20.4619 17.3988 20.6076 17.2488 20.7242C17.0989 20.8409 16.9239 20.9076 16.7239 20.9242C16.5239 20.9409 16.3322 20.8909 16.1488 20.7742L11.9988 18.2742Z"
                                                fill="#FFC700" />
                                        </svg>
                                    </span>
                                    <span class="font-bold text-black">5</span>
                                    <p class=" text-[#00000099]">Reviewed on November 03, 2025</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-[15px] text-gray-700 leading-6">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-16">
        <div class="container mx-auto">
            <!-- Reviews Section -->
            <h2 class="text-lg font-bold text-black mb-3">Courses You Might Be Interested In</h2>
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="coursesGrid">

                <!-- Course Card 1 - Design (Original - No Changes) -->
                <div class="block overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Design">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=400&h=250&fit=crop"
                            alt="Design Course" class="object-cover w-full h-full">
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Design</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet,consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center h-[45px] transition-all duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">23 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">Free</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 - AI -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="AI">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop"
                            alt="AI Course" class="object-cover w-full h-full">
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Development</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">23 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">Free</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 - Marketing -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Marketing">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop"
                            alt="Marketing Course" class="object-cover w-full h-full">
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Marketing</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">23 total hours</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm line-through text-[#87CEEB]">$200</span>
                                    <span class="text-[15px] font-bold text-[#1B449C]">$199.99</span>
                                </div>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 4 - Business -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Business">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=250&fit=crop"
                            alt="Business Course" class="object-cover w-full h-full">
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Business</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">30 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">$99.99</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Course Cards for Load More -->
                <!-- Course Card 5 - Programming -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Programming" style="display: none;">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?w=400&h=250&fit=crop"
                            alt="Programming Course" class="object-cover w-full h-full">
                    </div>
                    <div class="pt-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Programming</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">23 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">Free</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 6 - Design -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Design" style="display: none;">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=250&fit=crop"
                            alt="UX Design Course" class="object-cover w-full h-full">
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">UX Design</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">35 total hours</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm line-through text-[#87CEEB]">$150</span>
                                    <span class="text-[15px] font-bold text-[#1B449C]">$129.99</span>
                                </div>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 7 - AI -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="AI" style="display: none;">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&h=250&fit=crop"
                            alt="Machine Learning Course" class="object-cover w-full h-full">
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Machine Learning</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">42 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">Free</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Card 8 - Business -->
                <div class="overflow-hidden p-3 bg-white rounded-md shadow-sm transition-all duration-300 group course-card hover:shadow-lg hover:scale-105"
                    data-type="Business" style="display: none;">
                    <div class="overflow-hidden relative h-48 rounded-sm">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=250&fit=crop"
                            alt="Leadership Course" class="object-cover w-full h-full">
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2 text-lg font-bold text-black text-[18px]">Leadership</h3>
                        <p class="mb-4 text-[13px] text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <div
                                class="flex justify-between items-center transition-all h-[45px] duration-300 group-hover:opacity-0 group-hover:hidden">
                                <div class="flex gap-2 items-center">
                                    <i class="text-sm fas fa-clock text-[#1B449C]"></i>
                                    <span class="text-sm text-black">28 total hours</span>
                                </div>
                                <span class="text-lg font-bold text-[#1B449C]">$149.99</span>
                            </div>
                            <div
                                class="hidden opacity-0 transition-all duration-300 group-hover:flex group-hover:opacity-100">
                                <a href="#"
                                    class="px-4 py-2 w-full text-sm font-medium text-center text-white rounded-lg transition-all duration-300 hover:opacity-90 hover:cursor-pointer bg-[#1B449C]">
                                    Preview this courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Load More Button -->
            <div class="text-center">
                <button id="loadMoreBtn"
                    class="overflow-hidden relative px-9 py-4 text-[15px] text-white rounded-lg transition-all duration-300 transform bg-primary group hover:bg-primary-700 hover:-translate-y-2 hover:shadow-xl">
                    <span class="relative z-10">Load More</span>
                    <div
                        class="absolute inset-0 bg-white opacity-0 transition-all duration-500 transform -translate-x-full group-hover:translate-x-0 group-hover:opacity-10">
                    </div>
                </button>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('front/assets/js/course_detail.js') }}"></script>
    @endpush

</x-front-layout>