<?php

namespace App\Services\Payment;
use Illuminate\Http\Request;

interface PaymentInterface
{
    public function createPayment(array $data);
    public function handleWebhook(Request $request);
    public function success(Request $request);
    public function cancel(Request $request);
}
