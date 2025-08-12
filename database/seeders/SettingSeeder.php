<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->updateOrCreate(['id' => 1], ['name' => 'site_title', 'value' => 'My New Site', 'type' => 'text']);
        Setting::query()->updateOrCreate(['id' => 2], ['name' => 'site_summary', 'value' => 'My New Site Summary', 'type' => 'text']);
        Setting::query()->updateOrCreate(['id' => 2], ['name' => 'site_description', 'value' => 'My New Site Description', 'type' => 'text']);
        Setting::query()->updateOrCreate(['id' => 3], ['name' => 'site_keywords', 'value' => 'My,New,Site,keywords', 'type' => 'text']);
        Setting::query()->updateOrCreate(['id' => 4], ['name' => 'site_logo', 'value' => '/settings/logo.png', 'type'=>'file']);
        Setting::query()->updateOrCreate(['id' => 5], ['name' => 'site_logo_inverse', 'value' => '/settings/logo-inverse.png', 'type'=>'file']);
        Setting::query()->updateOrCreate(['id' => 6], ['name' => 'site_favicon', 'value' => '/settings/favicon.png', 'type'=>'file']);
        Setting::query()->updateOrCreate(['id' => 7], ['name' => 'feez', 'value' => '10', 'type'=>'number']);

        Setting::query()->updateOrCreate(['id' => 9], ['name' => 'questions_no', 'value' => '10', 'type'=>'number']);
        Setting::query()->updateOrCreate(['id' => 10], ['name' => 'successr_ate', 'value' => '60', 'type'=>'number']);
    }
}
