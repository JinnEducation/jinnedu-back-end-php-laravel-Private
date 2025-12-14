<x-front-layout>

    <!-- Complete Checkout Content -->
    <section class="pt-[140px] pb-16 bg-gray-50 min-h-screen">
        <div class="container px-4 mx-auto">

            <!-- Page Title -->
            <div class="mb-6 mt-14 text-center">
                <h1 class="mb-3 text-2xl font-bold text-black md:text-3xl">
                    @if($checkoutType === 'pay')
                        {{ label_text('global', 'Complete Payment', __('site.Complete Payment')) }}
                    @else
                        {{ label_text('global', 'Complete checkout', __('site.Complete checkout')) }}
                    @endif
                </h1>
                <div class="inline-block px-4 py-1.5 mb-3 text-xs text-black border border-gray-200 rounded-md">
                    {{ label_text('global', 'Your wallet', __('site.Your wallet')) }} : <span id="wallet-balance">{{ number_format($walletBalance, 2) }}</span> {{ label_text('global', 'USD', __('site.USD')) }}
                </div>
                <p class="text-xs text-gray-500">
                    @if($checkoutType === 'pay')
                        {{ label_text('global', 'Complete your payment: 1) Review items 2) Choose a payment method.', __('site.Complete your payment: 1) Review items 2) Choose a payment method.')) }}
                    @else
                        {{ label_text('global', 'Top up your wallet in two steps: 1) Enter amount 2) Choose a payment gateway.', __('site.Top up your wallet in two steps: 1) Enter amount 2) Choose a payment gateway.')) }}
                    @endif
                </p>
            </div>

            <!-- Main Card -->
            <div class="max-w-2xl mx-auto bg-white rounded-md border border-gray-200 shadow-md p-6 md:p-8">

                <!-- Hidden Fields -->
                <input type="hidden" name="checkout_type" id="checkout-type" value="{{ $checkoutType }}" data-checkout-url="{{ route('checkout.checkout') }}">
                <input type="hidden" name="order_ids" id="order-ids" value="{{ $orders->pluck('id')->implode(',') }}">

                <!-- Items Section (for payment mode) -->
                @if($checkoutType === 'pay')
                    @if($orders->isNotEmpty())
                    <div class="mb-6 bg-white border border-gray-300 rounded-lg">
                        <div class="flex justify-between items-center mb-4 bg-[#E9EDF6] p-4 border-b border-gray-300 rounded-t-lg">
                            <h3 class="text-base font-bold text-black">{{ label_text('global', 'Items', __('site.Items')) }}</h3>
                            <h3 class="text-base font-bold text-black">{{ label_text('global', 'Price', __('site.Price')) }}</h3>
                        </div>
                        <div class="space-y-4 px-4 pb-4" id="element">
                            @foreach($orders as $order)
                            <div class="flex justify-between items-center element-item" data-price="{{ $order->price }}">
                                <div class="flex flex-col">
                                    <span class="text-base text-black font-medium">{{ label_text('global', 'Order', __('site.Order')) }} #{{ $order->id }}</span>
                                    @if($order->status == 1)
                                        <span class="text-xs text-green-600">({{ label_text('global', 'Already Paid', __('site.Already Paid')) }})</span>
                                    @elseif($order->status == 2)
                                        <span class="text-xs text-red-600">({{ label_text('global', 'Failed', __('site.Failed')) }})</span>
                                    @else
                                        <span class="text-xs text-blue-600">({{ label_text('global', 'Pending', __('site.Pending')) }})</span>
                                    @endif
                                </div>
                                <span class="text-base text-black font-semibold">{{ number_format($order->price, 2) }}$</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    {{-- Show message if no orders found --}}
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800 mb-2">
                            <strong>⚠️ No orders found or orders are already paid.</strong>
                        </p>
                        @if(request()->has('order_ids'))
                        <p class="text-xs text-red-600">
                            Requested Order IDs: {{ request()->get('order_ids') }}
                        </p>
                        @endif
                        <p class="text-xs text-red-600 mt-2">
                            Please check your order status or contact support.
                        </p>
                    </div>
                    @endif
                @endif

                <!-- Wallet Amount -->
                <div class="mb-6">
                    <label class="block mb-2 text-base font-bold text-black">
                        @if($checkoutType === 'pay')
                            Total Amount
                        @else
                            Wallet amount
                        @endif
                    </label>
                    <div class="flex gap-3 items-center mb-3 relative">
                        <input type="number" 
                            id="wallet-amount" 
                            value="{{ $checkoutType === 'pay' ? number_format($totalAmount, 2, '.', '') : 10 }}" 
                            min="0"
                            {{ $checkoutType === 'pay' ? 'readonly' : '' }}
                            class="flex-1 px-4 py-3 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300 {{ $checkoutType === 'pay' ? 'bg-gray-50' : '' }}">
                        <span
                            class="px-4 py-3 text-base font-medium text-black bg-white absolute top-[1px] right-0 border-r border-gray-300 rounded-lg">
                            USD
                        </span>
                    </div>
                    <!-- Preset Amount Buttons (only for topup) -->
                    @if($checkoutType === 'topup')
                    <div class="flex gap-2 flex-wrap">
                        <button
                            class="preset-btn px-4 py-2 text-xs text-black bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200"
                            data-amount="10">
                            +10
                        </button>
                        <button
                            class="preset-btn px-4 py-2 text-xs text-black bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200"
                            data-amount="20">
                            +20
                        </button>
                        <button
                            class="preset-btn px-4 py-2 text-xs text-black bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200"
                            data-amount="50">
                            +50
                        </button>
                        <button
                            class="preset-btn px-4 py-2 text-xs text-black bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200"
                            data-amount="100">
                            +100
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Country Select -->
                <div class="mb-6">
                    <label class="block mb-3 text-base font-semibold text-black">
                        Payment Data
                    </label>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm text-black">
                            Country
                        </label>
                        <select id="country-select"
                            class="w-full px-4 py-3 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300">
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ $loop->first ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block mb-3 text-base font-semibold text-black">
                       {{ label_text('global', ' Payment method', __('site.Complete Payment')) }}
                    </label>
                    <input type="hidden" name="payment_gateway" id="payment-gateway">
                    
                    
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4" id="payment-gateway-container">
                        @foreach($paymentGateways as $gatewayCode => $gateway)
                            <div class="payment-gateway shadow-md cursor-pointer border-2 border-gray-200 rounded-md p-2.5 hover:border-primary transition-all duration-200 {{ $gatewayCode === 'my-wallet' && $checkoutType === 'pay' && $walletBalance < $totalAmount ? 'opacity-50 cursor-not-allowed' : '' }}"
                                data-gateway="{{ $gatewayCode }}"
                                data-countries="{{ json_encode($gateway['countries']) }}"
                                data-min-balance="{{ $gatewayCode === 'my-wallet' && $checkoutType === 'pay' ? $totalAmount : 0 }}"
                                id="{{ $gatewayCode }}-gateway">
                                @if($gatewayCode === 'my-wallet' && $checkoutType === 'pay' && $walletBalance < $totalAmount)
                                    <div class="relative">
                                        <img src="{{ asset($gateway['image_path']) }}" alt="{{ $gateway['name'] }}" class="rounded-lg opacity-50">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-xs font-bold text-red-600 bg-white px-2 py-1 rounded"> {{ label_text('global', 'Insufficient', __('site.Insufficient')) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ asset($gateway['image_path']) }}" alt="{{ $gateway['name'] }}" class="rounded-lg">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Paym Discount -->
                <div class="mb-6">
                    <label class="block mb-2 text-base font-semibold text-black">
                       {{ label_text('global', 'Paym Discount', __('site.Paym Discount')) }} 
                    </label>
                    <div class="flex gap-3 relative">
                        <input type="text" id="discount-code" placeholder="Enter the code"
                            class="flex-1 px-5 py-6 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300">
                        <button id="apply-discount-btn"
                            class="absolute top-1/2 right-3 transform translate-y-[-50%] translate-x-[-3%] px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 transition-all duration-200">
                             {{ label_text('global', 'Application', __('site.Application')) }} 
                        </button>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="mb-6 py-4 border-t border-gray-200">
                    <div class="space-y-3 w-1/3">
                        <div class="flex justify-between items-center text-base">
                            <span class="font-bold text-black"> {{ label_text('global', 'Total', __('site.Total')) }} </span>
                            <span class="text-center w-20">
                                <span class="font-semibold text-black" id="total-amount">7.46 $</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-base">
                            <span class="font-bold text-black">{{ label_text('global', 'Discount', __('site.Discount')) }}</span>
                            <span class="text-center w-20">
                                <span class="font-semibold text-black" id="discount-amount">0.00 $</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-base">
                            <span class="font-bold text-black"> {{ label_text('global', 'Tax / Fees', __('site.Tax / Fees')) }}</span>
                            <span class="text-center w-20">
                                <span class="font-semibold text-black" id="tax-amount">%0</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-base">
                            <span class="font-bold text-black">{{ label_text('global', 'Service', __('site.Service')) }} </span>
                            <span class="text-center w-20">
                                <span class="font-semibold text-black" id="service-amount">0.00 $</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Total Price and Purchase Button -->
                <div class="flex justify-between items-center gap-4 mb-6 px-2.5 py-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-center w-1/3">
                        <span class="text-lg font-bold text-black">{{ label_text('global', 'Total price', __('site.Total price')) }}</span>
                        <span class="text-center w-20">
                            <span class="font-bold text-lg text-black" id="final-total">7.46 $</span>
                        </span>
                    </div>
                    <button id="purchase-btn"
                        class="px-4 py-3 text-xs text-white bg-primary rounded-lg hover:bg-primary-700 transition-all duration-200 shadow-sm">
                       {{ label_text('global', 'Purchase confirmation', __('site.Purchase confirmation')) }} 
                    </button>
                </div>

                <!-- Disclaimer Notes -->
                <div class="space-y-3">
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 flex justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_180_966)">
                                    <path
                                        d="M6.0108 7.85664H16.8708C17.0988 7.82314 17.3071 7.70875 17.4577 7.53438C17.6083 7.36 17.6912 7.13726 17.6912 6.90684C17.6912 6.67641 17.6083 6.45367 17.4577 6.2793C17.3071 6.10492 17.0988 5.99053 16.8708 5.95703H6.0108C5.78282 5.99053 5.5745 6.10492 5.42387 6.2793C5.27325 6.45367 5.19037 6.67641 5.19037 6.90684C5.19037 7.13726 5.27325 7.36 5.42387 7.53438C5.5745 7.70875 5.78282 7.82314 6.0108 7.85664Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M13.8308 12.4289C13.8308 12.1771 13.7308 11.9355 13.5528 11.7573C13.3749 11.5791 13.1334 11.4788 12.8816 11.4785H5.61679C5.36488 11.4785 5.1233 11.5786 4.94518 11.7567C4.76705 11.9348 4.66699 12.1764 4.66699 12.4283C4.66699 12.6802 4.76705 12.9218 4.94518 13.0999C5.1233 13.2781 5.36488 13.3781 5.61679 13.3781H12.8816C13.1332 13.3778 13.3745 13.2777 13.5524 13.0997C13.7304 12.9218 13.8305 12.6806 13.8308 12.4289Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M22.8106 15.1748C22.9066 14.1284 22.9702 12.908 22.9714 11.4872C22.9847 9.45334 22.8285 7.42192 22.5046 5.41399C22.2466 4.19972 21.6422 3.08626 20.7646 2.20838C19.8869 1.3305 18.7736 0.725835 17.5594 0.467588C15.5511 0.143583 13.5192 -0.0125565 11.485 0.000788136C9.45113 -0.0124676 7.41971 0.143672 5.41178 0.467588C4.19774 0.725748 3.08453 1.33023 2.20688 2.20788C1.32922 3.08554 0.724742 4.19874 0.466582 5.41279C0.14287 7.42074 -0.0132681 9.45215 -0.000217785 11.486C-0.0133014 13.5202 0.142837 15.552 0.466582 17.5604C0.724742 18.7744 1.32922 19.8876 2.20688 20.7653C3.08453 21.6429 4.19774 22.2474 5.41178 22.5056C7.42013 22.8294 9.45194 22.9855 11.4862 22.9724C12.907 22.9724 14.1262 22.9064 15.1738 22.8116C16.2179 23.6472 17.5335 24.0685 18.8687 23.9948C20.204 23.921 21.4652 23.3574 22.4109 22.4119C23.3566 21.4663 23.9204 20.2052 23.9944 18.8699C24.0683 17.5347 23.6461 16.219 22.8106 15.1748ZM11.4862 21.1916C9.55333 21.2029 7.62282 21.0552 5.71418 20.75C4.85678 20.5709 4.07341 20.1372 3.46658 19.5056C2.83502 18.8987 2.40131 18.1154 2.22218 17.258C1.91706 15.3493 1.76936 13.4188 1.78058 11.486C1.76921 9.55313 1.91691 7.62262 2.22218 5.71399C2.40139 4.85699 2.83511 4.07405 3.46658 3.46759C4.07346 2.83602 4.85681 2.40232 5.71418 2.22319C7.62284 1.91812 9.55333 1.77042 11.4862 1.78159C13.419 1.77029 15.3495 1.91799 17.2582 2.22319C18.1155 2.40232 18.8989 2.83602 19.5058 3.46759C20.1373 4.07446 20.5711 4.85782 20.7502 5.71519C21.0552 7.62385 21.2029 9.55434 21.1918 11.4872C21.1918 12.2792 21.169 13.0388 21.1282 13.7744C20.1 13.2252 18.9226 13.0211 17.7696 13.1923C16.6166 13.3634 15.5492 13.9007 14.7249 14.7249C13.9006 15.549 13.3632 16.6164 13.1918 17.7693C13.0205 18.9223 13.2244 20.0998 13.7734 21.128C13.039 21.1688 12.2782 21.1904 11.4874 21.1916H11.4862ZM18.5662 22.22C17.8437 22.22 17.1375 22.0058 16.5368 21.6044C15.9361 21.203 15.4679 20.6325 15.1914 19.965C14.915 19.2976 14.8426 18.5631 14.9836 17.8546C15.1245 17.146 15.4724 16.4951 15.9833 15.9843C16.4941 15.4734 17.145 15.1255 17.8536 14.9846C18.5621 14.8436 19.2966 14.916 19.964 15.1924C20.6315 15.4689 21.202 15.9371 21.6034 16.5378C22.0047 17.1385 22.219 17.8447 22.219 18.5672C22.2177 19.5355 21.8324 20.4637 21.1476 21.1483C20.4628 21.8328 19.5345 22.2178 18.5662 22.2188V22.22Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M19.0397 19.3125C18.7017 19.2604 18.3577 19.2604 18.0197 19.3125C17.9178 19.3343 17.8243 19.3851 17.7507 19.4587C17.677 19.5324 17.6262 19.6258 17.6044 19.7277C17.5524 20.0657 17.5524 20.4097 17.6044 20.7477C17.6262 20.8496 17.677 20.943 17.7507 21.0167C17.8243 21.0903 17.9178 21.1411 18.0197 21.1629C18.3577 21.2148 18.7016 21.2148 19.0397 21.1629C19.1415 21.1411 19.2349 21.0903 19.3086 21.0167C19.3823 20.943 19.4331 20.8496 19.4549 20.7477C19.5069 20.4097 19.5069 20.0657 19.4549 19.7277C19.4331 19.6258 19.3823 19.5324 19.3086 19.4587C19.2349 19.3851 19.1415 19.3343 19.0397 19.3125Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M18.5304 19.0662C18.782 19.0659 19.0233 18.9658 19.2012 18.7879C19.3792 18.6099 19.4793 18.3687 19.4796 18.117V16.5318C19.4796 16.2799 19.3795 16.0383 19.2014 15.8602C19.0233 15.6821 18.7817 15.582 18.5298 15.582C18.2779 15.582 18.0363 15.6821 17.8582 15.8602C17.68 16.0383 17.58 16.2799 17.58 16.5318V18.117C17.5803 18.3689 17.6806 18.6103 17.8588 18.7883C18.037 18.9663 18.2785 19.0662 18.5304 19.0662Z"
                                        fill="#1B449C" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_180_966">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ label_text('global', ' By clicking 'Complete Purchase, I acknowledge that I have read and agree to the terms and
                            conditions and the privacy policy of the Jien platform.', __('site. By clicking 'Complete Purchase, I acknowledge that I have read and agree to the terms and
                            conditions and the privacy policy of the Jien platform.')) }}
                           
                        </p>
                    </div>
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 flex justify-center">
                            <svg width="18" height="24" viewBox="0 0 18 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_180_979)">
                                    <path
                                        d="M15.5306 23.7081H2.46675C1.88938 23.7075 1.33583 23.476 0.927568 23.0644C0.519305 22.6529 0.289674 22.0949 0.289063 21.5128L0.289063 11.2699C0.289062 10.6874 0.518423 10.1288 0.926753 9.71675C1.33508 9.30469 1.88898 9.07288 2.46675 9.07227H15.5306C16.108 9.07288 16.6615 9.30436 17.0698 9.71592C17.478 10.1275 17.7077 10.6855 17.7083 11.2675V21.5105C17.7083 22.0929 17.4789 22.6515 17.0706 23.0636C16.6622 23.4757 16.1083 23.7075 15.5306 23.7081ZM2.46675 10.5354C2.27413 10.5354 2.0894 10.6125 1.95319 10.7498C1.81699 10.8871 1.74047 11.0733 1.74047 11.2675V21.5105C1.74047 21.7047 1.81699 21.8909 1.95319 22.0282C2.0894 22.1655 2.27413 22.2426 2.46675 22.2426H15.5306C15.7232 22.2426 15.9079 22.1655 16.0441 22.0282C16.1803 21.8909 16.2569 21.7047 16.2569 21.5105V11.2675C16.2569 11.0733 16.1803 10.8871 16.0441 10.7498C15.9079 10.6125 15.7232 10.5354 15.5306 10.5354H2.46675Z"
                                        fill="#1B449C" stroke="#1B449C" stroke-width="0.5" />
                                    <path
                                        d="M14.083 10.534C13.8903 10.534 13.7056 10.4569 13.5694 10.3196C13.4332 10.1823 13.3567 9.99603 13.3567 9.80186V6.14581C13.3567 4.98122 12.8977 3.86433 12.0808 3.04084C11.2639 2.21735 10.156 1.75471 9.00073 1.75471C7.84546 1.75471 6.7375 2.21735 5.9206 3.04084C5.1037 3.86433 4.64477 4.98122 4.64477 6.14581V9.80418C4.63729 9.99316 4.55756 10.1719 4.42228 10.3029C4.287 10.434 4.10667 10.5071 3.91907 10.5071C3.73146 10.5071 3.55113 10.434 3.41585 10.3029C3.28057 10.1719 3.20084 9.99316 3.19336 9.80418V6.14581C3.19336 4.59302 3.80526 3.10383 4.89446 2.00585C5.98366 0.907858 7.46094 0.291016 9.0013 0.291016C10.5417 0.291016 12.0189 0.907858 13.1081 2.00585C14.1973 3.10383 14.8092 4.59302 14.8092 6.14581V9.80418C14.8086 9.99796 14.7318 10.1836 14.5957 10.3204C14.4596 10.4572 14.2752 10.534 14.083 10.534Z"
                                        fill="#1B449C" stroke="#1B449C" stroke-width="0.5" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_180_979">
                                        <rect width="18" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ label_text('global', 'All transactions are secure, processed, and authorized by payment service providers.', __('site.All transactions are secure, processed, and authorized by payment service providers.')) }}
                            
                        </p>
                    </div>
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 flex justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_180_975)">
                                    <path
                                        d="M12.0006 0C9.62712 0 7.30693 0.703824 5.33344 2.02247C3.35994 3.34111 1.8218 5.21535 0.913498 7.40818C0.00519968 9.601 -0.232453 12.0139 0.230594 14.3418C0.69364 16.6697 1.83659 18.808 3.5149 20.4863C5.19322 22.1646 7.33153 23.3076 9.65942 23.7706C11.9873 24.2337 14.4002 23.996 16.5931 23.0877C18.7859 22.1794 20.6601 20.6413 21.9788 18.6678C23.2974 16.6943 24.0012 14.3741 24.0012 12.0006C23.9976 8.81896 22.7321 5.76864 20.4824 3.51886C18.2326 1.26909 15.1823 0.00358865 12.0006 0ZM12.0006 21.8171C10.0588 21.8173 8.16045 21.2417 6.54575 20.163C4.93105 19.0843 3.6725 17.551 2.92927 15.757C2.18604 13.963 1.99152 11.9889 2.3703 10.0844C2.74907 8.17979 3.68414 6.43034 5.05724 5.05724C6.43034 3.68414 8.1798 2.74907 10.0844 2.37029C11.9889 1.99151 13.963 2.18604 15.757 2.92927C17.551 3.6725 19.0843 4.93105 20.163 6.54574C21.2417 8.16044 21.8173 10.0588 21.8171 12.0006C21.8138 14.6031 20.7785 17.0981 18.9383 18.9383C17.0981 20.7785 14.6031 21.8138 12.0006 21.8171Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M12.0004 5.08984C11.7128 5.08984 11.4316 5.17512 11.1924 5.33488C10.9533 5.49465 10.7669 5.72173 10.6567 5.98743C10.5466 6.25313 10.5177 6.5455 10.5738 6.82761C10.6298 7.10972 10.7682 7.36888 10.9715 7.57234C11.1748 7.7758 11.4338 7.91443 11.7159 7.97069C11.9979 8.02695 12.2903 7.99832 12.5561 7.88842C12.8219 7.77852 13.0491 7.5923 13.2091 7.35328C13.3691 7.11425 13.4546 6.83316 13.4548 6.54555C13.4545 6.15978 13.3012 5.78989 13.0286 5.517C12.7559 5.24411 12.3861 5.0905 12.0004 5.08984Z"
                                        fill="#1B449C" />
                                    <path
                                        d="M12.001 10.1816C11.7117 10.1816 11.4342 10.2966 11.2297 10.5012C11.0251 10.7057 10.9102 10.9832 10.9102 11.2725V17.8176C10.9102 18.1069 11.0251 18.3844 11.2297 18.589C11.4342 18.7935 11.7117 18.9085 12.001 18.9085C12.2903 18.9085 12.5678 18.7935 12.7724 18.589C12.9769 18.3844 13.0919 18.1069 13.0919 17.8176V11.2725C13.0919 11.1292 13.0637 10.9874 13.0088 10.855C12.954 10.7227 12.8737 10.6024 12.7724 10.5012C12.6711 10.3999 12.5508 10.3195 12.4185 10.2647C12.2861 10.2099 12.1443 10.1816 12.001 10.1816Z"
                                        fill="#1B449C" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_180_975">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ label_text('global', 'The price you see on the payment provider's page may vary slightly due to differences in
                            exchange rates.', __('site.The price you see on the payment provider's page may vary slightly due to differences in
                            exchange rates.')) }}
                            
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>


    @push('scripts')
        <script src="{{ asset('front/assets/js/wallet-checkout.js') }}"></script>
    @endpush
</x-front-layout>
