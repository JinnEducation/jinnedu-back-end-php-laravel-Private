<x-front-layout>
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                {{ label_text('global', 'Test Payment (Local Mode)', __('site.Test Payment (Local Mode)')) }}
            </h2>

            <p class="mb-2 text-gray-600">
                {{ label_text('global', 'This is a test payment page for development.', __('site.This is a test payment page for development.')) }}
            </p>

            <p class="mb-6 text-lg font-semibold text-gray-800">
                {{ label_text('global', 'Amount:', __('site.Amount:')) }} ${{ number_format($transaction->amount, 2) }}
            </p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-gray-600 mb-2">
                    <strong>{{ label_text('global', 'Transaction ID:', __('site.Transaction ID:')) }}</strong>
                    {{ $transaction->reference_id }}
                </p>

                <p class="text-sm text-gray-600 mb-2">
                    <strong>{{ label_text('global', 'Payment Channel:', __('site.Payment Channel:')) }}</strong>
                    {{ $transaction->payment_channel }}
                </p>

                <p class="text-sm text-gray-600">
                    <strong>{{ label_text('global', 'Current Wallet:', __('site.Current Wallet:')) }}</strong>
                    ${{ number_format($transaction->current_wallet, 2) }}
                </p>
            </div>
            
            <form action="{{ route('local-payment-test-complete') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="reference_id" value="{{ $transaction->reference_id }}">
                <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 font-semibold">
                    {{ label_text('global', '✓ Simulate Successful Payment', __('site.✓ Simulate Successful Payment')) }}
                </button>
            </form>
            
            <a href="{{ route('checkout-response', ['id' => $transaction->id, 'status' => 'cancel']) }}" 
               class="block w-full text-center text-red-500 hover:text-red-700 transition-colors duration-200 py-2">
                {{ label_text('global', '✗ Cancel Payment', __('site.✗ Cancel Payment')) }}
            </a>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    {{ label_text('global', 'This is a test mode. No real payment will be processed.', __('site.This is a test mode. No real payment will be processed.')) }}
                </p>
            </div>
        </div>
    </div>
</x-front-layout>
