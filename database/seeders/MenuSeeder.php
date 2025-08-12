<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use App\Models\Role;
use App\Models\User;
use App\Models\Language;
use App\Models\Label;
use App\Models\Translation;

use App\Models\UserCertification;
use App\Models\UserEducation;
use App\Models\UserLanguage;
use App\Models\UserHourlyPrice;

use App\Models\GroupClass;
use App\Models\OurCourse;

use App\Models\UserFavorite;
use App\Models\UserInterest;

use App\Models\Review;
use App\Models\Support;
use App\Models\ConferenceComplaint;
use App\Models\Conference;
use App\Models\Order;
use App\Models\Chat;
use App\Models\ParentInvitation;

use App\Models\WeekDay;
use App\Models\WorldTimezone;
use App\Models\DegreeType;
use App\Models\Subject;
use App\Models\Situation;
use App\Models\Experience;
use App\Models\Level;
use App\Models\Currency;
use App\Models\Specialization;
use App\Models\Country;
use App\Models\Outline;
use App\Models\Frequency;
use App\Models\Price;
use App\Models\SortByTutor;

use App\Models\Department;
use App\Models\Category;
use App\Models\Course;
use App\Models\Navigation;

use App\Models\Post;
use App\Models\Page;
use App\Models\Package;
use App\Models\Video;
use App\Models\Link;
use App\Models\Image;
use App\Models\Event;
use App\Models\Help;
use App\Models\Document;
use App\Models\Advertisement;

//use App\Models\Report;
use App\Models\Menu;
use App\Models\Setting;


use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public $id;

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //================================================
        $this->id=0;
        //================================================
        /*$this->createMenuSubMenus([
            'type' => '', 
            'name' => '',
            'title' => 'الثوابت',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect><path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path></g></svg>',
            'children' =>[
                [
                    'type' => PaymentType::class, 
                    'name' => 'payment-types',
                    'title' => 'انواع الدفع',
                    'indexTitle' => 'قائمة الانواع ',
                    'createTitle' => 'اضافة الانواع',
                    'editTitle' => 'تعديل الانواع',
                    //'showTitle' => 'عرض الانواع',
                    'destroyTitle' => 'حذف الانواع',
                    'svg' => ''
                ],
                [
                    'type' => EstateType::class, 
                    'name' => 'estate-types',
                    'title' => 'انواع العقارات',
                    'indexTitle' => 'قائمة الانواع ',
                    'createTitle' => 'اضافة الانواع',
                    'editTitle' => 'تعديل الانواع',
                    //'showTitle' => 'عرض الانواع',
                    'destroyTitle' => 'حذف الانواع',
                    'svg' => ''
                ],
                [
                    'type' => Expense::class, 
                    'name' => 'expense-types',
                    'title' => 'انواع المصروفات',
                    'indexTitle' => 'قائمة الانواع ',
                    'createTitle' => 'اضافة الانواع',
                    'editTitle' => 'تعديل الانواع',
                    //'showTitle' => 'عرض الانواع',
                    'destroyTitle' => 'حذف الانواع',
                    'svg' => ''
                ],
                [
                    'type' => Currency::class, 
                    'name' => 'currencies',
                    'title' => 'العملات',
                    'indexTitle' => 'قائمة العملات',
                    'createTitle' => 'اضافة العملات',
                    'editTitle' => 'تعديل العملات',
                    //'showTitle' => 'عرض العملات',
                    'destroyTitle' => 'حذف العملات',
                    'svg' => ''
                ],
                [
                    'type' => Governorate::class, 
                    'name' => 'governorates',
                    'title' => 'المحافظات',
                    'indexTitle' => 'قائمة المحافظات',
                    'createTitle' => 'اضافة المحافظات',
                    'editTitle' => 'تعديل المحافظات',
                    //'showTitle' => 'عرض المحافظات',
                    'destroyTitle' => 'حذف المحافظات',
                    'svg' => ''
                ],
                [
                    'type' => Region::class, 
                    'name' => 'regions',
                    'title' => 'المناطق',
                    'indexTitle' => 'قائمة المناطق',
                    'createTitle' => 'اضافة المناطق',
                    'editTitle' => 'تعديل المناطق',
                    //'showTitle' => 'عرض المناطق',
                    'destroyTitle' => 'حذف المناطق',
                    'svg' => ''
                ]
            ]
        ]);*/
        //================================================
        $this->createMenuSubMenus([
            'type' => Setting::class, 
            'name' => 'settings',
            'title' => 'settings-management',
            'indexTitle' => ['settings-list',0],
            'createTitle' => ['add-settings',1],
            'editTitle' => ['update-settings',1],
            'showTitle' => ['view-settings',1],
            'destroyTitle' => ['delete-settings',1],
            'svg' => 'Communication/Group.svg',
            /*'others' => [
                ['name' => 'split','title'=>'افراز','invisible' => 1],
            ]*/
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => User::class, 
            'name' => 'users',
            'title' => 'users-management',
            'indexTitle' => ['users-list',0],
            'createTitle' => ['add-users',1],
            'editTitle' => ['update-users',1],
            'showTitle' => ['view-users',1],
            'destroyTitle' => ['delete-users',1],
            'svg' => 'Communication/Group.svg',
            /*'others' => [
                ['name' => 'split','title'=>'افراز','invisible' => 1],
            ]*/
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => UserCertification::class, 
            'name' => 'certifications',
            'title' => 'certifications-management',
            'indexTitle' => ['certifications-list',0],
            'createTitle' => ['add-certifications',1],
            'editTitle' => ['update-certifications',1],
            'showTitle' => ['view-certifications',1],
            'destroyTitle' => ['delete-certifications',1],
            'svg' => 'Communication/Group.svg',
            /*'others' => [
                ['name' => 'split','title'=>'افراز','invisible' => 1],
            ]*/
        ]);
        //================================================
        /*$this->createMenuSubMenus([
            'type' => Review::class, 
            'name' => 'reviews',
            'title' => 'reviews-management',
            'indexTitle' => ['reviews-list',0],
            'createTitle' => ['add-reviews',1],
            'editTitle' => ['update-reviews',1],
            'showTitle' => ['view-reviews',1],
            'destroyTitle' => ['delete-reviews',1],
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'admin-list','title'=>'admin-list','invisible' => 0],
            ]
        ]);*/
        //================================================
        $this->createMenuSubMenus([
            'type' => ConferenceComplaint::class, 
            'name' => 'complaints',
            'title' => 'complaints-management',
            'indexTitle' => ['complaints-list',0],
            /*'createTitle' => ['add-complaints',1],
            'editTitle' => ['update-complaints',1],*/
            'showTitle' => ['view-complaints',1],
            'destroyTitle' => ['delete-complaints',1],
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'reply','title'=>'reply','invisible' => 1],
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => Support::class, 
            'name' => 'supports',
            'title' => 'supports-management',
            'indexTitle' => ['supports-list',0],
            /*'createTitle' => ['add-supports',1],
            'editTitle' => ['update-supports',1],*/
            'showTitle' => ['view-supports',1],
            'destroyTitle' => ['delete-supports',1],
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'reply','title'=>'reply','invisible' => 1],
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => UserFavorite::class, 
            'name' => 'favorites',
            'title' => 'favorites-management',
            'indexTitle' => ['favorites-list',0],
            'createTitle' => ['add-favorites',1],
            'showTitle' => ['view-favorites',1],
            'destroyTitle' => ['delete-favorites',1],
            'svg' => 'Communication/Group.svg'
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => UserInterest::class, 
            'name' => 'interests',
            'title' => 'interests-management',
            'indexTitle' => ['interests-list',0],
            'createTitle' => ['add-interests',1],
            'showTitle' => ['view-interests',1],
            'destroyTitle' => ['delete-interests',1],
            'svg' => 'Communication/Group.svg'
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => Role::class, 
            'name' => 'roles',
            'title' => 'roles-management',
            'indexTitle' => ['roles-list',0],
            'createTitle' => ['add-roles',1],
            'editTitle' => ['update-roles',1],
            'showTitle' => ['view-roles',1],
            'destroyTitle' => ['delete-roles',1],
            'svg' => 'Communication/Group.svg',
            /*'others' => [
                ['name' => 'split','title'=>'افراز','invisible' => 1],
            ]*/
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => GroupClass::class, 
            'name' => 'group-classes',
            'title' => 'group-classes-management',
            'indexTitle' => ['group-classes-list',0],
            'createTitle' => ['add-group-classes',0],
            'editTitle' => ['update-group-classes',1],
            'showTitle' => ['view-group-classes',1],
            'destroyTitle' => ['delete-group-classes',1],
            'svg' => 'Communication/Group.svg',
            /*'others' => [
                ['name' => 'split','title'=>'افراز','invisible' => 1],
            ]*/
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => OurCourse::class, 
            'name' => 'our-courses',
            'title' => 'our-courses-management',
            'indexTitle' => ['our-courses-list',0],
            'createTitle' => ['add-our-courses',0],
            'editTitle' => ['update-our-courses',1],
            'showTitle' => ['view-our-courses',1],
            'destroyTitle' => ['delete-our-courses',1],
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'register-as-tutor','title'=>'register-as-tutor','invisible' => 1],
                ['name' => 'un-register-as-tutor','title'=>'un-register-as-tutor','invisible' => 1],
                ['name' => 'tutor-index','title'=>'tutor-index','invisible' => 0]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => Conference::class, 
            'name' => 'conferences',
            'title' => 'conferences-management',
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'admin-index','title'=>'admin-conferences','invisible' => 0],
                //['name' => 'reviews-managment','title'=>'reviews-managment','invisible' => 0],
                ['name' => 'student-change-date','title'=>'student-change-conference-date','invisible' => 1],
                ['name' => 'tutor-change-date','title'=>'tutor-change-conference-date','invisible' => 1],
                ['name' => 'tutor-index','title'=>'tutor-conferences','invisible' => 0],
                ['name' => 'student-index','title'=>'student-conferences','invisible' => 0],
                ['name' => 'create-tutor-link','title'=>'create-conference-tutor-link','invisible' => 1],
                ['name' => 'create-student-link','title'=>'create-conference-student-link','invisible' => 1],
                ['name' => 'upload-file','title'=>'upload-file','invisible' => 1],
                ['name' => 'add-note','title'=>'add-note','invisible' => 1],
                ['name' => 'add-complaint','title'=>'add-complaint','invisible' => 1],
                //['name' => 'add-review','title'=>'add-review','invisible' => 1]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => Order::class, 
            'name' => 'orders',
            'title' => 'orders-management',
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'admin-index','title'=>'admin-orders','invisible' => 0],
                ['name' => 'my-index','title'=>'my-orders','invisible' => 0],
                ['name' => 'refund','title'=>'refund','invisible' => 1],
                ['name' => 'transfer','title'=>'transfer','invisible' => 1]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => Chat::class, 
            'name' => 'chats',
            'title' => 'chats-management',
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'private-chat','title'=>'private-chat','invisible' => 0],
                ['name' => 'send-message','title'=>'send-message','invisible' => 1],
                ['name' => 'messages-list','title'=>'messages-list','invisible' => 1],
                ['name' => 'show-message','title'=>'show-message','invisible' => 1],
                ['name' => 'chat-contacts','title'=>'chat-contacts','invisible' => 1]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => ParentInvitation::class, 
            'name' => 'invitations',
            'title' => 'invitations',
            'svg' => 'Communication/Group.svg',
            'others' => [
                ['name' => 'admin-invitations','title'=>'admin-invitations','invisible' => 0],
                ['name' => 'parent-invitations','title'=>'parent-invitations','invisible' => 0],
                ['name' => 'child-invitations','title'=>'child-invitations','invisible' => 0],
                ['name' => 'send-invitation','title'=>'send-invitation','invisible' => 1],
                ['name' => 'accept-invitation','title'=>'accept-invitation','invisible' => 1],
                ['name' => 'reject-invitation','title'=>'reject-invitation','invisible' => 1],
                ['name' => 'remove-invitation','title'=>'remove-invitation','invisible' => 1]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'localization',
            'title' => 'localization-management',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Language::class, 
                    'name' => 'localization.languages',
                    'title' => 'languages-management',
                    'indexTitle' => ['languages-list',1],
                    'createTitle' => ['add-languages',1],
                    'editTitle' => ['update-languages',1],
                    'showTitle' => ['view-languages',1],
                    'destroyTitle' => ['delete-languages',1],
                    'svg' => '',
                ],
                [
                    'type' => Label::class, 
                    'name' => 'localization.labels',
                    'title' => 'labels-management',
                    'indexTitle' => ['labels-list',1],
                    'createTitle' => ['add-labels',1],
                    'editTitle' => ['update-labels',1],
                    'showTitle' => ['view-labels',1],
                    'destroyTitle' => ['delete-labels',1],
                    'svg' => '',
                ],
                [
                    'type' => Translation::class, 
                    'name' => 'localization.translations',
                    'title' => 'translations-management',
                    'indexTitle' => ['translations-list',1],
                    'createTitle' => ['add-translations',1],
                    'editTitle' => ['update-translations',1],
                    'showTitle' => ['view-translations',1],
                    'destroyTitle' => ['delete-translations',1],
                    'svg' => '',
                ]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'constants',
            'title' => 'constants',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Country::class, 
                    'name' => 'constants.countries',
                    'title' => 'countries-management',
                    'indexTitle' => ['countries-list',1],
                    'createTitle' => ['add-countries',1],
                    'editTitle' => ['update-countries',1],
                    'showTitle' => ['view-countries',1],
                    'destroyTitle' => ['delete-countries',1],
                    'svg' => '',
                ],
                [
                    'type' => Level::class, 
                    'name' => 'constants.levels',
                    'title' => 'levels-management',
                    'indexTitle' => ['levels-list',1],
                    'createTitle' => ['add-levels',1],
                    'editTitle' => ['update-levels',1],
                    'showTitle' => ['view-levels',1],
                    'destroyTitle' => ['delete-levels',1],
                    'svg' => '',
                ],
                [
                    'type' => Currency::class, 
                    'name' => 'constants.currencies',
                    'title' => 'currencies-management',
                    'indexTitle' => ['currencies-list',1],
                    'createTitle' => ['add-currencies',1],
                    'editTitle' => ['update-currencies',1],
                    'showTitle' => ['view-currencies',1],
                    'destroyTitle' => ['delete-currencies',1],
                    'svg' => ''
                ],
                [
                    'type' => Specialization::class, 
                    'name' => 'constants.specializations',
                    'title' => 'specializations-management',
                    'indexTitle' => ['specializations-list',1],
                    'createTitle' => ['add-specializations',1],
                    'editTitle' => ['update-specializations',1],
                    'showTitle' => ['view-specializations',1],
                    'destroyTitle' => ['delete-specializations',1],
                    'svg' => '',
                ],
                [
                    'type' => Experience::class, 
                    'name' => 'constants.experiences',
                    'title' => 'experiences-management',
                    'indexTitle' => ['experiences-list',1],
                    'createTitle' => ['add-experiences',1],
                    'editTitle' => ['update-experiences',1],
                    'showTitle' => ['view-experiences',1],
                    'destroyTitle' => ['delete-experiences',1],
                    'svg' => '',
                ],
                [
                    'type' => Situation::class, 
                    'name' => 'constants.situations',
                    'title' => 'situations-management',
                    'indexTitle' => ['situations-list',1],
                    'createTitle' => ['add-situations',1],
                    'editTitle' => ['update-situations',1],
                    'showTitle' => ['view-situations',1],
                    'destroyTitle' => ['delete-situations',1],
                    'svg' => '',
                ],
                [
                    'type' => Subject::class, 
                    'name' => 'constants.subjects',
                    'title' => 'subjects-management',
                    'indexTitle' => ['subjects-list',1],
                    'createTitle' => ['add-subjects',1],
                    'editTitle' => ['update-subjects',1],
                    'showTitle' => ['view-subjects',1],
                    'destroyTitle' => ['delete-subjects',1],
                    'svg' => '',
                ],
                [
                    'type' => DegreeType::class, 
                    'name' => 'constants.degree-types',
                    'title' => 'degree-types-management',
                    'indexTitle' => ['degree-types-list',1],
                    'createTitle' => ['add-degree-types',1],
                    'editTitle' => ['update-degree-types',1],
                    'showTitle' => ['view-degree-types',1],
                    'destroyTitle' => ['delete-degree-types',1],
                    'svg' => '',
                ],
                [
                    'type' => WeekDay::class, 
                    'name' => 'constants.week-days',
                    'title' => 'week-days-management',
                    'indexTitle' => ['week-days-list',1],
                    'createTitle' => ['add-week-days',1],
                    'editTitle' => ['update-week-days',1],
                    'showTitle' => ['view-week-days',1],
                    'destroyTitle' => ['delete-week-days',1],
                    'svg' => '',
                ],
                [
                    'type' => WorldTimezone::class, 
                    'name' => 'constants.world-timezones',
                    'title' => 'world-timezones-management',
                    'indexTitle' => ['world-timezones-list',1],
                    'createTitle' => ['add-world-timezones',1],
                    'editTitle' => ['update-world-timezones',1],
                    'showTitle' => ['view-world-timezones',1],
                    'destroyTitle' => ['delete-world-timezones',1],
                    'svg' => '',
                ],
                [
                    'type' => Outline::class, 
                    'name' => 'constants.outlines',
                    'title' => 'outlines-management',
                    'indexTitle' => ['outlines-list',1],
                    'createTitle' => ['add-outlines',1],
                    'editTitle' => ['update-outlines',1],
                    'showTitle' => ['view-outlines',1],
                    'destroyTitle' => ['delete-outlines',1],
                    'svg' => '',
                ],
                /*[
                    'type' => Frequency::class, 
                    'name' => 'constants.frequencies',
                    'title' => 'frequencies-management',
                    'indexTitle' => ['frequencies-list',1],
                    'createTitle' => ['add-frequencies',1],
                    'editTitle' => ['update-frequencies',1],
                    'showTitle' => ['view-frequencies',1],
                    'destroyTitle' => ['delete-frequencies',1],
                    'svg' => '',
                ],*/
                [
                    'type' => Price::class, 
                    'name' => 'constants.prices',
                    'title' => 'prices-management',
                    'indexTitle' => ['prices-list',1],
                    'createTitle' => ['add-prices',1],
                    'editTitle' => ['update-prices',1],
                    'showTitle' => ['view-prices',1],
                    'destroyTitle' => ['delete-prices',1],
                    'svg' => '',
                ],
                [
                    'type' => SortByTutor::class, 
                    'name' => 'constants.sort-by-tutors',
                    'title' => 'sort-by-tutors-management',
                    'indexTitle' => ['sort-by-tutors-list',1],
                    'createTitle' => ['add-sort-by-tutors',1],
                    'editTitle' => ['update-sort-by-tutors',1],
                    'showTitle' => ['view-sort-by-tutors',1],
                    'destroyTitle' => ['delete-sort-by-tutors',1],
                    'svg' => '',
                ]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'sections',
            'title' => 'sections',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Department::class, 
                    'name' => 'sections.departments',
                    'title' => 'departments-management',
                    'indexTitle' => ['departments-list',1],
                    'createTitle' => ['add-departments',1],
                    'editTitle' => ['update-departments',1],
                    'showTitle' => ['view-departments',1],
                    'destroyTitle' => ['delete-departments',1],
                    'svg' => '',
                ],
                [
                    'type' => Category::class, 
                    'name' => 'sections.categories',
                    'title' => 'categories-management',
                    'indexTitle' => ['categories-list',1],
                    'createTitle' => ['add-categories',1],
                    'editTitle' => ['update-categories',1],
                    'showTitle' => ['view-categories',1],
                    'destroyTitle' => ['delete-categories',1],
                    'svg' => '',
                ],
                [
                    'type' => Course::class, 
                    'name' => 'sections.courses',
                    'title' => 'courses-management',
                    'indexTitle' => ['courses-list',1],
                    'createTitle' => ['add-courses',1],
                    'editTitle' => ['update-courses',1],
                    'showTitle' => ['view-courses',1],
                    'destroyTitle' => ['delete-courses',1],
                    'svg' => '',
                ],
                [
                    'type' => Navigation::class, 
                    'name' => 'sections.navigations',
                    'title' => 'navigations-management',
                    'indexTitle' => ['navigations-list',1],
                    'createTitle' => ['add-navigations',1],
                    'editTitle' => ['update-navigations',1],
                    'showTitle' => ['view-navigations',1],
                    'destroyTitle' => ['delete-navigations',1],
                    'svg' => '',
                ]
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'contents',
            'title' => 'contents',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Post::class, 
                    'name' => 'contents.posts',
                    'title' => 'posts-management',
                    'indexTitle' => ['posts-list',1],
                    'createTitle' => ['add-posts',1],
                    'editTitle' => ['update-posts',1],
                    'showTitle' => ['view-posts',1],
                    'destroyTitle' => ['delete-posts',1],
                    'svg' => '',
                ],
                [
                    'type' => Page::class, 
                    'name' => 'contents.pages',
                    'title' => 'pages-management',
                    'indexTitle' => ['pages-list',1],
                    'createTitle' => ['add-pages',1],
                    'editTitle' => ['update-pages',1],
                    'showTitle' => ['view-pages',1],
                    'destroyTitle' => ['delete-pages',1],
                    'svg' => '',
                ],
                [
                    'type' => Event::class, 
                    'name' => 'contents.events',
                    'title' => 'events-management',
                    'indexTitle' => ['events-list',1],
                    'createTitle' => ['add-events',1],
                    'editTitle' => ['update-events',1],
                    'showTitle' => ['view-events',1],
                    'destroyTitle' => ['delete-events',1],
                    'svg' => '',
                ],
                [
                    'type' => Help::class, 
                    'name' => 'contents.helps',
                    'title' => 'helps-management',
                    'indexTitle' => ['helps-list',1],
                    'createTitle' => ['add-helps',1],
                    'editTitle' => ['update-helps',1],
                    'showTitle' => ['view-helps',1],
                    'destroyTitle' => ['delete-helps',1],
                    'svg' => '',
                ],
                [
                    'type' => Advertisement::class, 
                    'name' => 'contents.advertisements',
                    'title' => 'advertisements-management',
                    'indexTitle' => ['advertisements-list',1],
                    'createTitle' => ['add-advertisements',1],
                    'editTitle' => ['update-advertisements',1],
                    'showTitle' => ['view-advertisements',1],
                    'destroyTitle' => ['delete-advertisements',1],
                    'svg' => '',
                ],
                [
                    'type' => Package::class, 
                    'name' => 'contents.packages',
                    'title' => 'packages-management',
                    'indexTitle' => ['packages-list',1],
                    'createTitle' => ['add-packages',1],
                    'editTitle' => ['update-packages',1],
                    'showTitle' => ['view-packages',1],
                    'destroyTitle' => ['delete-packages',1],
                    'svg' => '',
                ]
                
            ]
        ]);
        //================================================
        $this->createMenuSubMenus([
            'type' => '', 
            'name' => 'library',
            'title' => 'library',
            'svg' => 'Home/Globe.svg',
            'children' =>[
                [
                    'type' => Video::class, 
                    'name' => 'library.videos',
                    'title' => 'videos-management',
                    'indexTitle' => ['videos-list',1],
                    'createTitle' => ['add-videos',1],
                    'editTitle' => ['update-videos',1],
                    'showTitle' => ['view-videos',1],
                    'destroyTitle' => ['delete-videos',1],
                    'svg' => '',
                ],
                [
                    'type' => Link::class, 
                    'name' => 'library.links',
                    'title' => 'links-management',
                    'indexTitle' => ['links-list',1],
                    'createTitle' => ['add-links',1],
                    'editTitle' => ['update-links',1],
                    'showTitle' => ['view-links',1],
                    'destroyTitle' => ['delete-links',1],
                    'svg' => '',
                ],
                [
                    'type' => Document::class, 
                    'name' => 'library.documents',
                    'title' => 'documents-management',
                    'indexTitle' => ['documents-list',1],
                    'createTitle' => ['add-documents',1],
                    'editTitle' => ['update-documents',1],
                    'showTitle' => ['view-documents',1],
                    'destroyTitle' => ['delete-documents',1],
                    'svg' => '',
                ],
                [
                    'type' => Image::class, 
                    'name' => 'library.images',
                    'title' => 'images-management',
                    'indexTitle' => ['images-list',1],
                    'createTitle' => ['add-images',1],
                    'editTitle' => ['update-images',1],
                    'showTitle' => ['view-images',1],
                    'destroyTitle' => ['delete-images',1],
                    'svg' => '',
                ]
            ]
        ]);
        //================================================
    }

    public function createMenuSubMenus($data,$p_id=0){
        $parentId = ++$this->id;
        Menu::query()->updateOrCreate(['id' => $parentId], ['invisible' => 0, 'type' => $data['type'], 'title' => $data['title'], 'p_id' => $p_id, 
            'route' => $data['name'], 'name' => $data['name'], 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.create|'.$data['name'].'.show|'.$data['name'].'.edit', 
            'svg' => $data['svg']
        ]);
        //===========================================    
        if(isset($data['indexTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['indexTitle'][1], 'type' => $data['type'], 'title' => $data['indexTitle'][0], 'p_id' => $parentId, 'name' => 'index', 'route' => $data['name'].'.index', 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.show'
        ]);

        //===========================================    
        if(isset($data['createTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['createTitle'][1], 'type' => $data['type'], 'title' => $data['createTitle'][0], 'p_id' => $parentId, 'name' => 'create', 'route' => $data['name'].'.create', 
            'active_routes' => $data['name'].'.create|'.$data['name'].'.edit'        
        ]);
        
        //===========================================    
        if(isset($data['showTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['showTitle'][1], 'type' => $data['type'], 'title' => $data['showTitle'][0], 'p_id' => $parentId, 'name' => 'show', 'route' => $data['name'].'.show', 
            'active_routes' => $data['name'].'.index|'.$data['name'].'.show'
        ]);

        //===========================================    
        if(isset($data['editTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['editTitle'][1], 'type' => $data['type'], 'title' => $data['editTitle'][0], 'p_id' => $parentId, 'name' => 'edit', 'route' => $data['name'].'.edit', 
            'active_routes' => $data['name'].'.create|'.$data['name'].'.edit'
        ]);

        //===========================================    
        if(isset($data['destroyTitle'])) Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $data['destroyTitle'][1], 'type' => $data['type'], 'title' => $data['destroyTitle'][0], 'p_id' => $parentId, 'name' => 'destroy', 'route' => $data['name'].'.destroy', 
            'active_routes' => $data['name'].'.destroy'        
        ]);

        //===========================================
        if(isset($data['others'])) 
            foreach($data['others'] as $submenu) 
                Menu::query()->updateOrCreate(['id' => ++$this->id], ['invisible' => $submenu['invisible'], 'type' => $data['type'], 'title' => $submenu['title'], 'p_id' => $parentId, 'name' => $submenu['name'], 'route' => $data['name'].'.'.$submenu['name'], 
                    'active_routes' => $data['name'].'.'.$submenu['name']        
                ]);
        //===========================================
        if(isset($data['children'])) 
            foreach($data['children'] as $submenu) 
                $this->createMenuSubMenus($submenu,$parentId);

    }
}
//last id 29 new 30