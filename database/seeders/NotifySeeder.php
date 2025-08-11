<?php

namespace Database\Seeders;

use App\Models\NotificationInfo;
use Illuminate\Database\Seeder;

class NotifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationInfo::query()->updateOrCreate(['id' => 1], ['n_title' => 'login', 'n_details' => 'new-login', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'success']);
        NotificationInfo::query()->updateOrCreate(['id' => 2], ['n_title' => 'register', 'n_details' => 'new-register', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);
        NotificationInfo::query()->updateOrCreate(['id' => 3], ['n_title' => 'change-password', 'n_details' => 'change-password-done', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);
        NotificationInfo::query()->updateOrCreate(['id' => 4], ['n_title' => 'change-email', 'n_details' => 'change-email-done', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);
        NotificationInfo::query()->updateOrCreate(['id' => 5], ['n_title' => 'change-name', 'n_details' => 'change-name-done', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);
        NotificationInfo::query()->updateOrCreate(['id' => 6], ['n_title' => 'change-avatar', 'n_details' => 'change-avatar-done', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);
        NotificationInfo::query()->updateOrCreate(['id' => 7], ['n_title' => 'reset-password', 'n_details' => 'reset-password-done', 'n_url'=>'/', 'n_icon' => 'fa fa-heart', 'n_color' => 'info']);

    }
}
