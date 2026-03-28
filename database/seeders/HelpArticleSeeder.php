<?php

namespace Database\Seeders;

use App\Models\HelpArticle;
use App\Models\HelpArticleLang;
use App\Models\Language;
use Illuminate\Database\Seeder;

class HelpArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $englishId = Language::where('shortname', 'en')->value('id');
        $arabicId = Language::where('shortname', 'ar')->value('id');

        if (! $englishId || ! $arabicId) {
            return;
        }

        $icons = [
            '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 4a7 7 0 105.29 11.58l3.56 3.56a1 1 0 001.42-1.42l-3.56-3.56A7 7 0 0011 4zm0 2a5 5 0 110 10 5 5 0 010-10z" fill="#1B449C"/></svg>',
            '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 2a1 1 0 011 1v1h8V3a1 1 0 112 0v1h1a3 3 0 013 3v11a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h1V3a1 1 0 011-1zm12 8H5v8a1 1 0 001 1h12a1 1 0 001-1v-8z" fill="#1B449C"/></svg>',
            '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.42 0-8 2.01-8 4.5V21h16v-2.5c0-2.49-3.58-4.5-8-4.5z" fill="#1B449C"/></svg>',
            '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 1a1 1 0 011 1v1.06a7.002 7.002 0 016 6.94V12a3 3 0 003 3h1a1 1 0 110 2h-1a5 5 0 01-5-5V10a5 5 0 10-10 0v4a2 2 0 11-2 2V10a7.002 7.002 0 016-6.94V2a1 1 0 011-1z" fill="#1B449C"/></svg>',
        ];

        $studentItems = [
            ['title_en' => 'Find Tutor', 'slug_en' => 'help-student-find-tutor', 'title_ar' => 'البحث عن مدرس', 'slug_ar' => 'help-student-find-tutor-ar'],
            ['title_en' => 'Booking Basics', 'slug_en' => 'help-student-booking-basics', 'title_ar' => 'أساسيات الحجز', 'slug_ar' => 'help-student-booking-basics-ar'],
            ['title_en' => 'Classroom Tools', 'slug_en' => 'help-student-classroom-tools', 'title_ar' => 'أدوات الفصل', 'slug_ar' => 'help-student-classroom-tools-ar'],
            ['title_en' => 'Homework Tips', 'slug_en' => 'help-student-homework-tips', 'title_ar' => 'نصائح الواجبات', 'slug_ar' => 'help-student-homework-tips-ar'],
            ['title_en' => 'Payment Guide', 'slug_en' => 'help-student-payment-guide', 'title_ar' => 'دليل الدفع', 'slug_ar' => 'help-student-payment-guide-ar'],
            ['title_en' => 'Reschedule Class', 'slug_en' => 'help-student-reschedule-class', 'title_ar' => 'إعادة جدولة الحصة', 'slug_ar' => 'help-student-reschedule-class-ar'],
            ['title_en' => 'Track Progress', 'slug_en' => 'help-student-track-progress', 'title_ar' => 'متابعة التقدم', 'slug_ar' => 'help-student-track-progress-ar'],
            ['title_en' => 'Support Center', 'slug_en' => 'help-student-support-center', 'title_ar' => 'مركز الدعم', 'slug_ar' => 'help-student-support-center-ar'],
        ];

        $tutorItems = [
            ['title_en' => 'Tutor Profile Setup', 'slug_en' => 'help-tutor-profile-setup', 'title_ar' => 'إعداد ملف المدرس', 'slug_ar' => 'help-tutor-profile-setup-ar'],
            ['title_en' => 'Availability and Pricing', 'slug_en' => 'help-tutor-availability-pricing', 'title_ar' => 'التوفر والتسعير', 'slug_ar' => 'help-tutor-availability-pricing-ar'],
            ['title_en' => 'Lesson Planning', 'slug_en' => 'help-tutor-lesson-planning', 'title_ar' => 'تخطيط الدروس', 'slug_ar' => 'help-tutor-lesson-planning-ar'],
            ['title_en' => 'Student Engagement', 'slug_en' => 'help-tutor-student-engagement', 'title_ar' => 'تفاعل الطلاب', 'slug_ar' => 'help-tutor-student-engagement-ar'],
            ['title_en' => 'Assignments and Feedback', 'slug_en' => 'help-tutor-assignments-feedback', 'title_ar' => 'الواجبات والتغذية الراجعة', 'slug_ar' => 'help-tutor-assignments-feedback-ar'],
            ['title_en' => 'Calendar Management', 'slug_en' => 'help-tutor-calendar-management', 'title_ar' => 'إدارة الجدول', 'slug_ar' => 'help-tutor-calendar-management-ar'],
            ['title_en' => 'Payouts and Finance', 'slug_en' => 'help-tutor-payouts-finance', 'title_ar' => 'المدفوعات والمالية', 'slug_ar' => 'help-tutor-payouts-finance-ar'],
            ['title_en' => 'Tutor Support Center', 'slug_en' => 'help-tutor-support-center', 'title_ar' => 'مركز دعم المدرسين', 'slug_ar' => 'help-tutor-support-center-ar'],
        ];

        $items = [];

        foreach ($studentItems as $index => $item) {
            $items[] = [
                'audience' => 'student',
                'slug' => $item['slug_en'],
                'icon_svg' => $icons[$index % count($icons)],
                'icon' => null,
                'status' => 'published',
                'langs' => [
                    'en' => [
                        'title' => $item['title_en'],
                        'description' => '<p>This help article explains key steps, best practices, and common mistakes so students can use this feature smoothly.</p>',
                    ],
                    'ar' => [
                        'title' => $item['title_ar'],
                        'description' => '<p>تشرح هذه المقالة خطوات مهمة وأفضل الممارسات والأخطاء الشائعة حتى يتمكن الطالب من استخدام هذه الميزة بسهولة.</p>',
                    ],
                ],
            ];
        }

        foreach ($tutorItems as $index => $item) {
            $items[] = [
                'audience' => 'tutor',
                'slug' => $item['slug_en'],
                'icon_svg' => $icons[$index % count($icons)],
                'icon' => null,
                'status' => 'published',
                'langs' => [
                    'en' => [
                        'title' => $item['title_en'],
                        'description' => '<p>This article helps tutors optimize workflow, improve lesson delivery, and manage platform tools more efficiently.</p>',
                    ],
                    'ar' => [
                        'title' => $item['title_ar'],
                        'description' => '<p>تساعد هذه المقالة المدرسين على تحسين سير العمل وتطوير تقديم الدروس وإدارة أدوات المنصة بكفاءة أعلى.</p>',
                    ],
                ],
            ];
        }

        foreach ($items as $item) {
            $article = HelpArticle::updateOrCreate(
                [
                    'audience' => $item['audience'],
                    'slug' => $item['slug'],
                ],
                [
                    'icon' => $item['icon'],
                    'icon_svg' => $item['icon_svg'],
                    'status' => $item['status'],
                ]
            );

            HelpArticleLang::updateOrCreate(
                ['help_article_id' => $article->id, 'language_id' => $englishId],
                $item['langs']['en']
            );

            HelpArticleLang::updateOrCreate(
                ['help_article_id' => $article->id, 'language_id' => $arabicId],
                $item['langs']['ar']
            );
        }
    }
}
