# Checkout Accounting Flow

## Scope

This document maps the student payment/accounting flow starting from the frontend checkout page and continuing through payment gateways, wallet storage, order completion, and downstream tutor finance handling.

No code was changed while preparing this document.

## Main Entry Points

### Frontend checkout page

- `resources/views/front/checkout.blade.php`
- `public/front/assets/js/wallet-checkout.js`

The page supports two modes:

- `topup`: student adds money to wallet
- `pay`: student pays one or more existing orders

The page renders:

- current wallet balance
- selected orders and their prices when `type=pay`
- amount input or readonly total amount
- payment gateway selector
- discount code input
- purchase button that posts to `route('checkout.checkout')`

### Web routes

- `routes/web.php`

Relevant routes:

- `GET /{locale?}/checkout` -> `Front\CheckoutController@checkout`
- `POST /{locale?}/checkout/apply-discount` -> `Front\CheckoutController@applyDiscountCode`
- `POST /{locale?}/checkout` -> `Front\CheckoutController@checkout_store`
- `GET /{locale?}/checkout-complete` -> `Front\CheckoutController@checkoutComplete`
- `GET /{locale?}/checkout-response/{id}/{status}` -> `Front\CheckoutController@handlePaymentResponse`
- `GET /{locale?}/payment-response/{id}/{status}` -> `WalletPaymentTransactionController@handlePaymentResponse`

There are two callback route styles in the project:

- old/general: `checkout-response`
- new/frontend final page: `checkout-response-get`

## Student Flow Overview

## 1. Order creation before checkout

Checkout itself does not create the order. The order is created first in one of these places, then the student is redirected to checkout.

### Group class booking

- `app/Http/Controllers/Front/HomeController.php`
- `app/Http/Controllers/OrderController.php`

Flow:

1. `HomeController::groupClassOrder()` checks if the student already has an order.
2. If not, it creates the order through `OrderController::groupClass()`.
3. That method creates an `orders` row with:
   - `ref_type = 1`
   - `ref_id = group class id`
   - `price = group class price`
   - default `status = 0` from migration
4. Then the user is either:
   - sent to direct wallet checkout via old `WalletController::checkout($orderId)`, or
   - redirected to `/checkout?type=pay&order_ids=<id>`

### Private lesson booking

- `app/Http/Controllers/Front/HomeController.php`
- `app/Http/Controllers/OrderController.php`

Flow:

1. `HomeController::privateLessonOrder()` prepares booking date.
2. `OrderController::privateLesson()` creates an order with:
   - `ref_type = 4`
   - `ref_id = tutor id`
   - `dates` JSON for the session time
   - `price = tutor hourly_rate`
3. Then the same split happens:
   - direct old wallet checkout, or
   - redirect to frontend checkout page

### Course purchase

- `app/Http/Controllers/Front/CourseController.php`
- `app/Http/Controllers/OrderController.php`
- `app/Models/CourseEnrollment.php`

Flow:

1. `CourseController::bookCourse()` creates the order through `OrderController::courseUser()`.
2. `OrderController::courseUser()` creates an order with:
   - `ref_type = 2`
   - `ref_id = course id`
   - `price = course final_price`
3. It also creates or updates `course_enrollments` immediately, before payment is completed.
4. Then the student is either:
   - paid directly through old `WalletController::checkout($orderId)`, or
   - redirected to frontend checkout page

### Important architecture note

There are two active payment styles in parallel:

- old style: `WalletController::checkout()` performs wallet payment and also creates business side effects such as conferences or class linkage
- new style: `Front\CheckoutController` handles payment page, external gateways, wallet top-up, and external payment transaction records

This split is the most important structural fact in the accounting flow.

## 2. Frontend checkout page behavior

### Blade view

- `resources/views/front/checkout.blade.php`

Responsibilities:

- reads `checkoutType`
- receives `orders`, `walletBalance`, `totalAmount`, `paymentGateways`, `countries`
- shows selected order ids in a hidden field
- pushes `wallet-checkout.js`

### Frontend JS

- `public/front/assets/js/wallet-checkout.js`

Responsibilities:

- keeps selected gateway state
- recalculates displayed totals
- applies discount code via AJAX
- submits checkout via AJAX
- redirects to gateway URL or success URL returned by backend

POST payload sent to backend includes:

- `type`
- `amount`
- `order_ids[]`
- `payment_gateway`
- `country`
- `discount_code` if applied

## 3. New checkout backend flow

### Controller

- `app/Http/Controllers/Front/CheckoutController.php`

### Step A: display checkout

`CheckoutController::checkout()`:

1. loads current authenticated user
2. reads `type` query param, default `topup`
3. if `type=pay`, loads requested orders belonging to the same student
4. sums order prices into `totalAmount`
5. loads current wallet balance from `user->wallets()->first()`
6. builds payment gateway list through `getPaymentGateways()`
7. returns `front.checkout`

### Step B: submit checkout

`CheckoutController::checkout_store()` validates:

- `type` in `topup,pay`
- `payment_gateway`
- `country`

Then it optionally validates a discount code and routes to:

- `handleTopup()`
- `handlePayment()`

## 4. Top-up flow

### New top-up transaction creation

`CheckoutController::handleTopup()`:

1. reads requested amount
2. subtracts discount if present
3. blocks `my-wallet` gateway for top-up
4. creates a `wallet_payment_transactions` row with:
   - `user_id`
   - `amount = finalAmount`
   - `payment_channel`
   - `current_wallet`
   - `reference_id = uuid`
   - `response = { type: topup, payment_gateway: ... }`
5. sends the transaction to payment gateway service through `processPaymentGateway()`

### Top-up completion

After gateway success, `CheckoutController::completeTransaction()`:

1. marks `wallet_payment_transactions.payment_status = completed`
2. marks `wallet_payment_transactions.status = active`
3. ensures `user_wallets` row exists
4. if `type == topup`, increases wallet balance
5. creates a `wallet_transactions` record of type `credit`

Tables touched in top-up success:

- `wallet_payment_transactions`
- `user_wallets`
- `wallet_transactions`

## 5. Order payment flow through new checkout

### Payment preparation

`CheckoutController::handlePayment()`:

1. reads `order_ids`
2. loads only student-owned orders
3. sums `orders.price`
4. subtracts discount to produce `finalAmount`
5. if gateway is `my-wallet`, goes to direct wallet payment
6. otherwise creates `wallet_payment_transactions` row with metadata:
   - `response = { order_ids: [...], type: pay, payment_gateway: ... }`

### Direct wallet payment inside new checkout

`CheckoutController::payFromWallet()`:

1. ensures wallet exists
2. checks enough balance
3. deducts total amount from `user_wallets.balance`
4. loops through orders and for each order:
   - sets `status = 1`
   - sets `payment = wallet`
   - saves order
   - creates `wallet_transactions` debit row linked to `order_id`
5. returns `redirect_url = checkout-complete`

Tables touched:

- `user_wallets`
- `orders`
- `wallet_transactions`

### External gateway payment through new checkout

`CheckoutController::processPaymentGateway()`:

1. resolves driver through `PaymentManager::driver()`
2. calls `createPayment()` on gateway service
3. extracts URL and returns it to JS
4. frontend redirects browser to that external payment page

## 6. Payment gateway layer

### Gateway manager

- `app/Services/Payment/PaymentManager.php`
- `app/Services/Payment/PaymentInterface.php`

Supported in manager:

- `paypal`
- `stripe`
- `local-test`

### Important mismatch

`CheckoutController::getPaymentGateways()` displays these gateways in UI:

- `my-wallet` for pay only
- `paypal`
- `stripe`
- `jawal-pay`
- `palpay`
- `local-test` in local/debug

But `PaymentManager` only supports:

- `paypal`
- `stripe`
- `local-test`

So `jawal-pay` and `palpay` appear in UI but do not have a registered payment driver in the current manager.

### PayPal service

- `app/Services/Payment/PayPalService.php`

Behavior:

1. creates PayPal order
2. stores `paypal_order_id` inside `wallet_payment_transactions.response`
3. returns approve URL
4. on success callback, captures payment from PayPal
5. marks wallet payment transaction completed
6. if metadata contains order ids, marks orders completed and creates `wallet_transactions` debit rows

### Stripe service

- `app/Services/Payment/StripeService.php`

Behavior:

1. creates Stripe Checkout session
2. stores order ids/type in Stripe metadata
3. redirects back to `checkout-response-get`
4. on success callback, retrieves Stripe session
5. marks wallet payment transaction completed
6. if order ids exist, marks orders completed and creates `wallet_transactions` debit rows

### Local test service

- `app/Services/Payment/LocalTestService.php`
- `resources/views/front/local-payment-test.blade.php`

Behavior:

1. returns local test page instead of real gateway
2. on completion marks wallet payment transaction completed
3. if order ids exist, marks orders completed and creates `wallet_transactions` debit rows

## 7. Callback and completion flow

### New frontend callback

Main route:

- `GET /{locale?}/checkout-response/{id}/{status}`

Handled by:

- `Front\CheckoutController::handlePaymentResponse()`

Flow on success:

1. load `wallet_payment_transactions` by id
2. read `type` from transaction response JSON
3. resolve gateway driver
4. inject `reference_id` into request for services that need it
5. call `gateway->success($request)`
6. if gateway returns success, call `completeTransaction($transaction, $type)`
7. redirect to `checkout-complete`

### Important duplication note

For Stripe and PayPal, the gateway `success()` method already marks the `wallet_payment_transactions` row completed and may already complete orders.

Then `CheckoutController::completeTransaction()` runs again and can:

- mark the same transaction completed again
- create a generic `wallet_transactions` credit record
- call `completeOrders()` again, although `completeOrders()` filters only statuses `[0,2]`

This means the project currently has duplicated completion responsibilities between:

- `CheckoutController::completeTransaction()`
- gateway service `success()` methods

Order double-completion is partially protected because `completeOrders()` only picks orders with status `0` or `2`.

## 8. How order status becomes complete

### In new checkout

Order completion happens in one of these places:

- `CheckoutController::payFromWallet()` for wallet payment
- `CheckoutController::completeOrders()` after external success
- `PayPalService::completeOrders()`
- `StripeService::completeOrders()`
- `LocalTestService::completeOrders()`

In all these paths, the completed order is generally updated to:

- `orders.status = 1`
- `orders.payment = gateway name or wallet`

### Order status meanings observed in code

Based on current usage:

- `0` = pending / not paid yet
- `1` = completed / paid
- `2` = failed
- `4` = refunded or reversed in some refund flows

This is inferred from controller usage and not from enum definitions.

## 9. Wallet storage model

### User wallet

- `app/Models/UserWallet.php`
- `database/migrations/2023_06_17_070613_create_user_wallets_table.php`

Main field used in checkout:

- `balance`

Relation:

- `User::wallets()` in `app/Models/User.php`

### Wallet movement ledger

- `app/Models/WalletTransaction.php`
- `database/migrations/2024_08_04_160722_create_wallet_transactions_table.php`

Ledger types:

- `credit`
- `debit`

Observed meaning in current flow:

- top-up success creates `credit`
- order payment creates `debit`

### External payment transaction tracker

- `app/Models/WalletPaymentTransaction.php`
- `database/migrations/2025_05_06_011626_create_wallet_payment_transactions_table.php`

This table is the gateway-side transaction tracker. It stores:

- requested amount
- payment channel
- current wallet snapshot
- gateway reference id
- status/payment_status
- JSON response metadata

This is separate from `wallet_transactions`, which acts more like the user wallet ledger.

## 10. Old wallet flow still active

### Controller

- `app/Http/Controllers/WalletController.php`

`WalletController::checkout($orderid)` is still actively used by:

- `Front\HomeController::groupClassOrder()`
- `Front\HomeController::privateLessonOrder()`
- `Front\CourseController::bookCourse()`

This method is not just accounting. It also performs business actions based on `ref_type`.

Examples:

- `ref_type = 1`: attaches student to group class and prepares group class side effects
- `ref_type = 4`: creates conference via `ConferenceController::createPrivateLessonConference()`
- `ref_type = 2`: touches wallet package/course logic
- `ref_type = 6`: refund logic
- `ref_type = 7`: package logic

Important difference from the new checkout path:

- old wallet flow creates learning/business artifacts immediately
- new checkout flow mostly updates financial/order records only

That means successful external payment in the new checkout path completes the `order`, but does not obviously recreate all the old business side effects that `WalletController::checkout()` used to perform.

## 11. Tutor finance downstream flow

Student payment and tutor finance are not completed in one place.

### Tutor finance creation

- `app/Http/Controllers/WalletController.php`
- `app/Http/Controllers/ConferenceController.php`
- `app/Http/Controllers/ReviewController.php`
- `app/Models/TutorFinance.php`
- `app/Http/Controllers/TutorFinanceController.php`

Observed active flow:

1. Student order becomes paid.
2. Later, when a conference/review-related flow runs, the code calls `WalletController::addTutorFinance()`.
3. That method creates `tutor_finances` row and also calls `addTutorTransferToHisWallet()`.
4. Tutor wallet is then increased.

Active call sites found:

- `ConferenceController` around lines `1249` and `1260`
- `ReviewController` line `93`

Important detail:

- direct tutor finance creation calls inside checkout/gateway success paths are mostly commented out
- therefore student payment completion and tutor earning recognition are decoupled in the current implementation

## 12. Course-specific note

`OrderController::courseUser()` writes into `course_enrollments` before payment is completed.

So for courses, there are two distinct states:

- enrollment row exists
- order payment may still be pending until checkout succeeds

The user-facing course access logic often checks enrollment joined with `order.status = 1`, so the enrollment alone is not the final accounting state.

## 13. Related Files Map

### Frontend checkout

- `resources/views/front/checkout.blade.php`
- `public/front/assets/js/wallet-checkout.js`
- `resources/views/front/complete_checkout.blade.php`
- `resources/views/front/local-payment-test.blade.php`

### Frontend flow controllers

- `app/Http/Controllers/Front/CheckoutController.php`
- `app/Http/Controllers/Front/HomeController.php`
- `app/Http/Controllers/Front/CourseController.php`

### Order/business creation

- `app/Http/Controllers/OrderController.php`
- `app/Http/Controllers/ConferenceController.php`

### Old wallet/business flow

- `app/Http/Controllers/WalletController.php`

### Payment services

- `app/Services/Payment/PaymentManager.php`
- `app/Services/Payment/PaymentInterface.php`
- `app/Services/Payment/PayPalService.php`
- `app/Services/Payment/StripeService.php`
- `app/Services/Payment/LocalTestService.php`

### Generic payment transaction controller

- `app/Http/Controllers/WalletPaymentTransactionController.php`

### Models

- `app/Models/Order.php`
- `app/Models/User.php`
- `app/Models/UserWallet.php`
- `app/Models/WalletTransaction.php`
- `app/Models/WalletPaymentTransaction.php`
- `app/Models/CourseEnrollment.php`
- `app/Models/TutorFinance.php`

### Enums

- `app/Enums/TransactionPaymentStatus.php`
- `app/Enums/TransactionStatus.php`

### Database tables/migrations

- `database/migrations/2023_05_14_121059_create_orders_table.php`
- `database/migrations/2023_06_17_070613_create_user_wallets_table.php`
- `database/migrations/2024_08_04_160722_create_wallet_transactions_table.php`
- `database/migrations/2025_05_06_011626_create_wallet_payment_transactions_table.php`
- `database/migrations/2025_12_28_115436_create_course_enrollments_table.php`

## 14. Key Findings

1. The project currently has two overlapping checkout architectures: old `WalletController::checkout()` and new `Front\CheckoutController`.
2. External gateway completion logic is duplicated between gateway services and `CheckoutController::completeTransaction()`.
3. `jawal-pay` and `palpay` appear in checkout UI, but no drivers for them exist in `PaymentManager`.
4. The new checkout flow completes `orders` and writes wallet ledger rows, but it does not obviously reproduce all old business side effects such as conference creation and class linkage.
5. `CourseEnrollment` is created before payment completion, so enrollment row existence is not equal to successful payment.
6. Tutor finance is mostly downstream and delayed, not created directly at the moment student payment succeeds.

## 15. Short Student Path Summary

### For `pay`

1. Student creates or reuses an `order`.
2. Student lands on `checkout?type=pay&order_ids=...`.
3. Frontend posts to `CheckoutController::checkout_store()`.
4. Backend either:
   - deducts from `user_wallets` directly, or
   - creates `wallet_payment_transactions` and redirects to external gateway.
5. On success, order becomes `status = 1` and payment method is written into `orders.payment`.
6. Wallet ledger gets a `debit` record.
7. Later, tutor finance may be created from conference/review related flows.

### For `topup`

1. Student lands on `checkout?type=topup`.
2. Backend creates `wallet_payment_transactions`.
3. Student pays through gateway.
4. On success, `user_wallets.balance` increases.
5. Wallet ledger gets a `credit` record.
