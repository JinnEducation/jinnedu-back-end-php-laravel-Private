<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function singlecourse($locale, $id)
    {

        $cacheKey = "front:course:{$locale}:{$id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($locale, $id) {

            $course = Course::query()
                ->with([
                    'category:id,name',
                    'instructor:id,name',
                    'activeDiscount',

                    // ✅ لغة واحدة فقط
                    'langs' => fn ($q) => $q->where('lang', $locale),

                    // الأقسام
                    'sections' => fn ($q) => $q->orderBy('sort_order'),
                    'sections.langs' => fn ($q) => $q->where('lang', $locale),

                    // عناصر الأقسام
                    'sections.items' => fn ($q) => $q->orderBy('sort_order'),
                    'sections.items.langs' => fn ($q) => $q->where('lang', $locale),

                    // Reviews (عرض فقط)
                    'reviews' => fn ($q) => $q->latest('id')->limit(20),
                    'reviews.user:id,name',
                ])
                ->where('status', 'published')
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | بيانات مشتقة (Derived Data)
            |--------------------------------------------------------------------------
            */

            $courseLang = $course->langs->first(); // لغة واحدة → safe

            // إجمالي مدة الكورس
            $totalSeconds = 0;
            foreach ($course->sections as $section) {
                foreach ($section->items as $item) {
                    $totalSeconds += (int) ($item->duration_seconds ?? 0);
                }
            }

            // Course Content (sections + items)
            $content = $course->sections->map(function ($section) {
                return [
                    'title' => optional($section->langs->first())->title ?? '-',
                    'items' => $section->items->map(function ($item) {
                        return [
                            'title' => optional($item->langs->first())->title ?? '-',
                            'duration_seconds' => (int) ($item->duration_seconds ?? 0),
                        ];
                    }),
                ];
            });

            // Reviews summary
            $avgRating = (float) $course->reviews()->avg('rating');
            $reviewsCount = (int) $course->reviews()->count();

            $ratingsDist = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            $dist = $course->reviews()
                ->selectRaw('rating, COUNT(*) as c')
                ->groupBy('rating')
                ->pluck('c', 'rating');

            foreach ($dist as $rating => $count) {
                $ratingsDist[(int) $rating] = (int) $count;
            }

            $related = Course::query()
                ->with([
                    'langs' => fn ($q) => $q->where('lang', $locale),
                    'activeDiscount',
                ])
                ->where('status', 'published')
                ->where('id', '!=', $course->id)
                ->where('category_id', $course->category_id)
                ->latest('id')
                ->limit(4)
                ->get();

            return [
                'course' => $course,
                'courseLang' => $courseLang,
                'content' => $content,
                'totalSeconds' => $totalSeconds,
                'outcomes' => (array) ($courseLang->outcomes_json ?? []),
                'requirements' => (array) ($courseLang->requirements_json ?? []),
                'avgRating' => $avgRating,
                'reviewsCount' => $reviewsCount,
                'ratingsDist' => $ratingsDist,
                'related' => $related,
            ];
        });

        return view('front.singlecourse', $data);
    }

    public function bookCourse($locale, $id)
    {
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $course = Course::with(['instructor:id,name', 'activeDiscount'])->findOrFail($id);

            $alreadyEnrolled = CourseEnrollment::where('course_enrollments.user_id', $user->id)
                ->where('course_enrollments.course_id', $course->id)
                ->whereHas('order', function ($q) {
                    $q->where('status', 1); // مدفوع
                })
                ->exists();

            if ($alreadyEnrolled) {
                return redirect()
                    ->route('redirect.dashboard')
                    ->with('error', 'You are already enrolled in this course');
            }

            $orderController = new OrderController;

            $response = $orderController->courseUser(request(), $course);
            $original = $response->getOriginalContent();
            if ($original['success']) {
                $walletController = new WalletController;
                $responseCheckout = $walletController->checkout($original['result']['id'] ?? $original['order_id']);
                $originalCheckout = $responseCheckout->getOriginalContent();
                // dd(vars: $originalCheckout);

                // if (! $originalCheckout['success']) {
                //     return redirect()->back()->with('error', $originalCheckout['message']);
                // }

                $orderId = $original['result']['id'] ?? $original['order']['id'];
                $order = Order::find($orderId);

                if (! $order) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Order not found');
                }

                if ($course->is_free) {
                    CourseEnrollment::firstOrCreate([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ], [
                        'order_id' => $order->id,
                        'enrolled_at' => now(),
                    ]);

                    DB::commit();

                    return redirect()
                        ->route('redirect.dashboard')
                        ->with('success', 'Course enrolled successfully');
                }

                // هل يريد الدفع مباشرة من المحفظة؟
                $payDirectly = request()->get('pay_directly', false);

                $user = Auth::user();
                $wallet = $user->wallets()->first();
                $walletBalance = $wallet ? $wallet->balance : 0;
                $hasEnoughBalance = $walletBalance >= $order->price;
                // الدفع المباشر
                if ($payDirectly && $hasEnoughBalance) {
                    $responseCheckout = $walletController->checkout($orderId);
                    $originalCheckout = $responseCheckout->getOriginalContent();

                    if ($originalCheckout['success']) {
                        // $walletController->addTutorFinance($order, $order->ref_id, 2);
                        DB::commit();

                        return redirect()->route('redirect.dashboard')
                            ->with('success', $originalCheckout['message']);
                    } else {
                        DB::rollBack();

                        // إذا فشل الدفع نرسله لصفحة checkout
                        return redirect()->route('checkout', [
                            'type' => 'pay',
                            'order_ids' => $orderId,
                        ])->with('error', $originalCheckout['message'] ?? 'Payment failed');
                    }
                }

                // التوجيه إلى صفحة الدفع مع خيارات الدفع
                DB::commit();

                return redirect()->route('checkout', [
                    'type' => 'pay',
                    'order_ids' => $orderId,
                ])->with('info', $hasEnoughBalance
                    ? 'You can pay from your wallet or choose another payment method'
                    : 'Please complete payment to finish your order'
                );

            } else {
                DB::rollBack();

                return redirect()->back()->with('error', $original['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e;

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
