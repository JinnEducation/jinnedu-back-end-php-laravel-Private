@php
    $htmlLocale = str_replace('_', '-', $locale ?? app()->getLocale());
    $htmlDirection = $direction ?? 'ltr';
    $currentLangShort = strtoupper($currentLanguage->shortname ?? ($locale ?? app()->getLocale()));
@endphp
@include('layouts.head')

<!-- Universal RTL/LTR Header -->
@include('layouts.header')

<!-- Hero Section -->
{{ $slot }}

<!-- Footer -->
@include('layouts.footer')

<!-- زر واتساب عائم -->
<div class="fixed right-6 bottom-6 z-50 rtl:left-6 rtl:right-auto group">
    <a href="https://wa.me/1234567890?text=مرحبا، أحتاج مساعدة" target="_blank"
        class="flex relative justify-center items-center w-14 h-14 text-white bg-green-500 rounded-full shadow-lg transition-all duration-300 transform hover:bg-green-600 hover:scale-110 hover:shadow-xl group">
        <i class="text-2xl fab fa-whatsapp"></i>

        <!-- رسالة التوضيح من الجانب -->
        <div
            class="absolute top-1/2 right-full px-3 py-2 mr-3 text-sm text-white whitespace-nowrap bg-gray-800 rounded-lg shadow-lg opacity-0 transition-all duration-300 transform -translate-y-1/2 pointer-events-none rtl:left-full rtl:right-auto rtl:ml-3 rtl:mr-0 group-hover:opacity-100">
            {{ label_text('global', 'site.contact-whatsapp', __('site.Contact us on WhatsApp')) }}
            <!-- السهم الجانبي -->
            <div
                class="absolute top-1/2 left-full w-0 h-0 border-t-4 border-b-4 border-l-4 border-transparent transform -translate-y-1/2 rtl:right-full rtl:left-auto rtl:border-r-4 rtl:border-l-0 border-l-gray-800 rtl:border-r-gray-800">
            </div>
        </div>
    </a>
</div>

<!-- Direction Toggle Button -->
{{-- <div class="fixed bottom-4 z-50 start-4">
        <button id="direction-toggle"
            class="px-4 py-2 text-white rounded-lg shadow-lg transition-colors duration-300 bg-primary-600 hover:bg-primary-700">
            <i class="fas fa-exchange-alt me-2"></i>
            <span>RTL</span>
        </button>
    </div> --}}

<!-- ================= Login & Forgot Modal ================= -->
@include('layouts.partials.login')

<script src="{{ asset('front/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front/assets/js/main.js') }}"></script>

<x-show-message type="success" />
<x-show-message type="warning" />
<x-show-message type="danger" />
<x-show-message type="error" />
<x-show-message type="info" />

@stack('scripts')
</body>

</html>
