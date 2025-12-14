<x-front-layout>


 <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center py-10 bg-white">
        <!-- Main Container -->
        <div class="container px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <!-- Home -->
                    <li>
                        <a href="index.html" class="transition-colors text-primary-600 hover:text-primary-700">
                            {{ label_text('global', 'Home', __('site.Home')) }}
                        </a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    <li>
                        <a href="#" class="transition-colors text-primary-600 hover:text-primary-700">
                            {{ label_text('global', 'About', __('site.About')) }}
                        </a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    <!-- Current Page -->
                    <li>
                        <span class="text-black">
                            {{ label_text('global', 'Get In Touch', __('site.Get In Touch')) }}
                        </span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <div class="text-left">
                <h1 class="mb-3 text-3xl font-bold text-black">
                    {{ label_text('global', 'Get In Touch', __('site.Get In Touch')) }}
                </h1>
                <p class="text-lg font-light text-primary">
                    {{ label_text('global', 'FEEL FREE TO DROP US A LINE BELOW', __('site.FEEL FREE TO DROP US A LINE BELOW')) }}
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="pt-8 pb-32">
        <div class="container px-4 mx-auto">

            <form id="contactForm" class="space-y-8" action="{{route('site.contact_data')}}" method="post">
                <!-- First Row - Names -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- First Name -->
                    <div class="space-y-2">
                        <label for="firstName" class="block text-sm font-bold text-black" name="firstName">
                            {{ label_text('global', 'Your First Name', __('site.Your First Name')) }}
                        </label>
                        <input type="text" id="firstName" name="firstName" placeholder="{{ label_text('global', 'Enter the first name', __('site.Enter the first name')) }}"
                            class="px-4 py-4 w-full text-black bg-gray-100 rounded-md border-2 border-gray-200 transition-all duration-300 focus:border-primary focus:bg-white focus:ring-0 focus:outline-none placeholder:text-gray-500"
                            required>
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-2">
                        <label for="lastName" class="block text-sm font-bold text-black">
                            {{ label_text('global', 'Your Last Name', __('site.Your Last Name')) }}
                        </label>
                        <input type="text" id="lastName" name="lastName" placeholder="{{ label_text('global', 'Enter the first name', __('site.Enter the first name')) }}"
                            class="px-4 py-4 w-full text-black bg-gray-100 rounded-md border-2 border-gray-200 transition-all duration-300 focus:border-primary focus:bg-white focus:ring-0 focus:outline-none placeholder:text-gray-500"
                            required>
                    </div>
                </div>

                <!-- Second Row - Email & Mobile -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-black">
                            {{ label_text('global', 'Your Email', __('site.Your Email')) }}
                        </label>
                        <input type="email" id="email" name="email" placeholder="{{ label_text('global', 'Enter Your Email', __('site.Enter Your Email')) }}"
                            class="px-4 py-4 w-full text-black bg-gray-100 rounded-md border-2 border-gray-200 transition-all duration-300 focus:border-primary focus:bg-white focus:ring-0 focus:outline-none placeholder:text-gray-500"
                            required>
                    </div>

                    <!-- Mobile -->
                    <div class="space-y-2">
                        <label for="mobile" class="block text-sm font-bold text-black">
                            {{ label_text('global', 'Your Mobile', __('site.Your Mobile')) }}
                        </label>
                        <input type="tel" id="mobile" name="mobile" placeholder="{{ label_text('global', 'Enter Your Mobile', __('site.Enter Your Mobile')) }}"
                            class="px-4 py-4 w-full text-black bg-gray-100 rounded-md border-2 border-gray-200 transition-all duration-300 focus:border-primary focus:bg-white focus:ring-0 focus:outline-none placeholder:text-gray-500"
                            required>
                    </div>
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-bold text-black">
                        {{ label_text('global', 'Your Message', __('site.Your Message')) }}
                    </label>
                    <textarea id="message" name="message" rows="6" placeholder="{{ label_text('global', 'Enter Your Message', __('site.Enter Your Message')) }}"
                        class="w-full px-4 py-4 text-black bg-gray-100 border-2 border-gray-200 rounded-md transition-all duration-300 focus:border-primary focus:bg-white focus:ring-0 focus:outline-none placeholder:text-gray-500 resize-vertical min-h-[120px]"
                        required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center pt-6">
                    <button type="submit" id="btnSubmit"
                        class="px-12 py-3 font-medium text-white rounded-lg transition-all duration-300 transform bg-primary hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 hover:scale-105">
                        {{ label_text('global', 'Send', __('site.Send')) }}
                    </button>
                </div>
            </form>


        </div>
    </section>


    <script>
        $(document).ready(function () {
            // Form validation and submission
            $('#contactForm').on('submit', function (e) {
                e.preventDefault();

                // Get form data
                const formData = {
                    firstName: $('#firstName').val(),
                    lastName: $('#lastName').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
                    message: $('#message').val()
                };

                // Basic validation
                let isValid = true;
                let errorMessage = '';

                // Check required fields
                Object.keys(formData).forEach(key => {
                    if (!formData[key].trim()) {
                        isValid = false;
                        errorMessage = "{{ label_text('global', 'Please fill in all required fields.', __('site.Please fill in all required fields.')) }}";
                        return;
                    }
                });

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (formData.email && !emailRegex.test(formData.email)) {
                    isValid = false;
                    errorMessage = "{{ label_text('global', 'Please enter a valid email address.', __('site.Please enter a valid email address.')) }}";
                }

                if (!isValid) {
                    alert(errorMessage);
                    return;
                }

                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.text("{{ label_text('global', 'Sending...', __('site.Sending...')) }}").prop('disabled', true);

                // Simulate form submission
                setTimeout(() => {
                    $(this).submit();
                }, 1500);
            });

            // Enhanced focus effects
            $('input, textarea').on('focus', function () {
                $(this).parent().addClass('field-focused');
            }).on('blur', function () {
                $(this).parent().removeClass('field-focused');
            });

            // Real-time validation feedback
            $('#email').on('blur', function () {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    $(this).addClass('border-red-500').removeClass('border-gray-300');
                    $('#btnSubmit').removeClass('bg-primary').addClass('bg-primary-200').prop('disabled', false);
                } else {
                    $(this).removeClass('border-red-500').addClass('border-gray-300');
                    $('#btnSubmit').addClass('bg-primary').removeClass('bg-primary-200').prop('disabled', true);
                }
            });

            // Character counter for message
            $('#message').on('input', function () {
                const maxLength = 5000;
                const currentLength = $(this).val().length;

                // Remove existing counter
                $(this).siblings('.char-counter').remove();

                if (currentLength > 0) {
                    const counter = $(`<div class="mt-1 text-xs text-gray-500 char-counter">${currentLength}/${maxLength} {{ label_text('global', 'characters', __('site.characters')) }}</div>`);
                    $(this).parent().append(counter);
                }

                if (currentLength > maxLength) {
                    $(this).addClass('border-red-500');
                    $('#btnSubmit').removeClass('bg-primary').addClass('bg-primary-200').prop('disabled', false);
                } else {
                    $(this).removeClass('border-red-500');
                    $('#btnSubmit').addClass('bg-primary').removeClass('bg-primary-200').prop('disabled', true);
                }
            });
        });
    </script>

</x-front-layout>
