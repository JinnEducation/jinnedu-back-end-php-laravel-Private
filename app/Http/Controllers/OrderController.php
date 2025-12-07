<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Constants\CurrencyController;
use App\Models\Conference;
use App\Models\GroupClass;
use App\Models\Order;
use App\Models\OurCourse;
use App\Models\OurCourseTutor;
use App\Models\Post;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $currencyResponse;

    public function __construct()
    {
        $this->currencyResponse = [
            'success' => false,
            'message' => 'currency-dose-not-exist',
            'msg-code' => '111',
        ];
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::query();

        $items->where('ref_type', $request->ref_type);
        // Add any additional conditions to filter the orders if needed
        if (! empty($request->q)) {
            $items->whereHas('user', function ($query) use ($request) {
                $query->whereRaw(filterTextDB('name').' like ?', ['%'.filterText($request->q).'%']);
            });
        }

        // Perform pagination after applying filters
        $items = $items->paginate($limit);

        foreach ($items as $item) {
            $item->student = $item->user;
            $item->tutor = $item->tutor_id ? $item->tutor : null;
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ], 200);
    }

    public function myIndex(Request $request)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::query()->where('user_id', $user->id);
        foreach ($items as $item) {
            $item->student = $item->user;
            $item->tutor = $item->tutor_id ? $item->tutor : null;
        }

        $items = $items->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ], 200);
    }

    public function groupClass(Request $request, $id)
    {
        $user = Auth::user() ?? $request->user();
        $user = User::find($user->id);
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $groupClass = GroupClass::find($id);
        if (! $groupClass) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $isOrderedBefore = Order::where([
            'user_id' => $user->id,
            'ref_type' => 1,
            'ref_id' => $groupClass->id,
        ])->exists();

        if ($isOrderedBefore) {
            return response([
                'success' => false,
                'message' => 'Group-class-ordered-before',
                'msg-code' => '333',
            ], 200);
        }

        $dates = $groupClass->dates()->get();
        $conferences = Conference::where('ref_id', $groupClass->id)->where('ref_type', 0)->where('order_id', 0)->get();

        foreach ($conferences as $conference) {
            $checkAllowBooking = $this->checkAllowBooking($user, null, $conference->start_date_time, 40);
            if (! $checkAllowBooking['success']) {
                return response($checkAllowBooking, 200);
            }
        }

        if (! empty($request->currency_id)) {
            $currency = new CurrencyController;
            $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
            if (! $this->currencyResponse['success']) {
                return response($this->currencyResponse, 200);
            }
        }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'group-class';
        $order->ref_type = 1;
        $order->ref_id = $groupClass->id;
        $order->price = $groupClass->price;
        $order->outline_id = 0;
        $order->tutor_id = $groupClass->tutor_id;

        if ($this->currencyResponse['success']) {
            $order->currency_id = $this->currencyResponse['result']->currency_id;
            $order->currency_exchange = $this->currencyResponse['result']->exchange;
            $order->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $order->save();

        $order->url = url('/').'/api/wallet/checkout/'.$order->id;
        return response([
            'order_id' => $order->id,
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            'url' => url('/').'/api/wallet/checkout/'.$order->id,
            // 'url' => url('/').'/paypal/'.$order->id
        ], 200);
    }

    public function tutorDates(Request $request, $id)
    {
        /*return response([
                'success' => true,
                'result' => [
                    '2023-07-02' => ['01:00','01:30','02:00'],
                    '2023-07-03' => ['01:00','01:30','02:00'],
                    '2023-07-04' => ['01:00','01:30','02:00'],
                    '2023-07-02' => ['01:00','01:30','02:00'],
                    '2023-07-05' => ['01:00','01:30','02:00'],
                    '2023-07-06' => ['01:00','01:30','02:00'],
                    '2023-07-07' => ['01:00','01:30','02:00']
                    ]
        ] , 200);*/

        $user = Auth::user();
        // if(!$user) {
        //     return response([
        //             'success' => false,
        //             'message' => 'user-dose-not-exist',
        //             'msg-code' => '111'
        //     ], 200);
        // }

        $tutor = Tutor::find($id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $dates = [];
        $tutor->availabilities = $tutor->availabilities()->get();
        if ($tutor->availabilities) {
            for ($day = 0; $day < 7; $day++) {
                $book_date = date('Y-m-d H:i:s', strtotime($request->from_date.' 00:00:00 +'.$day.' day'));
                $book_start = new DateTime($book_date);

                foreach ($tutor->availabilities as $availability) {
                    $availability->timezone;
                    $availability->day;
                    // $dates[strtolower($availability->day->name).'-'.strtolower($book_start->format('l'))]=[];
                    if (strtolower($availability->day->name) == strtolower($book_start->format('l'))) {
                        $dates[$book_start->format('Y-m-d')] = [];
                        $period = 0;
                        $start_hour = $book_start->format('Y-m-d').' '.$availability->hour_from.':00';
                        $book_start_hour = new DateTime($start_hour);
                        $end_hour = date('Y-m-d H:i:s', strtotime($book_start->format('Y-m-d').' '.$availability->hour_to.':00'));
                        $book_end_hour = new DateTime($end_hour);
                        do {
                            $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $start_hour, 40);
                            if ($checkAllowBooking['success']) {
                                $dates[$book_start->format('Y-m-d')][] = $book_start_hour->format('H:i');
                            }
                            $period += 30;
                            $start_hour = date('Y-m-d H:i:s', strtotime($book_start->format('Y-m-d').' '.$availability->hour_from.':00 +'.$period.' minutes'));
                            $book_start_hour = new DateTime($start_hour);
                        } while ($book_start_hour < $book_end_hour);
                        $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $end_hour, 40);
                        if ($checkAllowBooking['success']) {
                            $dates[$book_start->format('Y-m-d')][] = $book_end_hour->format('H:i');
                        }
                    }
                }
            }
        }

        return response([
            'success' => true,
            'result' => $dates,
        ], 200);

        $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $request->from_date.' 00:00:00', 40);
        if (! $checkAllowBooking['success']) {
            return response($checkAllowBooking, 200);
        }

        $tutor->email = null;
        $tutor->availabilities = $tutor->availabilities()->get();

        if ($tutor->availabilities) {
            foreach ($tutor->availabilities as $availability) {
                $availability->timezone;
                $availability->day;
            }
        }

        $tutor_conferences = Conference::select('start_date_time', 'end_date_time')
            ->where('tutor_id', $tutor->id)
            ->whereRaw('start_date_time between ? and ? or end_date_time between ? and ?', [$request->from_date, $request->to_date, $request->from_date, $request->to_date])
            ->get();
        $student_conferences = Conference::select('start_date_time', 'end_date_time')
            ->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>0) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=0)', [$user->id, $user->id])
            ->whereRaw('start_date_time between ? and ? or end_date_time between ? and ?', [$request->from_date, $request->to_date, $request->from_date, $request->to_date])
            ->get();

        return response([
            'success' => true,
            'tutor' => $tutor,
            // 'availabilities' => $tutor->availabilities,
            'tutor_conferences' => $tutor_conferences,
            'student_conferences' => $student_conferences,
        ], 200);
    }

    public function trialLesson(Request $request, $id)
    {
        // dd('123');
        $user = Auth::user();
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $tutor = Tutor::find($id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        if ($tutor->type != 2) {
            return response([
                'success' => false,
                'message' => 'tutor-is-not-classefied',
                'msg-code' => '333',
            ], 200);
        }

        $checkAllowOrder = Order::where('user_id', $user->id)->where('ref_type', 3)->first(); // ->where('ref_id',$tutor->id)
        if ($checkAllowOrder) {
            return response([
                'success' => false,
                'message' => 'trial-lesson-dose-not-allow',
                'msg-code' => '444',
            ], 200);
        }

        $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $request->date['start_date_time'], 15);
        if (! $checkAllowBooking['success']) {
            return response($checkAllowBooking, 200);
        }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'trial-lesson';
        $order->dates = json_encode($request->date);
        $order->ref_type = 3;
        $order->ref_id = $tutor->id;
        $order->price = 0;
        $order->outline_id = 0;
        $order->tutor_id = $tutor->id;
        $order->save();

        $conference = new Conference;
        $conference->student_id = $order->user_id;
        $conference->tutor_id = $order->ref_id;

        $conference->ref_id = $order->ref_id;
        $conference->ref_type = $order->ref_type;
        $conference->order_id = $order->id;

        $conference->title = 'trial-lesson-at '.$request->date['start_date_time'];

        $conference->start_date_time = $request->date['start_date_time'];
        $conference->end_date_time = $request->date['end_date_time'];

        $start_date_time = explode(' ', $conference->start_date_time);
        $end_date_time = explode(' ', $conference->end_date_time);
        // echo $end_date_time;exit;
        // echo date("H:iA", strtotime($date_time[1]));exit;

        $conference->date = $request->date['date'];
        $conference->start_time = Carbon::parse($request->date['start_date_time'])->format('h:iA');
        $conference->end_time = Carbon::parse($request->date['end_date_time'])->format('h:iA');
        $conference->record = 3;
        $conference->timezone = 35;

        $conference->type = 'zoom'; // braincert
        $conference->status = 0;
        $conference->save();

        $postValues = [
            'title' => $conference->title,
            'timezone' => 35,
            'start_time' => $conference->start_time,
            'end_time' => $conference->end_time,
            'date' => $conference->date,
            'record' => 3,
        ];

        $zoom = new ZoomController;
        $result = $zoom->createMeeting($postValues);

        $conference->response = json_encode($result);
        $conference->save();


        // $braincert = new BraincertController;
        // $conference->response = $braincert->conferenceCreate($postValues);
        // // $conference->notes = json_encode($postValues);
        // $conference->save();

        return response([
            'success' => true,
            'message' => 'conference-added-successfully',
            'order' => $order,
            'conference' => $conference,
        ], 200);

    }

    public function checkAllowBooking($student, $tutor, $booking_date_time, $period)
    {
        $now = new DateTime('now');
        $start_date = $booking_date_time;
        $end_date = date('Y-m-d H:i:s', strtotime($start_date.' +'.$period.' minutes'));
        $book_start = new DateTime($start_date);
        $book_end = new DateTime($end_date);
        if ($book_start <= $now) {
            return [
                'success' => false,
                'message' => 'book-date-is-old',
                'msg-code' => '555',
                'book_start' => $book_start,
                'now' => $now,
            ];
        }

        if ($tutor) {
            // ======================================================
            // التحقق من توفر المعلم في الوقت المحدد
            $availabileTime = false;
            $tutor->availabilities = $tutor->availabilities()->get();
            
            if ($tutor->availabilities && $tutor->availabilities->isNotEmpty()) {
                $dayName = strtolower($book_start->format('l')); // Monday, Tuesday, etc.
                
                foreach ($tutor->availabilities as $availability) {
                    // التحقق من وجود day object
                    if (!isset($availability->day) || !isset($availability->day->name)) {
                        continue;
                    }
                    
                    $availabilityDayName = strtolower($availability->day->name);
                    
                    // التحقق من أن اليوم متطابق
                    if ($availabilityDayName == $dayName) {
                        // بناء تواريخ البداية والنهاية للفترة المتاحة
                        $availabile_from = new DateTime($book_start->format('Y-m-d').' '.$availability->hour_from);
                        $availabile_to = new DateTime($book_start->format('Y-m-d').' '.$availability->hour_to);
                        
                        // التحقق من أن وقت الحجز يقع ضمن الفترة المتاحة
                        if ($book_start >= $availabile_from && $book_end <= $availabile_to) {
                            $availabileTime = true;
                            break; // وجدنا فترة متاحة، لا حاجة للبحث أكثر
                        }
                    }
                }
            }

            if (! $availabileTime) {
                return [
                    'success' => false,
                    'message' => 'tutor-not-availabile-time',
                    'msg-code' => '666',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ];
            }
            // ======================================================

            $checkConflictTutorDate = Conference::where('tutor_id', $tutor->id)->whereRaw('(start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$start_date, $end_date, $start_date, $start_date, $end_date, $end_date])->first();
            if ($checkConflictTutorDate) {
                return [
                    'success' => false,
                    'message' => 'tutor-date-conflict',
                    'msg-code' => '666',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ];
            }
        }

        // $checkConflictStudentDate = Conference::whereRaw('((student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)) and (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$student->id,$student->id,$start_date , $end_date,$start_date , $start_date , $end_date, $end_date])->first();
        // if($checkConflictStudentDate) {
        //     return [
        //             'success' => false,
        //             'message' => 'student-date-conflict',
        //             'msg-code' => '777',
        //             'start_date' => $start_date,
        //             'end_date' => $end_date,
        //             'conference' => $checkConflictStudentDate
        //     ];
        // }

        return [
            'success' => true,
        ];
    }

    /**
     * جلب آخر موعد متاح للحجز
     * 
     * @param User $tutor المعلم
     * @param int $period مدة الحجز بالدقائق
     * @param int $daysToCheck عدد الأيام للبحث (افتراضي: 30 يوم)
     * @return array|null آخر موعد متاح أو null إذا لم يوجد
     */
    public function getLastAvailableBookingTime($tutor, $period = 40, $daysToCheck = 30)
    {
        if (!$tutor) {
            return null;
        }

        $now = new DateTime('now');
        
        // الحصول على جميع المواعيد المتاحة
        $availabilities = $tutor->availabilities()->get();
        
        if (!$availabilities || $availabilities->isEmpty()) {
            return null;
        }

        // حد أقصى للبحث
        $maxSearchDate = clone $now;
        $maxSearchDate->modify("+{$daysToCheck} days");
        
        // البدء من بكرا (اليوم التالي)
        $currentDate = clone $now;
        $currentDate->modify('+1 day');
        $currentDate->setTime(0, 0, 0); // بداية اليوم
        $daysSearched = 0;
        
        // البحث من بكرا للأمام حتى نجد موعد متاح
        while ($currentDate <= $maxSearchDate && $daysSearched < $daysToCheck) {
            $dateKey = $currentDate->format('Y-m-d');
            $dayName = strtolower($currentDate->format('l'));
            
            // الحصول على المواعيد المتاحة لهذا اليوم
            $dayAvailabilities = $availabilities->filter(function($av) use ($dayName) {
                if (!isset($av->day) || !isset($av->day->name)) {
                    return false;
                }
                return strtolower($av->day->name) == $dayName;
            });

            if ($dayAvailabilities->isNotEmpty()) {
                // الحصول على جميع الحجوزات لهذا اليوم
                $dayBookings = Conference::where('tutor_id', $tutor->id)
                    ->whereDate('start_date_time', $dateKey)
                    ->whereNotNull('start_date_time')
                    ->whereNotNull('end_date_time')
                    ->get();
                
                // إنشاء خريطة للحجوزات لهذا اليوم
                $bookingsMap = [];
                foreach ($dayBookings as $booking) {
                    $bookingStart = new DateTime($booking->start_date_time);
                    $bookingEnd = new DateTime($booking->end_date_time);
                    
                    $bookingsMap[] = [
                        'start' => $bookingStart,
                        'end' => $bookingEnd,
                    ];
                }
                
                // البحث في كل فترة متاحة
                foreach ($dayAvailabilities as $availability) {
                    $availableFrom = new DateTime($dateKey . ' ' . $availability->hour_from);
                    $availableTo = new DateTime($dateKey . ' ' . $availability->hour_to);
                    
                    // البحث عن أول وقت متاح في هذه الفترة
                    // نبدأ من بداية الفترة (لأننا نبدأ من بكرا)
                    $testTime = clone $availableFrom;
                    
                    // البحث عن أول وقت متاح بعد الحجوزات
                    while ($testTime < $availableTo) {
                        $testEnd = clone $testTime;
                        $testEnd->modify("+{$period} minutes");
                        
                        // التأكد من أن الفترة لا تتجاوز نهاية الفترة المتاحة
                        if ($testEnd > $availableTo) {
                            break;
                        }
                        
                        // التحقق من عدم التعارض مع الحجوزات
                        $hasConflict = false;
                        foreach ($bookingsMap as $booking) {
                            // التحقق من التعارض
                            if (($testTime >= $booking['start'] && $testTime < $booking['end']) ||
                                ($testEnd > $booking['start'] && $testEnd <= $booking['end']) ||
                                ($testTime <= $booking['start'] && $testEnd >= $booking['end'])) {
                                $hasConflict = true;
                                break;
                            }
                        }
                        
                        if (!$hasConflict) {
                            // وجدنا أول موعد متاح
                            return [
                                'success' => true,
                                'start_date_time' => $testTime->format('Y-m-d H:i:s'),
                                'end_date_time' => $testEnd->format('Y-m-d H:i:s'),
                                'date' => $dateKey,
                                'day_name' => ucfirst($dayName),
                            ];
                        }
                        
                        // نذهب 30 دقيقة للأمام ونحاول مرة أخرى
                        $testTime->modify('+30 minutes');
                    }
                }
            }
            
            // الانتقال لليوم التالي
            $currentDate->modify('+1 day');
            $daysSearched++;
        }

        // لم نجد أي موعد متاح
        return [
            'success' => false,
            'message' => 'no-available-time-found',
        ];
    }

    public function privateLesson(Request $request, $id)
    {
        // dd('123');
        $user = Auth::user();
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $tutor = Tutor::find($id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        if ($tutor->type != 2) {
            return response([
                'success' => false,
                'message' => 'tutor-is-not-classefied',
                'msg-code' => '333',
            ], 200);
        }

        $hourlyPrice = (int)($tutor?->tutorProfile?->hourly_rate ?? 0);
        if ($hourlyPrice == 0) {
            return response([
                'success' => false,
                'message' => 'hourly-price-not-found',
                'msg-code' => '333',
            ], 200);
        }

        // بعد ما تراجع اساسيات المواعيد واختيار موعد تفعل
        // $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $request->date, 40);
        // if (! $checkAllowBooking['success']) {
        //     return response($checkAllowBooking, 200);
        // }

        // if (! empty($request->currency_id)) {
        //     $currency = new CurrencyController;
        //     $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
        //     if (! $this->currencyResponse['success']) {
        //         return response($this->currencyResponse, 200);
        //     }
        // }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'private-lesson';
        $order->dates = json_encode($request->date);
        $order->ref_type = 4;
        $order->ref_id = $tutor->id;
        $order->price = $hourlyPrice;
        $order->outline_id = 0;
        $order->tutor_id = $tutor->id;

        if ($this->currencyResponse['success']) {
            $order->currency_id = $this->currencyResponse['result']->currency_id;
            $order->currency_exchange = $this->currencyResponse['result']->exchange;
            $order->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $order->save();

        $order->url = url('/').'/api/wallet/checkout/'.$order->id;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            'url' => url('/').'/api/wallet/checkout/'.$order->id,
            // 'url' => url('/').'/paypal/'.$order->id
        ], 200);

    }

    public function topUp(Request $request, $amount)
    {
        // dd('123');
        $user = Auth::user();
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        if (! empty($request->currency_id)) {
            $currency = new CurrencyController;
            $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
            if (! $this->currencyResponse['success']) {
                return response($this->currencyResponse, 200);
            }
        }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'top-up';
        $order->dates = $request->date;
        $order->ref_type = 5;
        $order->ref_id = $user->id;
        $order->price = $amount;

        if ($this->currencyResponse['success']) {
            $order->price = $amount / $this->currencyResponse['result']->exchange;
            $order->currency_id = $this->currencyResponse['result']->currency_id;
            $order->currency_exchange = $this->currencyResponse['result']->exchange;
            $order->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $order->save();

        $order->url = url('/').'/'.$request->payment.'/'.$order->id;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            // 'url' => url('/') . '/'.$request->payment.'/' . $order->id,
            'url' => 'https://jinntest.jinnedu.com/checkout-response/'.$order->id.'/success',
        ], 200);

    }

    public function refund(Request $request, $orderid)
    {
        $admin = Auth::user();

        $refOrder = Order::where('id', $orderid)->where('status', 1)->first();
        if (! $refOrder) {
            return response([
                'success' => false,
                'message' => 'order-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $user = User::find($refOrder->user_id);
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $wallet = $user->wallets()->first();
        if (! $wallet) {
            return response([
                'success' => false,
                'message' => 'wallet-dose-not-exist',
                'msg-code' => '333',
            ], 200);
        }

        $order = new Order;
        $order->user_id = $admin->id;
        $order->ipaddress = $request->ip();
        $order->note = 'refund';
        $order->ref_type = 6;
        $order->ref_id = $refOrder->id;
        $order->price = $refOrder->price;
        $order->tutor_id = $refOrder->tutor_id;

        $order->currency_id = $refOrder->currency_id;
        $order->currency_exchange = $refOrder->exchange;
        $order->currency_code = $refOrder->currency_code;

        $order->save();

        $order->url = url('/').'/api/wallet/refund/'.$order->id;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            'url' => url('/').'/api/wallet/refund/'.$order->id,
        ], 200);

    }

    public function ourCourse(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $ourCourse = OurCourse::find($id);
        if (! $ourCourse) {
            return response([
                'success' => false,
                'message' => 'our-course-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $tutor = Tutor::find($request->tutor_id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $ourCourseTutor = OurCourseTutor::where('our_course_id', $ourCourse->id)->where('tutor_id', $tutor->id)->first();
        if (! $ourCourseTutor) {
            return response([
                'success' => false,
                'message' => 'tutor-not-register-in-course',
                'msg-code' => '333',
            ], 200);
        }

        $hourlyPrices = $ourCourseTutor->tutor->hourlyPrices()->orderBy('id', 'desc')->first();
        if (! $hourlyPrices) {
            return response([
                'success' => false,
                'message' => 'hourly-price-not-found',
                'msg-code' => '444',
            ], 200);
        }

        if (! isset($request->dates) || count($request->dates) == 0) {
            return response([
                'success' => false,
                'message' => 'dates-not-found',
                'msg-code' => '555',
            ], 200);
        }

        foreach ($request->dates as $lesson_date) {
            $checkAllowBooking = $this->checkAllowBooking($user, $tutor, $lesson_date['date'], 40);
            if (! $checkAllowBooking['success']) {
                return response($checkAllowBooking, 200);
            }
        }

        if (! empty($request->currency_id)) {
            $currency = new CurrencyController;
            $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
            if (! $this->currencyResponse['success']) {
                return response($this->currencyResponse, 200);
            }
        }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'our-course';
        $order->ref_type = 2;
        $order->ref_id = $ourCourse->id;
        $order->price = $hourlyPrices->price * $request->lessons; // $hourlyPrices->price
        $order->lessons = $request->lessons;
        $order->level_id = $request->level_id;
        $order->tutor_id = $request->tutor_id;
        $order->dates = json_encode($request->dates);

        if ($this->currencyResponse['success']) {
            $order->currency_id = $this->currencyResponse['result']->currency_id;
            $order->currency_exchange = $this->currencyResponse['result']->exchange;
            $order->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $order->save();

        $order->url = url('/').'/api/wallet/checkout/'.$order->id;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            'url' => url('/').'/api/wallet/checkout/'.$order->id,
            // 'url' => url('/').'/paypal/'.$order->id
        ], 200);
    }

    public function package(Request $request, $id)
    {
        // dd('123');
        $user = Auth::user();
        if (! $user) {
            return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $package = Post::where('id', $id)->where('content_type', 11)->first();
        if (! $package) {
            return response([
                'success' => false,
                'message' => 'package-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $package->info = $package->package()->first();
        if (! $package->info) {
            return response([
                'success' => false,
                'message' => 'package-info-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        if (! empty($request->currency_id)) {
            $currency = new CurrencyController;
            $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
            if (! $this->currencyResponse['success']) {
                return response($this->currencyResponse, 200);
            }
        }

        $order = new Order;
        $order->user_id = $user->id;
        $order->ipaddress = $request->ip();
        $order->note = 'package';
        $order->dates = $request->date;
        $order->ref_type = 7;
        $order->ref_id = $package->id;
        $order->price = $package->info->price;

        if ($this->currencyResponse['success']) {
            $order->currency_id = $this->currencyResponse['result']->currency_id;
            $order->currency_exchange = $this->currencyResponse['result']->exchange;
            $order->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $order->save();

        $order->url = url('/').'/api/wallet/checkout/'.$order->id;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $order,
            'url' => url('/').'/api/wallet/checkout/'.$order->id,
            // 'url' => url('/').'/paypal/'.$order->id
        ], 200);

    }
}
