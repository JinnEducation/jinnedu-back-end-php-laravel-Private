<!-- ================= Login Modal ================= -->
<div id="loginModal"
    class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300"
    aria-hidden="true">
    <!-- Wrapper -->
    <div
        class="modal-panel relative w-[400px] max-w-[90%] scale-95 opacity-0 rounded-sm bg-white shadow-2xl transition-all duration-300">
        <!-- Close Button -->
        <button type="button" class="absolute right-4 top-4 text-gray-400 hover:text-gray-700 focus:outline-none"
            data-close id="btn-close-loginModal">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Body -->
        <div class="px-8 py-16">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center mb-6">
                {{ label_text('global', 'site.login-title', __('site.Log in')) }}
            </h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ label_text('global', 'site.Email', __('site.Email')) }}
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" class="w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.90727 0C0.860697 0 0 0.870415 0 1.9288V12.5408C0 13.5991 0.860697 14.4696 1.90727 14.4696H14.7854C15.832 14.4696 16.6927 13.5991 16.6927 12.5408V1.9288C16.6927 0.870415 15.832 0 14.7854 0H1.90727ZM1.90727 1.2058H14.7854C14.8978 1.2058 15.001 1.23211 15.094 1.27527C13.1127 3.13681 10.9999 5.09714 8.9495 7.00869C8.63238 7.3044 8.09723 7.30335 7.77463 7.00634L1.56727 1.29176C1.66783 1.23865 1.78182 1.2058 1.90727 1.2058ZM15.5004 2.54112V12.5408C15.5004 12.952 15.1921 13.2638 14.7854 13.2638H1.90727C1.50063 13.2638 1.19233 12.952 1.19233 12.5408V2.57645L6.97236 7.89773C7.75662 8.61978 8.97542 8.6258 9.75875 7.89538C11.6519 6.10496 13.6085 4.32728 15.5004 2.54112Z"
                                    fill="#AAAAAA" />
                            </svg>
                        </span>
                        <input type="email" name="email" id="email"
                            placeholder="{{ label_text('global', 'site.email-placeholder', __('site.name@email.com')) }}"
                            class="w-full h-11 pl-10 pr-3 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                    <!-- Email Validation Message -->
                    <p id="email-msg"
                        class="text-xs text-red-500 mt-1 hidden transition-all duration-300">
                        {{ label_text(
                            'global',
                            'site.register-email-not-found',
                            __('site.register-email-not-found')
                        ) }}
                        
                    </p>
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ label_text('global', 'site.Password', __('site.Password')) }}
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg width="16" height="21" class="w-5 h-5" viewBox="0 0 16 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.33155 0.000483697C8.89273 0.0817139 9.43757 0.253589 9.94377 0.509167C11.4624 1.2546 12.4738 2.74746 12.6031 4.43398C12.669 5.52464 12.6793 6.61778 12.6343 7.70944C12.6353 8.09974 12.3197 8.41673 11.9294 8.41722C11.9136 8.41722 11.8978 8.41673 11.8819 8.41574C11.4862 8.40237 11.1766 8.07051 11.1905 7.67476C11.1905 7.67328 11.1905 7.67179 11.1905 7.6708C11.1855 6.75548 11.1905 5.84015 11.1905 4.92334C11.2043 3.007 9.66193 1.44183 7.7451 1.42796C6.00311 1.41558 4.52165 2.69595 4.28291 4.4216C4.24131 4.98674 4.23041 5.55336 4.24924 6.11949C4.24032 6.64551 4.24924 7.17152 4.24924 7.69704C4.28391 8.09725 3.98772 8.45041 3.58751 8.48508C3.1873 8.51975 2.83415 8.22356 2.79948 7.82335C2.79601 7.78125 2.79601 7.73914 2.79948 7.69704C2.79948 6.76834 2.79403 5.83966 2.79948 4.91046C2.797 2.45771 4.59744 0.375928 7.02493 0.0242604C7.05168 0.0183168 7.07793 0.00990612 7.10368 0H8.33303L8.33155 0.000483697Z"
                                    fill="#AAAAAA" />
                                <path
                                    d="M7.70312 20.9991C5.8591 20.9991 4.01507 20.9991 2.17105 20.9991C1.00906 21.0348 0.0387763 20.1214 0.00311428 18.9599C0.00212367 18.9208 0.00160974 18.8812 0.00260036 18.842C-0.000866785 16.5334 -0.000866785 14.2248 0.00260036 11.9162C-0.0132494 10.8899 0.712371 10.0013 1.72081 9.81161C1.94271 9.77249 2.16758 9.75514 2.39294 9.7596C5.94478 9.75663 9.49662 9.75515 13.0485 9.75564C13.8201 9.69422 14.5695 10.034 15.0322 10.6551C15.2902 11.0043 15.4299 11.4268 15.4309 11.8607C15.4348 14.2099 15.4388 16.5602 15.4309 18.9094C15.4269 20.0669 14.4853 21.0021 13.3283 20.9981C13.3258 20.9981 13.3229 20.9981 13.3204 20.9981C11.4491 20.9981 9.57686 20.9981 7.7056 20.9981H7.70312V20.9991ZM7.71947 19.5751H13.0668C13.1896 19.5786 13.3125 19.5686 13.4333 19.5459C13.7493 19.4934 13.9806 19.219 13.9782 18.8985C13.9826 18.6934 13.9782 18.4889 13.9782 18.2838C13.9782 16.1808 13.9782 14.0772 13.9782 11.9736C14.0014 11.724 13.8915 11.4803 13.6894 11.3322C13.5185 11.2297 13.3189 11.1841 13.1203 11.2029C9.48771 11.2108 5.85465 11.1886 2.22207 11.2282C1.68615 11.2336 1.44743 11.4684 1.44644 12.0029C1.44644 13.3753 1.44644 14.7478 1.44644 16.1208C1.44644 17.0361 1.44644 17.9515 1.44644 18.8683C1.42316 19.213 1.67675 19.5147 2.02049 19.5513C2.13491 19.5696 2.2508 19.5781 2.3667 19.5766C4.14931 19.5766 5.93141 19.5766 7.71352 19.5766L7.71947 19.5751Z"
                                    fill="#AAAAAA" />
                                <path
                                    d="M8.428 15.4098C8.428 15.6451 8.43295 15.8873 8.428 16.125C8.42304 16.497 8.12784 16.8006 7.75587 16.816C7.37646 16.8487 7.04212 16.5673 7.00943 16.1879C6.9604 15.6723 6.9604 15.1532 7.00943 14.6376C7.04807 14.3127 7.30714 14.0586 7.63256 14.0264C7.93222 13.9952 8.21998 14.1512 8.35768 14.4187C8.40721 14.5158 8.43196 14.6237 8.42899 14.7327C8.42899 14.9571 8.42899 15.1824 8.42899 15.4068L8.428 15.4098Z"
                                    fill="#AAAAAA" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password"
                            class="w-full h-11 pl-10 pr-10 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                            placeholder="{{ label_text('global', 'site.Password', __('site.Password')) }}">
                        <button type="button" data-toggle-password="#password"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <svg class="eye w-5 h-5 hidden" width="17" height="17" viewBox="0 0 17 17"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.22999 14.1898C6.27626 14.1898 4.32252 13.4468 2.83658 11.9609L0.73609 9.86038C-0.245363 8.87893 -0.245363 7.27375 0.73609 6.29229L2.83658 4.1918C5.80846 1.21992 10.6515 1.21992 13.6234 4.1918L15.7239 6.29229C16.7053 7.27375 16.7053 8.87893 15.7239 9.86038L13.6234 11.9609C12.1375 13.4468 10.1837 14.1898 8.22999 14.1898ZM8.22999 3.32959C6.62481 3.32959 5.0288 3.93497 3.80886 5.15491L1.70837 7.2554C1.25892 7.70485 1.25892 8.42948 1.70837 8.87893L3.80886 10.9794C6.24874 13.4193 10.2112 13.4193 12.6511 10.9794L14.7516 8.87893C15.2011 8.42948 15.2011 7.70485 14.7516 7.2554L12.6511 5.15491C11.4312 3.93497 9.83517 3.32959 8.22999 3.32959Z"
                                    fill="black" />
                                <path
                                    d="M8.22933 11.5016C6.3398 11.5016 4.79883 9.96065 4.79883 8.07112C4.79883 6.1816 6.3398 4.64062 8.22933 4.64062C10.1189 4.64062 11.6598 6.1816 11.6598 8.07112C11.6598 9.96065 10.1189 11.5016 8.22933 11.5016ZM8.22933 6.02567C7.10112 6.02567 6.1747 6.94291 6.1747 8.0803C6.1747 9.21768 7.09194 10.1349 8.22933 10.1349C9.36671 10.1349 10.284 9.21768 10.284 8.0803C10.284 6.94291 9.36671 6.02567 8.22933 6.02567Z"
                                    fill="black" />
                            </svg>
                            <svg class="eye-off w-5 h-5" width="17" height="17" viewBox="0 0 17 17"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.22999 14.1898C6.27626 14.1898 4.32252 13.4468 2.83658 11.9609L0.73609 9.86038C-0.245363 8.87893 -0.245363 7.27375 0.73609 6.29229L2.83658 4.1918C5.80846 1.21992 10.6515 1.21992 13.6234 4.1918L15.7239 6.29229C16.7053 7.27375 16.7053 8.87893 15.7239 9.86038L13.6234 11.9609C12.1375 13.4468 10.1837 14.1898 8.22999 14.1898ZM8.22999 3.32959C6.62481 3.32959 5.0288 3.93497 3.80886 5.15491L1.70837 7.2554C1.25892 7.70485 1.25892 8.42948 1.70837 8.87893L3.80886 10.9794C6.24874 13.4193 10.2112 13.4193 12.6511 10.9794L14.7516 8.87893C15.2011 8.42948 15.2011 7.70485 14.7516 7.2554L12.6511 5.15491C11.4312 3.93497 9.83517 3.32959 8.22999 3.32959Z"
                                    fill="black" />
                                <path
                                    d="M8.22933 11.5016C6.3398 11.5016 4.79883 9.96065 4.79883 8.07112C4.79883 6.1816 6.3398 4.64062 8.22933 4.64062C10.1189 4.64062 11.6598 6.1816 11.6598 8.07112C11.6598 9.96065 10.1189 11.5016 8.22933 11.5016ZM8.22933 6.02567C7.10112 6.02567 6.1747 6.94291 6.1747 8.0803C6.1747 9.21768 7.09194 10.1349 8.22933 10.1349C9.36671 10.1349 10.284 9.21768 10.284 8.0803C10.284 6.94291 9.36671 6.02567 8.22933 6.02567Z"
                                    fill="black" />
                                <line x1="14.1086" y1="1.04768" x2="3.25011" y2="15.9781" stroke="black"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-5">
                    <button type="button" class="text-sm text-primary hover:underline" data-open="#forgotModal">
                        {{ label_text('global', 'site.forgot-password-button', __('site.Forgot Password ?')) }}
                    </button>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember_me" class="rounded border-gray-300 text-primary focus:ring-primary">
                        {{ label_text('global', 'site.remember-me', __('site.Remember Me')) }}
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full h-11 rounded-lg bg-primary text-white font-semibold hover:brightness-95 transition-all duration-150">
                    {{ label_text('global', 'site.login', __('site.login')) }}
                </button>

            </form>
            <!-- Separator -->
            <div class="flex items-center my-4">
                <div class="flex-1 h-px bg-gray-300"></div>
                <span class="px-3 text-sm font-bold text-black">
                    {{ label_text('global', 'site.or', __('site.Or')) }}
                </span>
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            <!-- Google Button -->
            <button onclick="openGooglePopup()"
                class="relative w-full h-11 rounded-lg border border-gray-300 hover:bg-primary hover:text-white flex items-center justify-center font-medium text-gray-700 transition-all duration-350 courser-p">
                <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google"
                    class="w-5 h-5 absolute left-4">
                {{ label_text('global', 'site.sign-in-google', __('site.Sign in with Google')) }}
            </button>

            <!-- Sign Up -->
            <p class="mt-5 text-center text-sm text-gray-600">
                {{ label_text('global', 'site.new-to-jinn', __('site.New To Jinn?')) }}
                <a href="{{ route('register') }}" class="text-primary hover:underline">
                    {{ label_text('global', 'site.sign-up', __('site.sign-up')) }}
                </a>
            </p>
        </div>
    </div>
</div>

<!-- ========== Forgot Password Modal (Placeholder) ========== -->
<div id="forgotModal"
    class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300"
    aria-hidden="true">
    <!-- Wrapper -->
    <div
        class="modal-panel relative w-[400px] max-w-[90%] scale-95 opacity-0 rounded-sm bg-white shadow-2xl transition-all duration-300">
        <!-- Close Button -->
        <button type="button" class="absolute right-4 top-4 text-gray-400 hover:text-gray-700 focus:outline-none"
            data-close id="btn-close-loginModal">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Body -->
        <div class="px-8 py-16">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center mb-2">
                {{ label_text('global', 'site.forgot-password-title', __('site.Forgot password')) }}
            </h2>
            <p class="text-sm text-center font-light mb-4">
                {{ label_text('global', 'site.forgot-password-text', __("site.Enter the email address you use on Jinn. We'll send you a link to reset your password.")) }}
            </p>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ label_text('global', 'site.Email', __('site.Email')) }}
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg width="17" height="15" viewBox="0 0 17 15" fill="none" class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.90727 0C0.860697 0 0 0.870415 0 1.9288V12.5408C0 13.5991 0.860697 14.4696 1.90727 14.4696H14.7854C15.832 14.4696 16.6927 13.5991 16.6927 12.5408V1.9288C16.6927 0.870415 15.832 0 14.7854 0H1.90727ZM1.90727 1.2058H14.7854C14.8978 1.2058 15.001 1.23211 15.094 1.27527C13.1127 3.13681 10.9999 5.09714 8.9495 7.00869C8.63238 7.3044 8.09723 7.30335 7.77463 7.00634L1.56727 1.29176C1.66783 1.23865 1.78182 1.2058 1.90727 1.2058ZM15.5004 2.54112V12.5408C15.5004 12.952 15.1921 13.2638 14.7854 13.2638H1.90727C1.50063 13.2638 1.19233 12.952 1.19233 12.5408V2.57645L6.97236 7.89773C7.75662 8.61978 8.97542 8.6258 9.75875 7.89538C11.6519 6.10496 13.6085 4.32728 15.5004 2.54112Z"
                                fill="#AAAAAA" />
                        </svg>
                    </span>
                    <input type="email" id="email"
                        placeholder="{{ label_text('global', 'site.email-placeholder', __('site.name@email.com')) }}"
                        class="w-full h-11 pl-10 pr-3 rounded-lg border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
            </div>

            <!-- Login Button -->
            <button
                class="w-full h-11 rounded-lg bg-primary text-white font-semibold hover:brightness-95 transition-all duration-150">
                {{ label_text('global', 'site.login', __('site.login')) }}
            </button>

            <!-- Separator -->
            <div class="flex items-center my-4">
                <div class="flex-1 h-px bg-gray-300"></div>
                <span class="px-3 text-sm font-bold text-black">
                    {{ label_text('global', 'site.or', __('site.Or')) }}
                </span>
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            <!-- Google Button -->
            <button
                class="relative w-full h-11 rounded-lg border border-gray-300 hover:bg-primary hover:text-white flex items-center justify-center font-medium text-gray-700 transition-all duration-350 courser-p">
                {{ label_text('global', 'site.email-login-link', __('site.eMAIL ME A LOGIN LINK')) }}
            </button>

            <!-- Sign Up -->
            <p class="mt-5 text-center text-sm text-gray-600">
                {{ label_text('global', 'site.back-to', __('site.Back to')) }}
                <button type="button" class="text-primary hover:underline" data-open="#loginModal">
                    {{ label_text('global', 'site.Log-in', __('site.Log in')) }}
                </button>
            </p>
        </div>
    </div>
</div>

@push('scripts')
@if(request('login') == 1)
<script>
    $(document).ready(function() {
        setTimeout(() => {
            $("#btn-open-loginModal").click();
        }, 1000);
    });
</script>
@endif
<script>
    function openGooglePopup() {
        const width = 500;
        const height = 600;

        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        window.open(
            "{{ route('google.login') }}",
            "googleLogin",
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
        );
    }
</script>

<script>

window.addEventListener('message', function (event) {
    if (event.origin !== "{{ url('/') }}") return;

    const data = event.data;
    if (data.provider !== 'google') return;
    console.log(data)

    // إخفاء أي رسالة سابقة
    const emailMsg = document.getElementById('email-msg');
    emailMsg.classList.add('hidden');

    if (data.loginNow) {
        // مستخدم موجود → دخول مباشر
        window.location.href = "{{ route('home') }}";
        return;
    }

    // مستخدم غير موجود → أظهر رسالة
    // emailMsg.textContent = 'هذا البريد غير مسجّل، الرجاء إكمال إنشاء الحساب.';
    emailMsg.classList.remove('hidden');

    // فوكس على الإيميل (UX)
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.focus();
    }
});
</script>
@endpush