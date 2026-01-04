<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\GroupClass;
use App\Models\GroupClassStudent;
use App\Models\Order;
use App\Models\Post;
use App\Models\TutorFinance;
use App\Models\TutorTransfer;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;

class WalletController extends Controller
{
    // ========================================================

    public function balance()
    {
        $user = Auth::user();

        $wallet = $user->wallets()->first();
        $group_class_count = 0;
        $our_course_count = 0;
        $private_lesson_count = 0;
        if (! $wallet) {
            $balance = 0;
        } else {
            $balance = $wallet->balance;
            $group_class_count = $wallet->group_class_count;
            $our_course_count = $wallet->our_course_count;
            $private_lesson_count = $wallet->private_lesson_count;
        }

        return response([
            'success' => true,
            'message' => 'balance-retrieved-successfully',
            'result' => [
                'balance' => $balance,
                'group_class_count' => $group_class_count,
                'our_course_count' => $our_course_count,
                'private_lesson_count' => $private_lesson_count,
            ],
            'balance' => $balance,
            'group_class_count' => $group_class_count,
            'our_course_count' => $our_course_count,
            'private_lesson_count' => $private_lesson_count,
        ]);
    }

    // ========================================================

    public function refund($orderid)
    {
        $admin = Auth::user();

        $order = Order::where('id', $orderid)->where('user_id', $admin->id)->where('ref_type', 6)->whereIn('status', [0, 2])->first();
        if (! $order) {
            return response([
                'success' => false,
                'message' => 'order-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $refOrder = Order::where('id', $order->ref_id)->where('status', 1)->first();
        if (! $refOrder) {
            return response([
                'success' => false,
                'message' => 'reund-order-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        if (true) {
            $responseData = '{"status":"APPROVED"}';
            $order->payment = 'wallet';
        }
        // dd($responseData);
        $json = json_decode($responseData);

        if ($order->status == 0) {
            $order->metadata = $responseData;
            if (isset($json->status) && $json->status == 'APPROVED') {
                $order->status = 1;
            } else {
                $order->status = 2;
            }
            $order->save();
        }

        if (isset($json->status) && $json->status == 'APPROVED') {

            $refOrder->status = 4;
            $refOrder->save();

            $user = User::find($refOrder->user_id);
            $wallet = $user->wallets()->first();

            if ($refOrder->payment == 'wallet-package') {
                switch ($refOrder->ref_type) {
                    case 1: $wallet->group_class_count += 1;
                    case 2: $wallet->our_course_count += 1;
                    case 4: $wallet->private_lesson_count += 1;

                }

            } else {
                $wallet->balance += $refOrder->price;
            }
            $wallet->save();

            return response([
                'success' => true,
                'message' => 'refund-added-successfully',
                'order' => $order,
                'wallet' => $wallet,
            ], 200);

        } else {
            return response([
                'success' => false,
                'message' => 'refund-faild',
                'order' => $order,
            ], 200);
        }
    }

    // ========================================================
    public function transfer($conference_id)
    {
        $conferences = Conference::find($conference_id);
        if (! $conferences) {
            return response([
                'success' => false,
                'message' => 'conference-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        if ($conferences->ref_type == 1) {
            $groupClass = GroupClass::find($conferences->ref_id);
            if (! $conferences) {
                return response([
                    'success' => false,
                    'message' => 'groupClass-dose-not-exist',
                    'msg-code' => '222',
                ], 200);
            }

            $orders = Order::where('ref_type', 1)->where('ref_id', $conferences->ref_id)->where('status', 1)->where('transfer', 0)->get();
            foreach ($orders as $order) {

            }
        }
    }

    public function addTutorTransferToHisWallet($order, $tutor_id, $type = 1)
    {
        $percentage = getSettingVal('feez');
        $data = [
            'order_id' => $order->id,
            'tutor_id' => $tutor_id,
            'type' => $type,
            'percentage' => $percentage,
            'fee' => $percentage * $order->price / 100,
        ];

        $fee = TutorTransfer::create($data);

        $user = User::find($tutor_id);
        $wallet = $user->wallets()->first();
        if (! $wallet) {
            $wallet = new UserWallet;
            $wallet->user_id = $tutor_id;
            $wallet->balance = $fee->fee * $type;
            $wallet->save();
        } else {
            $wallet->balance += $fee->fee * $type;
            $wallet->save();
        }

    }

    public function addTutorFinance($order, $tutor_id, $type)
    {

        if ($type == 1 && TutorFinance::where(['ref_type' => 1, 'ref_id' => $order->ref_id])->exists()) {
            return false;
        }

        if ($type == 1) {
            $percentage = getSettingVal('group_class_fees');

            $groupclass = GroupClass::where('id', $order->ref_id)->first();
            $group_class_date = $groupclass->dates()->orderBy('id', 'desc')->first();
            $class_date = date('Y-m-d H:i:s', strtotime($group_class_date->class_date));
        } else {
            $percentage = getSettingVal('private_lesson_fees');

            // Check if dates is JSON and contains array with start_date_time
            $dates = $order->dates;
            $decoded = json_decode($dates, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['start_date_time'])) {
                // If it's JSON array with start_date_time, use it
                $class_date = date('Y-m-d H:i:s', strtotime($decoded['start_date_time']));
            } else {
                // If it's direct date string, use it as is
                $class_date = date('Y-m-d H:i:s', strtotime($dates));
            }
        }

        $data = [
            'order_id' => $order->id,
            'tutor_id' => $tutor_id,
            'ref_type' => $type,
            'ref_id' => $order->ref_id,
            'total' => $order->price,
            'percentage' => $percentage,
            'fee' => $percentage * $order->price / 100,
            'class_date' => $class_date,
        ];

        TutorFinance::create($data);

    }

    // ========================================================
    public function checkout($orderid)
    {
        // dd('12');
        $user = Auth::user();

        $wallet = $user->wallets()->first();
        // if(!$wallet) return response([
        //         'success' => false,
        //         'message' => 'wallet-dose-not-exist',
        //         'msg-code' => '111'
        // ] , 200);

        $order = Order::where('id', $orderid)->where('user_id', $user->id)->whereIn('status', [0, 2])->first();
        if (! $order) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        // if($order->price>$wallet->balance && $order->ref_type==1 && $wallet->group_class_count==0)
        //     return response([
        //             'success' => false,
        //             'message' => 'wallet-balance-is-not-enough',
        //             'msg-code' => '333'
        //     ] , 200);
        // else if($order->price>$wallet->balance && $order->ref_type==2 && $wallet->our_course_count==0)
        //     return response([
        //             'success' => false,
        //             'message' => 'wallet-balance-is-not-enough',
        //             'msg-code' => '333'
        //     ] , 200);
        // else if($order->price>$wallet->balance && $order->ref_type==4 && $wallet->private_lesson_count==0)
        //     return response([
        //             'success' => false,
        //             'message' => 'wallet-balance-is-not-enough',
        //             'msg-code' => '333'
        //     ] , 200);
        // else if($order->price>$wallet->balance && $order->ref_type==7)
        //     return response([
        //             'success' => false,
        //             'message' => 'wallet-balance-is-not-enough',
        //             'msg-code' => '333'
        //     ] , 200);

        if (true) {
            $responseData = '{"status":"APPROVED"}';
            $order->payment = 'wallet';
        }
        // dd($responseData);
        $json = json_decode($responseData);

        if ($order->status == 0) {
            $order->metadata = $responseData;
            if (isset($json->status) && $json->status == 'APPROVED') {
                $order->status = 1;
            } else {
                $order->status = 2;
            }
            $order->save();
        }

        if (isset($json->status) && $json->status == 'APPROVED') {

            $time = strtotime(date('Y-m-d'));
            $end_date = date('Y-m-d', strtotime('+1 month', $time));

            if ($order->ref_type == 1) {
                $groupClass = GroupClass::find($order->ref_id);

                // if($wallet->group_class_count > 0) {
                //     $wallet->group_class_count -=1;
                //     $wallet->save();

                //     $order->payment = 'wallet-package';
                //     $order->save();

                //     //$this->addTutorTransferToHisWallet($order,$groupClass->tutor_id);
                //     $this->addTutorFinance($order,$groupClass->tutor_id, 1);
                // } else {
                //     $wallet->balance -= $order->price;
                //     $wallet->save();

                //     //$this->addTutorTransferToHisWallet($order,$groupClass->tutor_id);
                //     $this->addTutorFinance($order,$groupClass->tutor_id, 1);
                // }

                // =====================================================================

                $dates = $groupClass->dates()->get();
                $conferences = Conference::where('ref_id', $groupClass->id)->where('ref_type', 1)->where('order_id', 0)->get();
                // dd($dates);

                $gcStudent = new GroupClassStudent;
                $gcStudent->student_id = $order->user_id;
                $gcStudent->class_id = $groupClass->id;
                $gcStudent->order_id = $order->id;
                $gcStudent->save();

                $user = User::find($order->tutor_id);
                $info = [
                    'type' => 'group class',
                    'orderId' => $order->id,
                ];

                sendFCMNotification(
                    'Jinnedu',
                    'You have a new student to '.$groupClass->name.' group class',
                    $user->fcm,
                    $info,
                    $user->id
                );

            } elseif ($order->ref_type == 2) {
                if ($wallet->our_course_count > 0) {
                    $wallet->our_course_count -= 1;
                    $wallet->save();

                    $order->payment = 'wallet-package';
                    $order->save();

                    // $this->addTutorTransferToHisWallet($order,$order->tutor_id);
                } else {
                    $wallet->balance -= $order->price;
                    $wallet->save();

                    // $this->addTutorTransferToHisWallet($order,$order->tutor_id);
                }
                // =====================================================================
                if ($wallet->group_class_count > 0) {
                    $wallet->group_class_count -= 1;
                } else {
                    $wallet->balance -= $order->price;
                }
                $wallet->save();
                // =====================================================================
                $conference = new ConferenceController;
                $conferences = $conference->createOurCourseConferences($order);
            } elseif ($order->ref_type == 4) {
                // if($wallet->private_lesson_count>0) {
                //     $wallet->private_lesson_count -=1;
                //     $wallet->save();

                //     $order->payment = 'wallet-package';
                //     $order->save();

                //     //$this->addTutorTransferToHisWallet($order,$order->ref_id);
                //     $this->addTutorFinance($order,$order->ref_id, 4);
                // }else {
                //     $wallet->balance -= $order->price;
                //     $wallet->save();

                //     //$this->addTutorTransferToHisWallet($order,$order->ref_id);
                //     $this->addTutorFinance($order,$order->ref_id, 4);
                // }
                // =====================================================================
                $conference = new ConferenceController;
                $conferences = $conference->createPrivateLessonConference($order);

                $user = User::find($order->tutor_id);
                $info = [
                    'type' => 'private lesson',
                    'orderId' => $order->id,
                ];

                sendFCMNotification(
                    'Jinnedu',
                    'You have a new private lesson',
                    $user->fcm,
                    $info,
                    $user->id
                );

            } elseif ($order->ref_type == 6) {

                if (true) {
                    $refOrder = Order::where('id', $order->ref_id)->where('status', 1)->first();
                    $refOrder->status = 4;
                    $refOrder->save();

                    $user = User::find($refOrder->user_id);
                    $wallet = $user->wallets()->first();

                    $wallet->balance += $order->price;
                    $wallet->save();

                    return response([
                        'success' => true,
                        'message' => 'refund-added-successfully',
                        'order' => $order,
                        'wallet' => $wallet,
                    ], 200);
                }
            } elseif ($order->ref_type == 7) {
                $package = Post::where('id', $order->ref_id)->where('content_type', 11)->first();
                if ($package) {
                    $package->info = $package->package()->first();
                }

                if ($package->info) {
                    $user = User::find($order->user_id);
                    $wallet = $user->wallets()->first();
                    $wallet->balance -= $order->price;
                    $wallet->group_class_count += $package->info->group_class_count;
                    $wallet->our_course_count += $package->info->our_course_count;
                    $wallet->private_lesson_count += $package->info->private_lesson_count;
                    $wallet->save();

                    return response([
                        'success' => true,
                        'message' => 'package-added-successfully',
                        'order' => $order,
                        'wallet' => $wallet,
                    ], 200);
                }
            }

            // =====================================================================
            /*Mail::send('front-teachmearabic.paypal-invoice',$data, function($message) use ($user) {
                  $message->from('noreply@teachmearabic.org', 'Paypal Payment');
                  $message->to($user->email);
                  $message->cc('noreply@teachmearabic.org');
                  //$message->bcc('mostafa.elamree@gmail.com');
                  $message->subject('Paypal Payment');
              });*/
            // ======================================================================
            return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'order' => $order,
                'conferences' => $conferences,
            ], 200);
        } else {
            return response([
                'success' => false,
                'message' => 'payment-faild',
                'order' => $order,
            ], 200);
        }

    }

    public function getWalletTransactions(Request $request)
    {

        $limit = setDataTablePerPageLimit($request->limit);

        $transactions = WalletTransaction::select('wallet_transactions.*')->with('user');

        if ($request->user_id) {
            $transactions->where('user_id', $request->user_id);
        }

        $transactions = paginate($transactions, $limit);

        return response([
            'success' => true,
            'message' => 'transactions-listed-successfully',
            'result' => $transactions,
        ], 200);

    }

    public function addWalletTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $transaction = WalletTransaction::create([
            'user_id' => $request->user_id,
            'order_id' => $request->order_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        if ($transaction) {

            $user = User::find($request->user_id);
            $wallet = $user->wallets()->first();
            if (! $wallet) {
                $wallet = new UserWallet;
                $wallet->user_id = $request->user_id;
                $wallet->balance = $request->type == 'credit' ? $request->amount : -$request->amount;
                $wallet->save();
            } else {
                $wallet->balance += $request->type == 'credit' ? $request->amount : -$request->amount;
                $wallet->save();
            }

            return response([
                'success' => true,
                'message' => 'transactions-added-successfully',
            ], 200);
        } else {
            return response([
                'success' => false,
                'message' => 'failed-to-add-transactions',
            ], 200);
        }
    }
}
