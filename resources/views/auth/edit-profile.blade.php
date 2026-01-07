@php
    $htmlLocale = str_replace('_', '-', app()->getLocale());
    $htmlDirection = App\Models\Language::where('shortname', app()->getLocale())->first()->direction;
@endphp
<!DOCTYPE html>
<html lang="{{ $htmlLocale }}" dir="{{ $htmlDirection }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ label_text('global', 'site.register-title', __('site.Create Account - JINN EDU')) }}</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/all.min.css') }}">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('front/assets/css/style.css') }}">
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen flex-col md:flex-row">
        <!-- Sidebar Desktop -->
        <aside
            class="block relative h-full w-100 bg-primary text-white p-10 md:sticky top-0 md:h-screen overflow-y-auto  scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
            <!-- Logo -->
            <div>
                <div class="my-12 flex justify-center items-center">
                    <img src="{{ asset('front/assets/imgs/logo-white.png') }}"
                        alt="{{ label_text('global', 'site.register-logo-alt', __('site.JINN Education')) }}"
                        width="45%">
                </div>
                <div class="flex justify-center items-center gap-4 text-sm mb-12">
                    <a href="{{ route('home') }}" class="hover:underline">
                        {{ label_text('global', 'site.Home', __('site.Home')) }}
                    </a>
                    <span>|</span>
                    <a href="{{ route('redirect.dashboard') }}" class="hover:underline">
                        {{ label_text('global', 'site.Go to dashboard', __('site.Go to dashboard')) }}
                    </a>
                    <span>|</span>
                    <a href="{{ route('site.contact') }}" class="hover:underline">
                        {{ label_text('global', 'site.contact-us', __('site.contact-us')) }}
                    </a>
                </div>
            </div>

            <!-- Steps Navigation -->
            <div class="relative space-y-6">
                <!-- Connector Line 1-2 -->
                <div class="absolute left-5 w-0.5 h-full bg-gradient-to-b from-gray-400 to-transparent"
                    style="background: repeating-linear-gradient(to bottom, #94A3B8 0px, #94A3B8 4px, transparent 4px, transparent 8px);">
                </div>

                <!-- Step 2 -->
                <div class="step-item" data-step="2">
                    <div class="step-circle" data-circle="2">1</div>
                    <div class="flex-1 pt-2">
                        <h3 class="font-semibold text-base">
                            {{ label_text('global', 'site.register-step2-title', __('site.Account Information')) }}
                        </h3>
                    </div>
                </div>
                @if($user->type == 2)
                    <!-- Step 3 -->
                    <div class="step-item hidden" data-step="3">
                        <div class="step-circle" data-circle="3">2</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step3-title', __('site.Personal Information')) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-item hidden" data-step="4">
                        <div class="step-circle" data-circle="4">3</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step4-title', __('site.Tutor\'s Biography')) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="step-item hidden" data-step="5">
                        <div class="step-circle" data-circle="5">4</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step5-title', __('site.Tutor\'s Availability')) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="step-item hidden" data-step="6">
                        <div class="step-circle" data-circle="6">5</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step6-title', __('site.Tutor\'s Hourly Rate')) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div class="step-item hidden" data-step="7">
                        <div class="step-circle" data-circle="7">6</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step7-title', __('site.Tutor\'s Qualifications & Certificates')) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Step 8 -->
                    <div class="step-item hidden" data-step="8">
                        <div class="step-circle" data-circle="8">7</div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-semibold text-base">
                                {{ label_text('global', 'site.register-step8-title', __('site.Tutor Profile Video')) }}
                            </h3>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="mt-12 text-xs text-white/70">
                <p>
                    {{ label_text('global', 'site.register-footer-copy', __('site.© 2024 - 2025 JINNEDU. All rights reserved.')) }}
                </p>
                <a href="#" class="underline hover:text-white">
                    {{ label_text('global', 'site.register-privacy-policies', __('site.Privacy Policies')) }}
                </a>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto mt-12">
            @if($errors->any())
                <div class="text-white rounded-lg bg-danger">
                    <ul>
                        @foreach ($errors->all() as $key => $error)
                            <li>{{ $key + 1 . ' - ' . $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" id="signup-form" action="{{ route('profile.edit.store') }}"
                enctype="multipart/form-data">
                <input type="hidden" name="type" id="account-type" value="{{ old('type', $user->type) }}" required>

                <!-- Step 2: Account Information -->
                <div class="pane block opacity-100" data-step="2">
                    <div class="mx-auto" id="account-info">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step2-heading', __('site.Account Information')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step2-subtitle', __('site.Set up your account basic information')) }}
                            </p>
                        </div>

                        <!-- Profile Picture -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-900 mb-2 text-center">
                                {{ label_text('global', 'site.register-profile-picture-label', __('site.Profile Picture')) }}
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <div class="flex justify-center mb-8">
                            <div class="relative">
                                <!-- Avatar Circle -->
                                <div id="avatarPreview"
                                    class="w-43 h-43 rounded-full shadow-lg bg-gray-200 flex items-center justify-center overflow-hidden">

                                    @if($profile?->avatar_path)
                                        <svg id="avatarIcon" class="w-20 h-20 text-gray-400 hidden" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                        <img id="avatarImage" src="{{ asset('storage/' . $profile->avatar_path) }}"
                                            class="object-cover w-full h-full rounded-full">
                                    @else
                                        <svg id="avatarIcon" class="w-20 h-20 text-gray-400" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                        <!-- image preview -->
                                        <img id="avatarImage" src="" alt="Preview"
                                            class="hidden object-cover w-full h-full rounded-full" />
                                    @endif

                                </div>

                                <!-- Edit Button -->
                                <button type="button" id="btnUpload"
                                    class="absolute top-0 right-0 bg-primary border-4 border-[#F5F5F5] text-white p-2 rounded-full shadow-lg hover:bg-primary-dark transition-colors">
                                    <svg class="w-5 h-5" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.183 3.1978L13.689 5.7038C13.7395 5.75474 13.7678 5.82357 13.7678 5.8953C13.7678 5.96703 13.7395 6.03586 13.689 6.0868L7.622 12.1538L5.044 12.4398C4.96288 12.4495 4.88063 12.4406 4.80345 12.4138C4.72626 12.3871 4.65615 12.3432 4.59838 12.2854C4.54062 12.2277 4.49672 12.1575 4.46997 12.0804C4.44323 12.0032 4.43435 11.9209 4.444 11.8398L4.73 9.2618L10.8 3.1978C10.8509 3.1473 10.9198 3.11897 10.9915 3.11897C11.0632 3.11897 11.1321 3.1473 11.183 3.1978ZM15.683 2.5618L14.328 1.2068C14.1245 1.004 13.8488 0.890137 13.5615 0.890137C13.2742 0.890137 12.9985 1.004 12.795 1.2068L11.812 2.1898C11.7615 2.24074 11.7332 2.30957 11.7332 2.3813C11.7332 2.45303 11.7615 2.52186 11.812 2.5728L14.317 5.0828C14.3679 5.13331 14.4368 5.16164 14.5085 5.16164C14.5802 5.16164 14.6491 5.13331 14.7 5.0828L15.683 4.0998C15.8858 3.89626 15.9997 3.62063 15.9997 3.3333C15.9997 3.04597 15.8858 2.77035 15.683 2.5668V2.5618ZM10.667 10.5038V13.3318H1.778V4.4428H8.161C8.24966 4.44132 8.33427 4.40547 8.397 4.3428L9.508 3.2338C9.55479 3.18723 9.58667 3.1278 9.59961 3.06306C9.61255 2.99833 9.60595 2.9312 9.58066 2.87022C9.55537 2.80924 9.51252 2.75716 9.45756 2.72058C9.40261 2.68401 9.33802 2.66459 9.272 2.6648H1.333C0.979548 2.66507 0.640648 2.80559 0.390719 3.05552C0.140791 3.30545 0.000264858 3.64435 0 3.9978L0 13.7758C0.000264858 14.1293 0.140791 14.4682 0.390719 14.7181C0.640648 14.968 0.979548 15.1085 1.333 15.1088H11.111C11.4645 15.1085 11.8034 14.968 12.0533 14.7181C12.3032 14.4682 12.4437 14.1293 12.444 13.7758V9.3928C12.4437 9.327 12.424 9.26274 12.3874 9.20809C12.3507 9.15344 12.2987 9.11084 12.238 9.08563C12.1772 9.06042 12.1103 9.05372 12.0457 9.06638C11.9812 9.07905 11.9218 9.1105 11.875 9.1568L10.764 10.2678C10.7024 10.331 10.6677 10.4156 10.667 10.5038Z"
                                            fill="white" />
                                    </svg>
                                </button>

                                <!-- Hidden File Input -->
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" required
                                    class="hidden" />
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="mb-6">
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.register-first-name-label', __('site.First Name')) }}
                                    </label>
                                    <input type="text"
                                        placeholder="{{ label_text('global', 'site.register-first-name-placeholder', __('site.First Name')) }}"
                                        name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.register-last-name-label', __('site.Last Name')) }}
                                    </label>
                                    <input type="text"
                                        placeholder="{{ label_text('global', 'site.register-last-name-placeholder', __('site.Last Name')) }}"
                                        name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.Email', __('site.Email')) }}
                                </label>
                                <div class="relative">
                                    <input id="email" type="email" name="email" readonly
                                        placeholder="{{ label_text('global', 'site.register-email-placeholder', __('site.example@gmail.com')) }}"
                                        value="{{ old('email', $user->email ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>

                                <!-- Email Validation Message -->
                                <p id="email-msg"
                                    class="text-xs text-gray-500 mt-1 opacity-0 transition-all duration-300">
                                    {{ label_text('global', 'site.register-email-msg', __('site.Please enter a valid email address')) }}
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.Country', __('site.Country')) }}
                                    </label>
                                    <select name="country" required id="country_inp"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country', $profile->country ?? '') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }} @if($country->phonecode)
                                                ({{$country->phonecode}})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.register-contact-number-label', __('site.Contact number')) }}
                                    </label>
                                    <input type="tel"
                                        placeholder="{{ label_text('global', 'site.register-phone-placeholder', __('site.Phone')) }}"
                                        name="phone" required value="{{ old('phone', $user->phone ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            {{-- <button type="button"
                                class="cursor-pointer btn-google flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05"
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335"
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                {{ label_text('global', 'site.sign-in-google-short', __('site.Google')) }}
                            </button> --}}
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                            <button type="submit"
                                class="cursor-pointer hidden btn-submit bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-submit', __('site.Submit')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Personal Information -->
                <div class="pane hidden" data-step="3">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step3-heading', __('site.Personal Information')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step3-subtitle', __('site.Set up your account basic information')) }}
                            </p>
                        </div>

                        <div class="mb-6">
                            <!-- Date of Birth -->
                            <div class="mb-2 relative font-inter">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-dob-label', __('site.Date of birth')) }}
                                </label>

                                <div class="relative">
                                    <!-- Hidden input for actual date value -->
                                    <input id="dob-value" type="hidden" name="date_of_birth"
                                        value="{{ old('date_of_birth', \Carbon\Carbon::parse(optional($tutorProfile)->dob)->format('Y-m-d')) }}"
                                        required>

                                    <!-- Display input for user -->
                                    <input id="dob-input" type="text" readonly
                                        value="{{ old('date_of_birth', \Carbon\Carbon::parse(optional($tutorProfile)->dob)->format('Y-m-d')) }}"
                                        placeholder="{{ label_text('global', 'site.register-dob-placeholder', __('site.Date of birth (yyyy / MMM / DD)')) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all pr-12 cursor-pointer shadow-sm">

                                    <!-- Icon -->
                                    <div id="dob-icon"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition cursor-pointer">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8 14.5C8.55228 14.5 9 14.0523 9 13.5C9 12.9477 8.55228 12.5 8 12.5C7.44772 12.5 7 12.9477 7 13.5C7 14.0523 7.44772 14.5 8 14.5Z"
                                                fill="#AAAAAA" />
                                            <path
                                                d="M8 18.5C8.55228 18.5 9 18.0523 9 17.5C9 16.9477 8.55228 16.5 8 16.5C7.44772 16.5 7 16.9477 7 17.5C7 18.0523 7.44772 18.5 8 18.5Z"
                                                fill="#AAAAAA" />
                                            <path
                                                d="M12 14.5C12.5523 14.5 13 14.0523 13 13.5C13 12.9477 12.5523 12.5 12 12.5C11.4477 12.5 11 12.9477 11 13.5C11 14.0523 11.4477 14.5 12 14.5Z"
                                                fill="#AAAAAA" />
                                            <path
                                                d="M12 18.5C12.5523 18.5 13 18.0523 13 17.5C13 16.9477 12.5523 16.5 12 16.5C11.4477 16.5 11 16.9477 11 17.5C11 18.0523 11.4477 18.5 12 18.5Z"
                                                fill="#AAAAAA" />
                                            <path
                                                d="M16 14.5C16.5523 14.5 17 14.0523 17 13.5C17 12.9477 16.5523 12.5 16 12.5C15.4477 12.5 15 12.9477 15 13.5C15 14.0523 15.4477 14.5 16 14.5Z"
                                                fill="#AAAAAA" />
                                            <path
                                                d="M16 18.5C16.5523 18.5 17 18.0523 17 17.5C17 16.9477 16.5523 16.5 16 16.5C15.4477 16.5 15 16.9477 15 17.5C15 18.0523 15.4477 18.5 16 18.5Z"
                                                fill="#AAAAAA" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M22.75 6.5V19.5C22.75 20.495 22.355 21.448 21.652 22.152C20.948 22.855 19.995 23.25 19 23.25H5C4.005 23.25 3.052 22.855 2.348 22.152C1.645 21.448 1.25 20.495 1.25 19.5V6.5C1.25 5.505 1.645 4.552 2.348 3.848C3.052 3.145 4.005 2.75 5 2.75H19C19.995 2.75 20.948 3.145 21.652 3.848C22.355 4.552 22.75 5.505 22.75 6.5ZM21.25 6.5C21.25 5.903 21.013 5.331 20.591 4.909C20.169 4.487 19.597 4.25 19 4.25H5C4.403 4.25 3.831 4.487 3.409 4.909C2.987 5.331 2.75 5.903 2.75 6.5V19.5C2.75 20.097 2.987 20.669 3.409 21.091C3.831 21.513 4.403 21.75 5 21.75H19C19.597 21.75 20.169 21.513 20.591 21.091C21.013 20.669 21.25 20.097 21.25 19.5V6.5Z"
                                                fill="#AAAAAA" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M22 8.25C22.414 8.25 22.75 8.586 22.75 9C22.75 9.414 22.414 9.75 22 9.75H2C1.586 9.75 1.25 9.414 1.25 9C1.25 8.586 1.586 8.25 2 8.25H22Z"
                                                fill="#AAAAAA" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.25 1.5C15.25 1.086 15.586 0.75 16 0.75C16.414 0.75 16.75 1.086 16.75 1.5V5.5C16.75 5.914 16.414 6.25 16 6.25C15.586 6.25 15.25 5.914 15.25 5.5V1.5Z"
                                                fill="#AAAAAA" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.25 1.5C7.25 1.086 7.586 0.75 8 0.75C8.414 0.75 8.75 1.086 8.75 1.5V5.5C8.75 5.914 8.414 6.25 8 6.25C7.586 6.25 7.25 5.914 7.25 5.5V1.5Z"
                                                fill="#AAAAAA" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Date Dropdown -->
                                <div id="dob-dropdown"
                                    class="hidden absolute top-full left-0 w-full md:w-1/2 lg:w-1/3 bg-white border border-gray-200 rounded-xl shadow-lg mt-2 z-50 p-4 transition-all duration-200">

                                    <!-- Month & Year Select -->
                                    <div class="flex items-center justify-between mb-3 gap-2">
                                        <select id="yearSelect" class="border rounded-lg px-2 py-1 text-sm w-1/2">
                                        </select>

                                        <select id="monthSelect" class="border rounded-lg px-2 py-1 text-sm w-1/2">
                                            <option value="0">January</option>
                                            <option value="1">February</option>
                                            <option value="2">March</option>
                                            <option value="3">April</option>
                                            <option value="4">May</option>
                                            <option value="5">June</option>
                                            <option value="6">July</option>
                                            <option value="7">August</option>
                                            <option value="8">September</option>
                                            <option value="9">October</option>
                                            <option value="10">November</option>
                                            <option value="11">December</option>
                                        </select>
                                    </div>

                                    <h3 id="monthLabel" class="font-semibold text-gray-800 text-sm mb-2"></h3>

                                    <div id="calendarGrid"
                                        class="grid grid-cols-7 text-center text-sm text-gray-700 select-none">
                                    </div>
                                </div>

                            </div>
                            <!-- Country (hidden as original) + Language + Subject -->
                            <div class="grid md:grid-cols-2 gap-6 mb-2">
                                <div class="hidden">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.Country', __('site.Country')) }}
                                    </label>
                                    <select name="countty_tutor" required
                                        class="text-[#AAAAAA] w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}" {{ old('countty_tutor', $tutorProfile->tutor_country ?? '') == $country->name ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.Native-Language', __('site.Native Language')) }}
                                    </label>
                                    <select name="language" required
                                        class="text-[#AAAAAA] w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->name }}" {{ old('language', $tutorProfile->native_language ?? '') == $language->name ? 'selected' : '' }}>
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.register-teaching-subject-label', __('site.Teaching Subject')) }}
                                    </label>
                                    <select name="teaching_subject" required
                                        class="text-[#AAAAAA] w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->name }}" {{ old('teaching_subject', $tutorProfile->teaching_subject ?? '') == $subject->name ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Experience -->
                            <div class="grid md:grid-cols-2 gap-6 mb-2">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ label_text('global', 'site.register-teaching-experience-label', __('site.Teaching Experience')) }}
                                    </label>
                                    <select name="teaching_experience" required
                                        class="text-[#AAAAAA] w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach ($experiences as $experience)
                                            <option value="{{ $experience->name }}" {{ old('teaching_experience', $tutorProfile->teaching_experience ?? '') == $experience->name ? 'selected' : '' }}>
                                                {{ $experience->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[#AAAAAA]">
                                        {{ label_text('global', 'site.register-teaching-experience-help', __('site.e.g., 1–2 years, 3–5 years, 5–7 years…')) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Situation -->
                            <div class="mb-2">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-situation-label', __('site.Situation')) }}
                                </label>
                                <select name="situation" required
                                    class="text-[#AAAAAA] w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    @foreach ($situations as $situation)
                                        <option value="{{ $situation->name }}" {{ old('situation', $tutorProfile->situation ?? '') == $situation->name ? 'selected' : '' }}>
                                            {{ $situation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Tutor's Biography -->
                <div class="pane hidden" data-step="4">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step4-heading', __('site.Biography Information')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step4-subtitle', __('site.Set up your account basic information')) }}
                            </p>
                        </div>

                        <div class="mb-6">

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-headline-label', __('site.Headline')) }}
                                </label>
                                <textarea rows="3" name="headline" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">{{ old('headline', $tutorProfile->headline ?? '') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-interests-label', __('site.Interests')) }}
                                </label>
                                <textarea rows="3" name="interests" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">{{ old('interests', $tutorProfile->interests ?? '') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-motivation-label', __('site.Motivation')) }}
                                </label>
                                <textarea rows="3" name="motivation" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">{{ old('motivation', $tutorProfile->motivation ?? '') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.Specializations', __('site.Specializations')) }}
                                </label>
                                <select name="specializations" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">
                                    @foreach ($specializations as $specialization)
                                        <option value="{{ $specialization->name }}" {{ old('specializations', $tutorProfile->specializations ?? '') == $specialization->name ? 'selected' : '' }}>
                                            {{ $specialization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-experience-label', __('site.Experience')) }}
                                </label>
                                <textarea rows="3" name="experience" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">{{ old('experience', $tutorProfile->experience_bio ?? '') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-methodology-label', __('site.Methodology')) }}
                                </label>
                                <textarea rows="3" name="methodology" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-[#F3F5FA] transition-all">{{ old('methodology', $tutorProfile->methodology ?? '') }}</textarea>
                            </div>

                        </div>


                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Tutor's Availability -->
                <div class="pane hidden" data-step="5">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step5-heading', __('site.Daily Availability Time')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step5-subtitle', __('site.Set up your daily time availability')) }}
                            </p>
                        </div>
                        @php
                            $days = [
                                'sunday' => __('site.Sunday'),
                                'monday' => __('site.Monday'),
                                'tuesday' => __('site.Tuesday'),
                                'wednesday' => __('site.Wednesday'),
                                'thursday' => __('site.Thursday'),
                                'friday' => __('site.Friday'),
                                'saturday' => __('site.Saturday'),
                            ];
                        
                            $availability = $availability ?? [];
                        @endphp
                    

                    <div class="mx-auto">

                        @foreach($days as $dayKey => $dayLabel)
                            @php
                                $ranges = $availability[$dayKey] ?? [];
                                $isAvailable = count($ranges) > 0;
                            @endphp
                        
                            <section class="day-card bg-white border border-gray-200 rounded-2xl p-5 shadow-card mb-5"
                                data-day="{{ $dayKey }}">
                        
                                {{-- HEADER --}}
                                <header class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-[18px] font-semibold text-gray-900">
                                            {{ $dayLabel }}
                                        </h3>
                        
                                        <span
                                            class="badge inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-medium
                                            {{ $isAvailable ? 'bg-blue-100 text-primary' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $isAvailable ? __('site.Available') : __('site.Unavailable') }}
                                        </span>
                                    </div>
                        
                                    <button type="button"
                                        class="toggle relative w-[46px] h-[26px] rounded-full
                                        {{ $isAvailable ? 'bg-primary' : 'bg-gray-300' }}"
                                        aria-pressed="{{ $isAvailable ? 'true' : 'false' }}">
                                        <span
                                            class="knob absolute w-[20px] h-[20px] bg-white rounded-full top-[3px]
                                            {{ $isAvailable ? 'right-[3px]' : 'left-[3px]' }} transition-all">
                                        </span>
                                    </button>
                                </header>
                        
                                {{-- RANGES --}}
                                <div class="ranges space-y-4 w-full md:w-3/4 {{ $isAvailable ? '' : 'hidden' }}">
                        
                                    @foreach($ranges as $range)
                                        <div class="range-row grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-4 items-end">
                        
                                            {{-- FROM --}}
                                            <div>
                                                <label class="block text-[13px] text-gray-800 mb-1">
                                                    {{ __('site.From') }}
                                                </label>
                                                <div class="relative">
                                                    <input type="time" value="{{ $range['from'] }}" required
                                                        class="hide-date-icon bg-[#F3F5FA] time-input w-full text-[16px] px-4 py-3 pl-11 rounded-lg border border-primary/35">
                                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 19C4.26 19 0 14.739 0 9.5S4.26 0 9.5 0 19 4.261 19 9.5 14.738 19 9.5 19Zm0-17.48A7.98 7.98 0 1 0 17.48 9.5 7.99 7.99 0 0 0 9.5 1.52Zm-.061 8.679H4.56a.78.78 0 0 1 0-1.56h4.119V3.04a.78.78 0 1 1 1.56 0V9.44a.78.78 0 0 1-.78.76Z" fill="#1B449C"/></svg>
                                                        </span>
                                                </div>
                                            </div>
                        
                                            {{-- TO --}}
                                            <div>
                                                <label class="block text-[13px] text-gray-800 mb-1">
                                                    {{ __('site.To') }}
                                                </label>
                                                <div class="relative">
                                                    <input type="time" value="{{ $range['to'] }}" required
                                                        class="hide-date-icon bg-[#F3F5FA] time-input w-full text-[16px] px-4 py-3 pl-11 rounded-lg border border-primary/35">
                                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 19C4.26 19 0 14.739 0 9.5S4.26 0 9.5 0 19 4.261 19 9.5 14.738 19 9.5 19Zm0-17.48A7.98 7.98 0 1 0 17.48 9.5 7.99 7.99 0 0 0 9.5 1.52Zm-.061 8.679H4.56a.78.78 0 0 1 0-1.56h4.119V3.04a.78.78 0 1 1 1.56 0V9.44a.78.78 0 0 1-.78.76Z" fill="#1B449C"/></svg>
                                                          </span>
                                                </div>
                                            </div>
                        
                                            {{-- DELETE --}}
                                            <div class="flex md:justify-center">
                                                <button type="button" class="btn-remove w-11 h-11 rounded-lg border border-gray-200 hover:bg-gray-50 grid place-items-center">
                                                    <svg width="22" height="30" viewBox="0 0 22 30" fill="none" xmlns="http://www.w3.org/2000/svg"> 
                                                      <path fill-rule="evenodd" clip-rule="evenodd" d="M5.17099 25.1213L3.74128 14.3343C3.58992 13.1914 4.90475 12.4222 5.82678 13.1284C5.9631 13.2324 6.07752 13.3624 6.16347 13.5108C6.24943 13.6592 6.30523 13.8232 6.32767 13.9932L7.75738 24.7802C7.77989 24.9497 7.76871 25.122 7.72446 25.2872C7.68022 25.4525 7.60379 25.6073 7.49955 25.7429C6.79181 26.6669 5.32272 26.2665 5.17099 25.1213ZM4.5306 14.2314L5.96022 25.0183C5.9789 25.1513 6.04945 25.2716 6.15647 25.3527C6.26348 25.4339 6.39827 25.4695 6.5314 25.4516C6.66452 25.4337 6.78518 25.3639 6.86701 25.2574C6.94884 25.1509 6.9852 25.0163 6.96816 24.8831L5.53863 14.0961C5.52069 13.9624 5.45039 13.8414 5.34319 13.7595C5.23598 13.6777 5.10066 13.6418 4.96699 13.6597C4.83332 13.6777 4.71224 13.748 4.6304 13.8552C4.54856 13.9624 4.51266 14.0977 4.5306 14.2314ZM15.0172 25.0183L16.4468 14.2314C16.4647 14.0977 16.4288 13.9624 16.347 13.8552C16.2652 13.748 16.1441 13.6777 16.0104 13.6597C15.8767 13.6418 15.7414 13.6777 15.6342 13.7595C15.527 13.8414 15.4567 13.9624 15.4388 14.0961L14.0092 24.8831C13.9922 25.0163 14.0286 25.1509 14.1104 25.2574C14.1922 25.3639 14.3129 25.4337 14.446 25.4516C14.5791 25.4695 14.7139 25.4339 14.8209 25.3527C14.928 25.2716 14.9985 25.1513 15.0172 25.0183ZM17.236 14.3343L15.8064 25.1213C15.7839 25.2926 15.7276 25.4578 15.6407 25.6071C15.5538 25.7565 15.4381 25.8872 15.3003 25.9914C15.1625 26.0957 15.0054 26.1716 14.838 26.2147C14.6707 26.2578 14.4964 26.2671 14.3254 26.2423C14.1544 26.2174 13.99 26.1589 13.8418 26.07C13.6936 25.9811 13.5646 25.8636 13.4621 25.7244C13.3597 25.5852 13.286 25.4271 13.2452 25.2592C13.2044 25.0912 13.1974 24.9169 13.2245 24.7462L14.6499 13.9932C14.8017 12.8475 16.2713 12.4483 16.9784 13.3715C17.0826 13.5071 17.1591 13.662 17.2033 13.8272C17.2476 13.9924 17.2588 14.1647 17.2363 14.3343H17.236ZM20.1269 9.91273H0.850995L3.28041 28.2438C3.31414 28.5098 3.44426 28.7543 3.64613 28.9308C3.848 29.1074 4.10761 29.2038 4.37579 29.2018H16.6012C16.8694 29.2038 17.129 29.1074 17.3309 28.9308C17.5328 28.7543 17.6629 28.5098 17.6966 28.2438L20.1269 9.91273ZM20.5812 9.11418L0.396635 9.11518C0.340126 9.1155 0.284327 9.12779 0.232915 9.15124C0.181503 9.1747 0.135646 9.20879 0.0983686 9.25126C0.0610906 9.29373 0.0332378 9.34362 0.0166466 9.39763C5.53788e-05 9.45165 -0.0048984 9.50858 0.00211344 9.56465L2.49163 28.3463C2.55084 28.804 2.77486 29.2245 3.12176 29.5289C3.46866 29.8333 3.91464 30.0008 4.37616 30H16.6012C17.0628 30.0008 17.5089 29.8334 17.8558 29.529C18.2028 29.2246 18.4269 28.8041 18.4861 28.3463L20.9709 9.5986C20.9838 9.54027 20.9835 9.47978 20.9699 9.4216C20.9563 9.36341 20.9298 9.30904 20.8923 9.26249C20.8548 9.21594 20.8074 9.17842 20.7535 9.1527C20.6995 9.12698 20.6405 9.11372 20.5808 9.11391L20.5812 9.11418ZM11.0016 24.9971C11.0016 25.666 9.98473 25.6671 9.98419 24.9986L9.97559 14.1171C9.97559 13.4483 10.9925 13.4472 10.993 14.1158L11.0021 24.9972L11.0016 24.9971ZM11.7885 14.1157L11.797 24.9971C11.7984 26.7146 9.1898 26.7162 9.18872 24.9986L9.18021 14.1171C9.17885 12.3996 11.7885 12.3982 11.7885 14.1158V14.1157ZM1.57278 2.50401L20.2989 7.52188L20.5578 6.55559C20.6262 6.2993 20.5901 6.02635 20.4575 5.79659C20.325 5.56684 20.1067 5.39905 19.8506 5.33002L3.05762 0.830324C2.80133 0.761899 2.52836 0.797916 2.29859 0.930476C2.06882 1.06304 1.90101 1.28132 1.83196 1.53744L1.57296 2.50419L1.57278 2.50401ZM13.5267 2.80972L9.75615 1.79943L9.9057 1.31411C9.94184 1.17882 10.0295 1.06304 10.1498 0.991501C10.2702 0.919962 10.4138 0.898343 10.5499 0.931263L13.311 1.67115C13.441 1.70332 13.5532 1.78503 13.6238 1.89883C13.6943 2.01263 13.7176 2.1495 13.6886 2.28022L13.5265 2.80972H13.5267ZM8.98657 1.5932L9.14454 1.08009C9.24191 0.748085 9.46424 0.466777 9.76476 0.295327C10.0653 0.123877 10.4206 0.0756463 10.7559 0.160779L13.517 0.900755C14.2313 1.10399 14.6603 1.82722 14.4457 2.53026L14.2961 3.01558L20.0562 4.55899C20.5159 4.68394 20.9076 4.98558 21.1459 5.39816C21.3842 5.81073 21.4497 6.30077 21.3282 6.76145L20.9661 8.11303C20.9387 8.21519 20.872 8.30231 20.7804 8.35526C20.6889 8.40821 20.5801 8.42264 20.4779 8.39539L0.981446 3.1712C0.930835 3.15773 0.883376 3.13441 0.841788 3.10257C0.8002 3.07074 0.765297 3.03101 0.739079 2.98567C0.712861 2.94034 0.695842 2.89027 0.688996 2.83835C0.682149 2.78642 0.685609 2.73366 0.699179 2.68307L1.06129 1.3314C1.18628 0.87177 1.48787 0.480176 1.90034 0.24196C2.31281 0.00374348 2.80272 -0.0617837 3.2633 0.0596593L8.98657 1.5932Z" fill="#AAAAAA"/>
                                                     </svg>
                                                </button>
                                            </div>
                        
                                        </div>
                                    @endforeach
                        
                                </div>
                        
                                {{-- EMPTY STATE --}}
                                <div class="empty-state {{ $isAvailable ? 'hidden' : '' }}">
                                    <div
                                        class="inline-flex items-center px-4 py-3 rounded-lg border border-dashed border-gray-300 text-gray-400 text-[15px]">
                                        {{ __('site.No ranges yet') }}
                                    </div>
                                </div>
                        
                                {{-- ADD RANGE --}}
                                <div class="mt-4">
                                    <button type="button"
                                        class="btn-add inline-flex items-center gap-2 text-[15px]
                                        {{ $isAvailable ? 'text-gray-800 hover:bg-gray-50' : 'text-gray-400 cursor-not-allowed' }}
                                        border border-gray-200 rounded-lg px-4 py-2"
                                        {{ $isAvailable ? '' : 'disabled' }}>
                                        <span class="text-xl">+</span>
                                        {{ __('site.Add time range') }}
                                    </button>
                                </div>
                        
                            </section>
                        @endforeach
                        
                        </div>
                        


                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Tutor's Hourly Rate -->
                <div class="pane hidden" data-step="6">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step6-heading', __('site.Price Information')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step6-subtitle', __('site.Set up your account basic information')) }}
                            </p>
                        </div>

                        <div class="mb-6">
                            <div class="mb-6">
                                <!-- <label class="block text-sm font-semibold text-gray-900 mb-2">Hourly Rate</label> -->
                                <input type="number" min="0" step="any"
                                    placeholder="{{ label_text('global', 'site.register-hourly-rate-placeholder', __('site.Hourly Rate')) }}"
                                    name="hourly_rate" required
                                    value="{{ old('hourly_rate', $tutorProfile->hourly_rate ?? '') }}"
                                    class="w-full px-4 py-8 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:bg-[#F3F5FA] focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Tutor's Qualifications & Certificates -->
                <div class="pane hidden" data-step="7">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step7-heading', __('site.Certification Information')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step7-subtitle', __('site.Set up your certification information')) }}
                            </p>
                        </div>
                        @php
                            $cert0 = old('cert0') ? old('cert0') : ($certifications[0] ?? []);
                            $certFilePath = $cert0['file_path'] ?? null;

                            $certFileUrl = $certFilePath ? asset('storage/' . $certFilePath) : null;

                            $certIsImage = $certFilePath
                                ? in_array(strtolower(pathinfo($certFilePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'])
                                : false;
                        @endphp

                        <!-- Certification Form -->
                        <div class="mb-6 space-y-6">

                            <!-- Certification Subject -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-cert-subject-label', __('site.Certification Subject')) }}
                                </label>

                                <select name="certification_subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none bg-[url('data:image/svg+xml;utf8,<svg width=\'20\' height=\'40\' viewBox=\'0 0 20 40\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M15.8327 27.0835L9.99935 32.9168L4.16602 27.0835\' stroke=\'%23AAAAAA\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/><path d=\'M15.8327 12.9165L9.99935 7.08317L4.16602 12.9165\' stroke=\'%23AAAAAA\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>')] bg-[right_1rem_center] bg-no-repeat">
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->name }}" {{ old('certification_subject', $cert0['subject'] ?? '') == $subject->name ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Certification Name -->
                            <div>
                                <input type="text"
                                    placeholder="{{ label_text('global', 'site.register-cert-name-placeholder', __('site.Certification Name')) }}"
                                    name="certification_name" required
                                    value="{{ old('certification_name', $cert0['name'] ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>

                            <!-- Certification Description -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-cert-description-label', __('site.Certification Description')) }}
                                </label>
                                <textarea rows="3"
                                    placeholder="{{ label_text('global', 'site.register-cert-description-placeholder', __('site.Up to 200 characters')) }}"
                                    name="certification_description" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none">{{ old('certification_description', $cert0['description'] ?? '') }}</textarea>
                            </div>

                            <!-- Certification Issued By -->
                            <div>
                                <input type="text"
                                    placeholder="{{ label_text('global', 'site.register-cert-issued-by-placeholder', __('site.Certification Issued By')) }}"
                                    name="certification_issued_by" required
                                    value="{{ old('certification_issued_by', $cert0['issued_by'] ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>

                            <!-- Years -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="relative">
                                    <input type="number" id="yearFrom"
                                        placeholder="{{ label_text('global', 'site.register-cert-year-from-placeholder', __('site.Certification Year From')) }}"
                                        name="certification_year_from" required
                                        value="{{ old('certification_year_from', $cert0['year_from'] ?? '') }}"
                                        class="hide-number-spin w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                                </div>
                                <div class="relative">
                                    <input type="number" id="yearTo"
                                        placeholder="{{ label_text('global', 'site.register-cert-year-to-placeholder', __('site.Certification Year To')) }}"
                                        name="certification_year_to" required
                                        value="{{ old('certification_year_to', $cert0['year_to'] ?? '') }}"
                                        class="hide-number-spin w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">

                                    <!-- Up/Down SVG Buttons -->
                                    <div class="absolute inset-y-0 right-3 flex flex-col justify-center">
                                        <button type="button" id="yearUp"
                                            class="w-4 h-4 flex items-center justify-center hover:text-primary transition">
                                            <svg width="20" height="20" viewBox="0 0 20 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.8327 12.9165L9.99935 7.08317L4.16602 12.9165"
                                                    stroke="#AAAAAA" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button type="button" id="yearDown"
                                            class="w-4 h-4 flex items-center justify-center hover:text-primary transition">
                                            <svg width="20" height="20" viewBox="0 0 20 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.8327 27.0835L9.99935 32.9168L4.16602 27.0835"
                                                    stroke="#AAAAAA" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Certification File (Link + Image Preview) -->
                            @if($certFileUrl)
                                <div class="space-y-3">
                                    <a href="{{ $certFileUrl }}" target="_blank"
                                        class="inline-flex items-center gap-2 text-primary hover:underline text-sm">
                                        View current certification file
                                    </a>

                                    @if($certIsImage)
                                        <div class="border border-gray-200 rounded-lg p-3 bg-white w-fit">
                                            <img src="{{ $certFileUrl }}" alt="Certification Preview"
                                                class="max-w-[260px] rounded" style="max-width: 260px;">
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Certification File Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-cert-file-label', __('site.Certification File')) }}
                                </label>

                                <label
                                    class="relative block w-full cursor-pointer border border-gray-300 rounded-lg overflow-hidden hover:border-primary transition">
                                    <input type="file" id="certFile" name="certification_file" {{ $certFileUrl ? '' : 'required' }} class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <span id="fileName" class="block px-4 py-3 text-sm text-gray-500">
                                        {{ $certFileUrl ? 'Replace current file (optional)' : label_text('global', 'site.register-cert-file-placeholder', __('site.Choose the file')) }}
                                    </span>
                                </label>
                            </div>
                        </div>


                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="button"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-continue', __('site.Continue →')) }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 8: Tutor Profile Video -->
                <div class="pane hidden" data-step="8">
                    <div class="mx-auto">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                {{ label_text('global', 'site.register-step8-heading', __('site.Tutor Video')) }}
                            </h1>
                            <p class="text-gray-600 mb-8">
                                {{ label_text('global', 'site.register-step8-subtitle', __('site.Upload a video that introduce you as a teacher. What can you offer, and why students should choose you as their teacher')) }}
                            </p>
                        </div>
                        @php
                            $videoPath = $tutorProfile->video_path ?? null;
                            $videoUrl = $videoPath ? asset('storage/' . $videoPath) : null;
                        @endphp

                        <div class="mb-6 space-y-6">

                            <!-- Existing Video -->
                            @if($videoUrl)
                                <div class="space-y-3">
                                    <a href="{{ route('show_video', ['path' => $videoPath, 'type' => 'user']) }}" target="_blank"
                                        class="inline-flex items-center gap-2 text-primary hover:underline text-sm">
                                        Open current video
                                    </a>
{{-- 
                                    <video controls class="w-full max-w-xl rounded-lg border border-gray-200 bg-black">
                                        <source src="{{ $videoUrl }}">
                                        Your browser does not support the video tag.
                                    </video> --}}
                                </div>
                            @endif

                            <!-- Video File -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ label_text('global', 'site.register-video-file-label', __('site.Video File')) }}
                                </label>
                                <label
                                    class="relative block w-full cursor-pointer border border-gray-300 rounded-lg overflow-hidden hover:border-primary transition">
                                    <input type="file" accept="video/*" id="videoFile" name="video_file" {{ $videoUrl ? '' : 'required' }}
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <span id="videoFileName" class="block px-4 py-3 text-sm text-gray-500">
                                        {{ $videoUrl ? 'Replace current video (optional)' : label_text('global', 'site.register-video-file-placeholder', __('site.Choose the file')) }}
                                    </span>
                                </label>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="flex items-center gap-2">
                                <input id="agreeTerms" type="checkbox" name="agree_terms" value="1" required {{ old('agree_terms', ($tutorProfile->video_terms_agreed ?? false) ? 1 : null) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30">
                                <label for="agreeTerms" class="text-sm text-gray-700">
                                    {{ label_text('global', 'site.register-agree-terms', __('site.Agree Terms')) }}
                                    <a href="#" class="text-primary hover:underline">
                                        {{ label_text('global', 'site.register-terms-and-conditions', __('site.Terms And Conditions')) }}
                                    </a>
                                </label>
                            </div>
                        </div>


                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button type="button"
                                class="cursor-pointer btn-back px-8 py-3 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                {{ label_text('global', 'site.register-back', __('site.← Back')) }}
                            </button>
                            <button type="submit"
                                class="cursor-pointer btn-continue bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                                {{ label_text('global', 'site.register-submit', __('site.Submit')) }}
                                <i class="fa-solid fa-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="{{ asset('front/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front/assets/js/main.js') }}"></script>
    <script>
        const _token = "{{ csrf_token() }}";
        const emailCheckUrl = "{{ route('auth.email-check') }}";
    </script>
    <script src="{{ asset('front/assets/js/profie.js') }}"></script>
</body>

</html>