/**
 * Wallet Checkout Page JavaScript
 * Handles all interactive functionality for the checkout page
 */

$(document).ready(function() {
    
    let selectedGateway = null;

    // Discount state
    let discountApplied = false;
    let discountPercentage = 0; // e.g. 10 means 10%
    let appliedDiscountCode = null;

    const TAX_RATE = 0; // 0% tax
    const SERVICE_FEE = 0; // $0 service fee

    // ============================================
    // Preset Amount Buttons
    // ============================================
    $('.preset-btn').on('click', function() {
        const amount = $(this).data('amount');
        const currentAmount = parseFloat($('#wallet-amount').val()) || 0;
        const newAmount = currentAmount + amount;
        
        $('#wallet-amount').val(newAmount);
        updateTotals(newAmount);
        
        // Visual feedback
        $(this).addClass('bg-primary text-white');
        setTimeout(() => {
            $(this).removeClass('bg-primary text-white');
        }, 200);
    });

    // ============================================
    // Wallet Amount Input Change
    // ============================================
    $('#wallet-amount').on('input change', function() {
        let amount = parseFloat($(this).val()) || 0;
        
        // Prevent negative values
        if (amount < 0) {
            amount = 0;
            $(this).val(0);
        }
        
        updateTotals(amount);
    });

    // ============================================
    // Country Selection Change - Update Payment Gateways
    // ============================================
    $('#country-select').on('change', function() {
        const selectedCountry = $(this).val();
        updatePaymentGateways(selectedCountry);
        
        // Reset selected gateway when country changes
        selectedGateway = null;
        $('#payment-gateway').val('');
        $('.payment-gateway').removeClass('border-primary').addClass('border-gray-200');
    });

    // ============================================
    // Update Payment Gateways Based on Country
    // ============================================
    function updatePaymentGateways(country) {
        $('.payment-gateway').each(function() {
            let gatewayCountries = $(this).data('countries');
            
            // Parse JSON string if it's a string
            if (typeof gatewayCountries === 'string') {
                try {
                    gatewayCountries = JSON.parse(gatewayCountries);
                } catch (e) {
                    console.error('Error parsing countries data:', e);
                    gatewayCountries = [];
                }
            }
            
            // Check if gateway is available for all countries ('all') or for specific country
            const isAvailable = gatewayCountries && (
                gatewayCountries.includes('all') || 
                gatewayCountries.includes(country)
            );
            
            if (isAvailable) {
                // Show gateway
                $(this).show().fadeIn(300);
            } else {
                // Hide gateway
                $(this).fadeOut(300, function() {
                    $(this).hide();
                });
            }
        });
        
        // Check if any gateways are available
        const visibleGateways = $('.payment-gateway:visible').length;
        if (visibleGateways === 0) {
            showMessage('No payment gateways available for this country', 'warning');
        }
    }

    // ============================================
    // Payment Gateway Selection
    // ============================================
    $('.payment-gateway').on('click', function() {
        // Check if gateway is visible
        if (!$(this).is(':visible')) {
            return;
        }
        
        // Check if gateway is disabled (insufficient balance)
        if ($(this).hasClass('opacity-50') && $(this).hasClass('cursor-not-allowed')) {
            const checkoutType = $('#checkout-type').val();
            if (checkoutType === 'pay') {
                const minBalance = parseFloat($(this).data('min-balance')) || 0;
                const walletBalance = parseFloat($('#wallet-balance').text().replace(/[^0-9.]/g, '')) || 0;
                const shortage = minBalance - walletBalance;
                
                showMessage(
                    `Insufficient wallet balance. You need $${shortage.toFixed(2)} more. Please top up your wallet first.`, 
                    'error'
                );
                
                // Show link to topup after 2 seconds
                setTimeout(() => {
                    if (confirm('Would you like to top up your wallet now?')) {
                        window.location.href = '/checkout?type=topup';
                    }
                }, 2000);
            }
            return;
        }
        
        // Remove active state from all gateways
        $('.payment-gateway').removeClass('border-primary').addClass('border-gray-200');
        
        // Add active state to selected gateway
        $(this).removeClass('border-gray-200').addClass('border-primary');
        
        // Store selected gateway
        selectedGateway = $(this).data('gateway');
        $('#payment-gateway').val(selectedGateway);
        
        // Visual feedback
        $(this).css('transform', 'scale(0.98)');
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 100);
    });

    // ============================================
    // Discount Code Application (REAL API CALL)
    // ============================================
    $('#apply-discount-btn').on('click', function() {
        const discountCode = $('#discount-code').val().trim();
        
        if (discountCode === '') {
            showMessage('Please enter a discount code', 'warning');
            return;
        }

        const $btn = $(this);
        const amount = parseFloat($('#wallet-amount').val()) || 0;

        // optional data attr, fallback route
        const applyUrl = apply_discount_url;

        $btn.prop('disabled', true).html('<span class="inline-block animate-pulse">Applying...</span>');

        $.ajax({
            url: applyUrl,
            method: 'POST',
            data: {
                code: discountCode,
                amount: amount,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Expected: { success:true, data:{ percentage, discount, message } }
                if (response && response.success) {
                    discountApplied = true;
                    discountPercentage = parseFloat(response.percentage) || 0;
                    appliedDiscountCode = discountCode;

                    showMessage(response.message || 'Discount code applied successfully!', 'success');

                    // Update UI
                    $btn
                        .text('Applied ✓')
                        .addClass('bg-green-600 hover:bg-green-700')
                        .removeClass('bg-primary hover:bg-primary-700')
                        .prop('disabled', true);

                    $('#discount-code').prop('disabled', true).addClass('bg-gray-100');
                    // Update totals (will compute discount by percentage)
                    updateTotals(amount);
                } else {
                    showMessage((response && response.message) ? response.message : 'Invalid discount code', 'error');
                    $btn.prop('disabled', false).text('Apply');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON || {};
                const msg = res.message || 'Invalid or expired discount code';

                // reset discount state just in case
                discountApplied = false;
                discountPercentage = 0;
                appliedDiscountCode = null;
                $('#discount-amount').text(formatCurrency(0));
                
                showMessage(msg, 'error');
                $btn.prop('disabled', false).text('Apply');
            }
        });
    });

    // ============================================
    // Purchase Confirmation Button
    // ============================================
    $('#purchase-btn').on('click', function() {
        const checkoutType = $('#checkout-type').val();
        const amount = parseFloat($('#wallet-amount').val()) || 0;
        const orderIds = $('#order-ids').val();
        
        // Validation
        if (checkoutType === 'pay' && !orderIds) {
            showMessage('No orders found', 'error');
            return;
        }
        
        if (checkoutType === 'topup' && amount <= 0) {
            showMessage('Please enter a valid amount', 'error');
            return;
        }
        
        if (!selectedGateway) {
            showMessage('Please select a payment method', 'error');
            return;
        }
        
        // Prepare data
        const data = {
            type: checkoutType,
            amount: amount,
            order_ids: orderIds ? orderIds.split(',').filter(id => id) : [],
            payment_gateway: selectedGateway,
            country: $('#country-select').val(),
            // send only if applied (avoid sending random text)
            discount_code: discountApplied ? (appliedDiscountCode || $('#discount-code').val() || null) : null,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // Show loading state
        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="inline-block animate-pulse">Processing...</span>');
        
        // Send AJAX request
        $.ajax({
            url: $('#checkout-type').data('checkout-url') || '/checkout',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success && response.url) {
                    // Redirect to payment gateway
                    window.location.href = response.url;
                } else if(response.success && response.redirect_url) {
                    // Direct success (wallet payment)
                    window.location.href = response.redirect_url;
                } else {
                    showMessage(response.message || 'An error occurred', 'error');
                    $btn.prop('disabled', false).text('Purchase confirmation');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                let errorMessage = response.message || 'An error occurred';
                
                // Handle insufficient balance error with topup link
                if (response.message === 'insufficient-wallet-balance' && response.topup_url) {
                    errorMessage = response.message_text || errorMessage;
                    showMessage(errorMessage, 'error');
                    
                    // Show topup option after 2 seconds
                    setTimeout(() => {
                        if (confirm('Would you like to top up your wallet now?')) {
                            window.location.href = response.topup_url;
                        }
                    }, 2000);
                } else {
                    showMessage(errorMessage, 'error');
                }
                
                $btn.prop('disabled', false).text('Purchase confirmation');
            }
        });
    });

    // ============================================
    // Update Totals Function
    // ============================================
    function updateTotals(baseAmount) {
        const checkoutType = $('#checkout-type').val();
        let elementsTotal = 0;
        
        // In payment mode, calculate sum of items
        if(checkoutType === 'pay') {
            $('.element-item').each(function() {
                const price = parseFloat($(this).data('price')) || 0;
                elementsTotal += price;
            });
            // Use elements total as base amount for payment
            baseAmount = elementsTotal;
        }
        
        // Calculate tax
        const taxAmount = baseAmount * TAX_RATE;
        
        // Calculate service fee
        const serviceFee = SERVICE_FEE;
        
        // Apply discount if applicable (by percentage)
        let discount = 0;
        if (discountApplied && discountPercentage > 0) {
            discount = baseAmount * (discountPercentage / 100);
            $('#discount-amount').text(formatCurrency(discount));
        } else {
            $('#discount-amount').text(formatCurrency(0));
        }

        // Calculate total
        const total = baseAmount;
        const finalTotal = total + taxAmount + serviceFee - discount;
        
        // Update display
        $('#total-amount').text(formatCurrency(total));
        $('#tax-amount').text(TAX_RATE > 0 ? formatCurrency(taxAmount) : '%0');
        $('#service-amount').text(formatCurrency(serviceFee));
        $('#final-total').text(formatCurrency(finalTotal));
    }

    // ============================================
    // Helper: Format Currency
    // ============================================
    function formatCurrency(amount) {
        return (parseFloat(amount) || 0).toFixed(2) + ' $';
    }

    // ============================================
    // Helper: Show Messages
    // ============================================
    function showMessage(message, type) {
        // Create message element
        const messageClass = {
            'success': 'bg-green-100 border-green-400 text-green-700',
            'error': 'bg-red-100 border-red-400 text-red-700',
            'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
            'info': 'bg-blue-100 border-blue-400 text-blue-700'
        };
        
        const $message = $(`
            <div class="fixed top-32 right-4 left-4 mx-auto max-w-md z-50 px-4 py-3 rounded-lg border-l-4 shadow-lg ${messageClass[type]} animate-fade-in-down" role="alert">
                <p class="font-medium">${message}</p>
            </div>
        `);
        
        // Add to page
        $('body').append($message);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            $message.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // ============================================
    // Initialize with default values
    // ============================================
    const checkoutType = $('#checkout-type').val();
    let initialAmount = parseFloat($('#wallet-amount').val()) || 10;
    
    // If payment mode, calculate from items
    if(checkoutType === 'pay') {
        let itemsTotal = 0;
        $('.element-item').each(function() {
            const price = parseFloat($(this).data('price')) || 0;
            itemsTotal += price;
        });
        initialAmount = itemsTotal;
    }
    
    updateTotals(initialAmount);
    
    // Initialize payment gateways based on default country
    const defaultCountry = $('#country-select').val();
    updatePaymentGateways(defaultCountry);
});

// Add custom CSS animation for message fade in
$('<style>')
    .prop('type', 'text/css')
    .html(`
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }
    `)
    .appendTo('head');
