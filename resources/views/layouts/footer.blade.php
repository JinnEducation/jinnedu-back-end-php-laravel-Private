<footer class="bg-[#374151] text-white px-4">
    <!-- المحتوى الرئيسي للفوتر -->
    <div class="container pt-12 pb-6 mx-auto">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-7 lg:gap-12">

            <!-- العمود الأول: الشعار والوصف -->
            <div class="col-span-1 text-center md:col-span-2 md:text-left rtl:md:text-right">
                <div class="flex justify-center mb-4 lg:justify-start">
                    <img src="{{ asset('front/assets/imgs/logo-white.png') }}"
                        alt="{{ label_text('global', 'site.footer-logo-alt', __('site.JINN Education Logo')) }}"
                        class="w-32">
                </div>
                <p class="text-sm leading-relaxed text-gray-300 md:text-[13px]">
                    {{ label_text('global', 'site.footer-description', __('site.Subscribe to our newsletter description')) }}
                </p>
            </div>

            <!-- العمود الثاني: قوائم الروابط -->
            <div class="grid grid-cols-2 col-span-1 gap-2 md:col-span-3 lg:grid-cols-3 md:grid-cols-2">
                <div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Home', __('site.Home')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.pages.show', ['slug' => 'about-us']) }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.about-us', __('site.about-us')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.pages.show', ['slug' => 'How-JinnEdu-Works']) }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.how-jin-works', __('site.how-jin-works')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.online_private_classes') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Live-Private-Classes', __('site.Live Private Classes')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.online_group_classes') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Live-Group-Classes', __('site.Live Group Classes')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Categories', __('site.Categories')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.K-12', __('site.K-12')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.contact') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.contact-us', __('site.contact-us')) }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Get-In-Touch', __('site.Get In Touch')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Help-For-Tutor', __('site.Help For Tutor')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Student-Guide', __('site.Student Guide')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Tutor-Guide', __('site.Tutor Guide')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.help-center', __('site.help-center')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Technical-Support', __('site.Technical Support')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Free-Courses', __('site.Free Courses')) }}
                            </a>
                        </li>

                    </ul>
                </div>
                <div>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('site.pages.show', ['slug' => 'terms-of-use']) }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Terms-and-Conditions', __('site.Terms and Conditions')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.pages.show', ['slug' => 'Policy']) }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Privacy-and-Usage-Policy', __('site.Privacy and Usage Policy')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Intellectual-Property-and-Copyrights', __('site.Intellectual Property and Copyrights')) }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.coming_soon') }}"
                                class="flex items-center text-sm text-gray-300 transition-all duration-300 hover:text-primary-400 hover:pr-2 rtl:hover:pl-2 rtl:hover:pr-0 md:text-[12px] group">
                                <span
                                    class="mr-2 w-1 h-1 bg-gray-400 rounded-full transition-colors duration-300 rtl:ml-2 rtl:mr-0 group-hover:bg-primary-400"></span>
                                {{ label_text('global', 'site.Refund-Policy', __('site.Refund Policy')) }}
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- العمود الثالث: النشرة الإخبارية -->
            <div class="col-span-1 text-center md:col-span-2 md:text-left rtl:md:text-right">
                <p class="mb-6 text-sm text-gray-300 md:text-base">
                    {{ label_text('global', 'site.newsletter-text', __("site.Subscribe to our newsletter Updated digest of what's new and exciting from JINN EDU.")) }}
                </p>
                <!-- نموذج الاشتراك -->
                <div class="flex gap-3 mx-auto max-w-md md:mx-0">
                    <input type="email"
                        placeholder="{{ label_text('global', 'site.enter-your-email', __('site.Enter your email')) }}"
                        class="flex-1 px-4 py-3 min-w-0 placeholder-gray-400 text-black bg-white rounded-lg border border-gray-500 transition-all duration-300 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-opacity-50" />

                    <button
                        class="px-6 py-3 font-medium text-white rounded-lg transition-all duration-300 transform bg-primary hover:bg-primary-700 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-opacity-50">
                        {{ label_text('global', 'site.Subscribe', __('site.Subscribe')) }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- أسفل الفوتر -->
    <div class="container px-6 pb-6 mx-auto">
        <!-- خط الفصل -->
        <div class="mb-6 border-t border-gray-600"></div>

        <div class="flex flex-col-reverse gap-4 justify-between items-center md:flex-row">
            <!-- حقوق النشر -->
            <div class="flex items-center text-sm text-gray-400">
                <i class="mr-2 far fa-copyright rtl:ml-2 rtl:mr-0"></i>
                {{ label_text('global', 'site.footer-copy', __('site.2025 JINN EDU. All rights reserved.')) }}
            </div>

            <!-- أيقونات السوشال ميديا -->
            <div class="flex space-x-2 rtl:space-x-reverse">
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary hover:scale-110 group">
                    <i class="text-md fab fa-facebook-f group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-pink-600 hover:scale-110 group">
                    <i class="text-md fab fa-instagram group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-gray-800 hover:scale-110 group">
                    <i class="text-md fab fa-x-twitter group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary-700 hover:scale-110 group">
                    <i class="text-md fab fa-linkedin-in group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-red-600 hover:scale-110 group">
                    <i class="text-md fab fa-youtube group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-purple-600 hover:scale-110 group">
                    <i class="text-md fab fa-discord group-hover:text-white"></i>
                </a>
                <a href="#"
                    class="flex justify-center items-center w-7 h-7 text-gray-600 bg-white rounded-full transition-all duration-300 transform hover:bg-primary-900 hover:scale-110 group">
                    <i class="text-md fab fa-telegram-plane group-hover:text-white"></i>
                </a>
            </div>
        </div>
    </div>
</footer>