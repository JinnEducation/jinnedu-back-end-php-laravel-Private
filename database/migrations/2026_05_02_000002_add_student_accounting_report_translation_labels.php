<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $labels = [
        'site-balance-report' => ['Site Balance Report', 'تقرير رصيد الموقع'],
        'no-student-accounting-transactions' => ['No student accounting transactions found.', 'لا توجد حركات محاسبية للطلاب.'],
        'total-completed-amount' => ['Total completed amount', 'إجمالي المبالغ المكتملة'],
        'last-month-income' => ['Last month income', 'دخل آخر شهر'],
        'wallet-topups' => ['Wallet top-ups', 'شحن المحفظة'],
        'site-purchases' => ['Site purchases', 'مشتريات الموقع'],
        'wallet-topup' => ['Wallet top-up', 'شحن محفظة'],
        'group-class-purchase' => ['Group class purchase', 'شراء قروب كلاس'],
        'private-lesson-purchase' => ['Private lesson purchase', 'شراء حصة خاصة'],
        'course-purchase' => ['Course purchase', 'شراء كورس'],
        'wallet-package-purchase' => ['Wallet package purchase', 'شراء باقة محفظة'],
        'order-payment' => ['Order payment', 'دفع طلب'],
        'manual-adjustment' => ['Manual adjustment', 'تعديل يدوي'],
        'legacy-credit' => ['Legacy credit', 'حركة إضافة قديمة'],
        'legacy-debit' => ['Legacy debit', 'حركة خصم قديمة'],
        'payment-gateway' => ['Payment gateway', 'بوابة الدفع'],
        'my-wallet' => ['My wallet', 'محفظتي'],
        'local-test' => ['Local test', 'اختبار محلي'],
        'manual' => ['Manual', 'يدوي'],
        'from-date' => ['From date', 'من تاريخ'],
        'to-date' => ['To date', 'إلى تاريخ'],
        'search-students' => ['Search students', 'بحث الطلاب'],
        'failed' => ['Failed', 'فشلت'],
        'canceled' => ['Canceled', 'ملغاة'],
        'completed' => ['Completed', 'مكتملة'],
        'pending' => ['Pending', 'معلقة'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('labels') || ! Schema::hasTable('translations') || ! Schema::hasTable('languages')) {
            return;
        }

        $languages = DB::table('languages')->whereIn('shortname', ['en', 'ar', 'fr', 'de'])->get()->keyBy('shortname');

        foreach ($this->labels as $name => [$english, $arabic]) {
            $label = DB::table('labels')->where('file', 'global')->where('name', $name)->first();
            $labelId = $label?->id ?: DB::table('labels')->insertGetId([
                'name' => $name,
                'file' => 'global',
                'title' => $english,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($label) {
                DB::table('labels')->where('id', $labelId)->update([
                    'title' => $english,
                    'updated_at' => now(),
                ]);
            }

            foreach (['en' => $english, 'ar' => $arabic, 'fr' => $english, 'de' => $english] as $shortname => $title) {
                if (! isset($languages[$shortname])) {
                    continue;
                }

                DB::table('translations')->updateOrInsert(
                    ['labelid' => $labelId, 'langid' => $languages[$shortname]->id],
                    ['title' => $title, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('labels') || ! Schema::hasTable('translations')) {
            return;
        }

        $labelIds = DB::table('labels')
            ->where('file', 'global')
            ->whereIn('name', array_keys($this->labels))
            ->pluck('id');

        DB::table('translations')->whereIn('labelid', $labelIds)->delete();
        DB::table('labels')->whereIn('id', $labelIds)->delete();
    }
};
